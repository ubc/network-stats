<?php 


add_action('wp_ajax_network_stats_site_data', array( 'Network_Stats_Sites_Data','fetch_latest_data' ) );



/**
 * Network_Stats_Sites_Data class.
 * 
 * @extends Network_Stats_Data
 */
class Network_Stats_Sites_Data extends Network_Stats_Data {
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @param string $name (default: 'sites')
	 * @return void
	 */
	function __construct( $name = 'sites' ) {
		$this->name = $name;
		$this->start();
		return $this;
	}
	
	/**
	 * fetch_latest_data function.
	 * 
	 * @access public
	 * @return void
	 */
	function fetch_latest_data( $page = 1 ) {
		global $wpdb;
		$size = 5;
		
		
		//get the latest data 
		$this->get_data();
		
		$page = ( is_integer( (int)$page ) ? $page : 1);
		
		
		$start_limit = ($page-1)*$size;
		$finish_limit = $page*$size +1;
		
		$blogs = $wpdb->get_results( 
			$wpdb->prepare( 
				"SELECT blog_id, domain, path, registered, last_updated, public, archived, mature, spam, deleted 
				FROM $wpdb->blogs ORDER BY blog_id ASC  LIMIT %d, %d", 
				$start_limit,
				$finish_limit
			)
		);
		
		if( $blogs ) {
			$i = 1;
			foreach( $blogs as $blog ) {
				
				if( $i > $size )
					break;
				
				$blogname = get_blog_option( $blog->blog_id, 'blogname' );
				$blogFacultyUBC =  get_blog_option( $blog->blog_id, 'blogFacultyUBC'  );
				$blogPurposeUBC = get_blog_option( $blog->blog_id, 'blogPurposeUBC'  );
					
				$this->append_to_data( array( 
					'name'		=> $blogname,
					'site_id'	=> $blog->blog_id,
					'registered'=>  $blog->registered,
					'last_updated'=> $blog->last_updated,
					'public'	=>  $blog->public,
					'archived'	=>  $blog->archived,
					'mature'	=>  $blog->mature,
					'spam'		=>  $blog->spam,
					'deleted'	=>  $blog->deleted,
					'site_url'	=> 'http://' . $blog->domain . $blog->path,
					'faculty'   => $blogFacultyUBC,
					'purpose'   => $blogPurposeUBC
					) 
				);
				$i++;

			}
			
		}
		
		if( count($blogs) > $size ){
			$this->temp_save();
			// lets get the next 5 items 
			return array( 'next_page' => $page+1, 'data' => $this->data );
			
			
		} else {
			$this->update();
			// we are done
			return array( 'next_page' => 'finished', 'data' => $this->data);
		}	
	}
}
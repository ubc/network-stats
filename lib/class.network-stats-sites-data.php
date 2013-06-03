<?php 


add_action('wp_ajax_network_stats_site_data', array( 'Network_Stats_Sites_Data','fetch_latest_data' ) );

class Network_Stats_Sites_Data extends Network_Stats_Data {
	
	function __construct( $name = 'sites' ) {
		$this->name = $name;
		$this->start();
	}
	
	function fetch_latest_data( ) {
		global $wpdb;

		$blogs = $wpdb->get_results( "SELECT blog_id, domain, path, registered, last_updated, public, archived, mature, spam, deleted FROM $wpdb->blogs ORDER BY domain ASC" );
				
		if( $blogs ) {

			foreach( $blogs as $blog ) {
				
				$blogname = get_blog_option( $blog->blog_id, 'blogname' );
				$blogFacultyUBC =  get_blog_option( $blog->blog_id, 'blogFacultyUBC'  );
				$blogPurposeUBC = get_blog_option( $blog->blog_id, 'blogPurposeUBC'  );
					
				$this->append_to_data( array( 
					
					'name'		=> $blogname,
					'site_id'	=> $blog->blog_id,
					'registered'=>  $blog->registered,
					'last_updated'=>  $blog->last_updated,
					'public'=>  $blog->public,
					'archived'=>  $blog->archived,
					'mature'=>  $blog->mature,
					'spam'=>  $blog->spam,
					'deleted'=>  $blog->deleted,
					'site_url'	=> 'http://' . $blog->domain . $blog->path,
					'faculty'   => $blogFacultyUBC,
					'purpose'   => $blogPurposeUBC
					
				) );

			}

		}
		// var_dump($this->data);
		$this->update();
		return $this->data;
		
	}
	
	function temp_append_to_data() {
		
	
	}
	
	
	
}
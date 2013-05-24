<?php 


class Network_Stats_Sites_Data extends Network_Stats_Data {
	
	function __construct( $name = 'sites' ) {
		$this->name = $name;
		$this->start();
	}
	
	function fetch_latest_data(){
		global $wpdb;
		
		$blogs  = $wpdb->get_results("SELECT blog_id, domain, path FROM $wpdb->blogs ORDER BY domain ASC");
		
		if ( $blogs ) {
			foreach ($blogs as $blog) {
				
				$theme = $wpdb->get_col("SELECT option_value FROM ".$wpdb->base_prefix.$blog->blog_id."_options WHERE option_name = 'current_theme'");
				$theme_name = ( empty( $theme[0] ) ? '' : $theme[0]);
				$this->append_to_data( array(
					'theme' => $theme[0],
					'site_id' => $blog->blog_id,
					'site_url' => 'http://'.$blog->domain.$blog->path
				) );
			}
		}
		// do stats
		if( $blogs ) {
			foreach ($blogs as $blog) {
				
				
			}
		}
		
		
		$this->update();
	}
}
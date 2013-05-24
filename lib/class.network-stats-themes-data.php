<?php 


class Network_Stats_Themes_Data extends Network_Stats_Data {
	
	function __construct( $name = 'themes' ) {
		$this->name = $name;
	}
	
	function fetch_latest_data(){
		
		$blogs  = $wpdb->get_results("SELECT blog_id, domain, path FROM $wpdb->blogs ORDER BY domain ASC");
		
		$blogtheme = array();
		if ($blogs) {
			foreach ($blogs as $blog) {
				$blogOptionsTable  = "wp_".$blog->blog_id."_options";
				$blogtemplate = $wpdb->get_col("SELECT option_value FROM $blogOptionsTable WHERE option_name = 'current_theme'");
				if($blogtemplate)
					$blogtheme[$blog->blog_id] = $blogtemplate[0];
			}
		}
		// do stats
		if($blogs) {
			foreach ($blogs as $blog) {
			
				$this->append_to_data( array(
					'theme' => $blogtheme[$blog->blog_id],
					'site_id' => $blog->blog_id,
					'site_url' => 'http://'.$blog->domain.$blog->path
				));
			}
		}
		
		$this->update();
	}
}
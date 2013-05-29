<?php
/*
 * Network Stats Plugins Data class file
 */

class Network_Stats_Plugins_Data extends Network_Stats_Data {

	protected $total_plugins = null;

	function __construct ( $name = 'plugins' ) {
		$this->name = $name;
		$this->total_plugins = get_plugins();		// get all of the plugins and return it to an array
		$this->start();
	}

	function fetch_latest_data() {
		
		global $wpdb;

		$blogs = $wpdb->get_results( "SELECT blog_id, domain, path FROM $wpdb->blogs ORDER BY domain ASC" );
				
		if( $blogs ) {

			foreach( $blogs as $blog ) {

				$plugins = get_blog_option( $blog->blog_id, 'active_plugins' );
				
				$this->append_to_data( array( 
					'plugins'	=> $plugins,
					'site_id'	=> $blog->blog_id,
					'site_url'	=> 'http://' . $blog->domain . $blog->path
				) );

			}

		}

		$this->update();

	}

	/**
	 * report_plugins_total function
	 * This function will return the total number of plugins in the system
	 * @access public
	 * @return count( $this->total_plugins )
	 */
	function report_plugins_total() {
		
		return count( $this->total_plugins );

	}

	/**
	 * report_plugins_usage function
	 * This function counts the total usage of each plugin
	 * @access public
	 * @return
	 */
	function report_plugins_usage( $decoded_json ) {

		//var_dump( $decoded_json );
		foreach( $decoded_json as $key => $value ) {

			var_dump( $decoded_json[$key]["plugins"] );

		}

	}

	/**
	 * report_sites_plugins function
	 * This function returns a list of the contents of an array if the parameter passed
	 * is an array otherwise it will just return whatever is passed in (kinda redundant there will fix later)
	 * @access public
	 * @param $plugins_array
	 * @return $list or $plugins_array
	 */
	function report_sites_plugins( $plugins_array ) {

		if( is_array( $plugins_array ) ) {
			$list = '<ul>';
			foreach( $plugins_array as $plugin ) {
				$list .= '<li>' . $plugin . '</li>';
			}

			$list .= '</ul>';
			return $list;
		}
		
		return $plugins_array;
	}
} 
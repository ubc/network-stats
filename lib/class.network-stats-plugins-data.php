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
	 * generate_plugins_table function
	 * This function generates an associative array containing all of the 
	 * relevant information needed for the plugin table
	 * @access public
	 * @return $plugins_data
	 */
	function generate_plugins_table( $decoded_json ) {
		//var_dump( $this->total_plugins );
		foreach( $this->total_plugins as $plugin_key => $plugin_value ) {
			$plugins_data[] = array(
				'name' => $plugin_value['Name'],
				'num_sites' => $this->get_num_sites( $plugin_key, $decoded_json ),
				'user_network' => is_plugin_active_for_network( $plugin_key ) ? "Network Activated" : "User Activated",
				'sites' => 'swag'
			);
			//echo $plugin_key . '<br/>';
		}
		//var_dump( $plugins_data );
		return $plugins_data;

	}

	/**
	 * get_num_sites function
	 * This funciton counts the number of times $plugin_key appears in $json_data
	 * @param $plugin_key, 
	 * @access public
	 * @return $num_sites
	 */
	function get_num_sites( $plugin_key, $json_data ) {
		/*$counts = array();	// this array will contain the count for each plugin
		$plugin_count = 0;
		var_dump( $this->total_plugins );
		foreach( $decoded_json as $key => $sub_arr ) {
			// add to the current group count if it exists
			/*if( isset( $counts[$key['plugins']] ) ) {
				$counts[$key['plugins']]++;
			}
			else {
				$counts[$key['plugins']] = 1;
			}
			var_dump ($sub_arr['plugins']);
			echo '<br/><br/>';
		}
		//var_dump( $counts );
		//return $counts;*/

		$num_sites = 0;
		foreach ( $json_data as $key => $sub_arr ) {
			if( in_array( $plugin_key, $sub_arr['plugins'] ) ) {
				$num_sites++;
			}
		}
		return $num_sites;
	}

	/**
	 * report_sites_plugins function
	 * This function will create a list of websites that are using the plugin
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
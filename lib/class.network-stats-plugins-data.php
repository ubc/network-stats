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

	/**
	 * fetch_latest_data funciton
	 * This funciton will create/update the JSON file associated with the current view
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
				$wpdb->prepare( "SELECT blog_id, domain, path FROM $wpdb->blogs ORDER BY domain ASC LIMIT %d, %d", 
				$start_limit,
				$finish_limit
				)
			);

				
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

		if( count($blogs) > $size ){
			$this->temp_save();
			// lets get the next 5 items 
			return array( 'next_page' => $page+1, 'data' => $this->data );
			
			
		} else {
			// fix the $this->data to be more usefull

			$this->data = $this->generate_plugins_table($this->data);
			$this->update();
			// we are done
			return array( 'next_page' => 'finished', 'data' => $this->data);
		}	

	}

	/**
	 * report_plugins_total function
	 * This function will return the total number of plugins in the system
	 * @access public
	 * @return int
	 */
	function report_plugins_total() {
		
		return count( $this->total_plugins );

	}

	/**
	 * generate_plugins_table function
	 * This function generates an associative array containing all of the 
	 * relevant information needed for the plugin table
	 * @access public
	 * @return array
	 */
	function generate_plugins_table( $decoded_json ) {
		//var_dump( $this->total_plugins );
		foreach( $this->total_plugins as $plugin_key => $plugin_value ) {
			$site_list = $this->report_sites_plugins( $plugin_key, $decoded_json );
			$plugins_data[] = array(
				'name' => $plugin_value['Name'],
				'num_sites' => $this->get_num_sites( $plugin_key, $decoded_json ),
				'user_network' => is_plugin_active_for_network( $plugin_key ) ? "Network Activated" : "User Activated",
				'sites' => empty( $site_list ) ? array("None") : $site_list 
			);
			//echo $plugin_key . '<br/>';
		}
		//var_dump( $plugins_data );
		return $plugins_data;

	}

	/**
	 * get_num_sites function
	 * This funciton counts the number of times $plugin_key appears in $json_data
	 * @param $plugin_key array
	 * @param $json_data array
	 * @access private
	 * @return null|array
	 */
	private function get_num_sites( $plugin_key, $json_data ) {

		$num_sites = 0;
		foreach( $json_data as $key => $sub_arr ) {
			if( in_array( $plugin_key, $sub_arr['plugins'] ) ) {
				$num_sites++;
			}
		}
		return $num_sites;
	}

	/**
	 * report_sites_plugins function
	 * This function will return a list of websites that are using the plugin
	 * @access private
	 * @param $plugins_array
	 * @return $site_list
	 */
	private function report_sites_plugins( $plugin_key, $json_data ) {

		foreach( $json_data as $key => $sub_arr ) {
			if( in_array( $plugin_key, $sub_arr['plugins'] ) ) {
				$site_list[] = $sub_arr['site_url'];
			}
		}
		//var_dump( $site_list );
		return $site_list;
	}
} 
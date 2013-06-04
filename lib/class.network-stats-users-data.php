<?php
/*
 *	Network Stats Users Data class file
 */

add_action( 'wp_ajax_network_stats_users_data', array( 'Network_Sites_Users_Data', 'fetch_latest_data' ) );

class Network_Stats_Users_Data extends Network_Stats_Data {

	function __construct( $name = 'users') {
		$this->name = $name;
		$this->start();
	}

	/**
	 * fetch_latest_data function
	 * This function will create/update the JSON file associated with the current view
	 * @access public
	 * @return void
	 */
	function fetch_latest_data() {

		//global $wpdb;

		//$blogs = $wpdb->get_results( "SELECT blog_id, domain, path FROM $wpdb->blogs ORDER BY domain ASC" );
		$args = array(
			'blog_id'	=> null,
			'orderby'	=> 'ID'
		);
		$users = get_users( $args );
		//print_r( $users );

		if( $users ) {

			foreach( $users as $user ) {

				$user_blogs = get_blogs_of_user( $user->ID );	// get a list of sites that the user belongs to
				if( $user_all_meta = get_user_meta( $user->ID ) ) {
					$user_all_meta = array_map( function( $a ) { return $a[0]; }, get_user_meta( $user->ID ) );
					// source: http://codex.wordpress.org/Function_Reference/get_user_meta
					// use the array mapping to get only the first index of each result
					// necessary because by default, the function will return an array that needs to be derefenced to grab the values
				}

				$this->append_to_data( array(
					'user_id'		=> $user->ID,
					'name'			=> $user_all_meta['first_name'] . ' ' . $user_all_meta['last_name'],
					'user_email'	=> $user->user_email,
					'role'			=> $user->roles,
					'registered'	=> $user->user_registered,
					'sites_array'	=> $user_blogs					
				) );

			}

		}

		$this->update();

	}

	/**
	 * generate_users_data function
	 * This function will generate an array containing the necessary information gathered from the blog users
	 * @access public
	 * @param array
	 * @return array
	 */
	function generate_users_data( $decoded_json ) {
		foreach( $decoded_json as $key => $sub_arr ) {
			$users = $this->grab_users_data( $sub_arr['users'] );
			//print_r( $users );
			//echo '<hr/>';
			$filtered_data[] = array(
				'data' => $users,
				'site_id' => $sub_arr['site_id'],
				'site_url' => $sub_arr['site_url']
			);

		}
		//print_r( $filtered_data );
		return $filtered_data;
	}

	/**
	 * grab_users_data function
	 * This function will parse through the users sub-array and grab the data needed from it
	 * @access private
	 * @param array
	 * @return array
	 */
	private function grab_users_data( $users_array ) {
		foreach( $users_array as $key => $value ) {
			$grabbed_data[] = array(
				'email' => $value['data']['user_email'],
				'role' => $value['roles'][0],
				'registered' => $value['data']['user_registered']
			);
		}
		//print_r($grabbed_data);
		return $grabbed_data;
	}
}
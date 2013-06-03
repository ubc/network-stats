<?php
/*
 *	Network Stats Users Data class file
 */

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

		global $wpdb;

		$blogs = $wpdb->get_results( "SELECT blog_id, domain, path FROM $wpdb->blogs ORDER BY domain ASC" );

		if( $blogs ) {

			foreach( $blogs as $blog ) {
				$blog_users = get_users( 'blog_id=' . $blog->blog_id );	// generates an array of users for each blog

				$this->append_to_data( array(
					'users'		=> $blog_users,
					'site_id'	=> $blog->blog_id,
					'site_url' 	=> 'http://' . $blog->domain . $blog->path
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
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
	function fetch_latest_data( $page = 1 ) {

		global $wpdb;
		$size = 5;								// this will limit the number of users grabbed to whatever size is set as

		$this->get_data();

		$page = ( is_integer( (int)$page ) ? $page : 1 );

		$start_limit = ($page - 1) * $size;		// lower limit: will use for the offset
		$finish_limit = $page * $size + 1;		// upper limit: will use for the number

		$args = array(
			'blog_id'	=> null,
			'orderby'	=> 'ID',
			'number'	=> $size,
			'offset'	=> $start_limit
		);	// offset and number is necessary for paging please see
			// http://codex.wordpress.org/Class_Reference/WP_User_Query
			// under the Pagination Parameters section
		$users = get_users( $args );

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

		if( count($users) > $size ) {
			$this->temp_save();
			// get the next 5 items
			return array( 'next_page' => $page + 1, 'data' => $this->data );
		}
		else {
			$this->update();
			// we are done
			return array( 'next_page' => 'finished', 'data' => $this->data );
		}

	}

}
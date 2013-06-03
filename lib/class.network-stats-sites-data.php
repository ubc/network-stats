<?php 


add_action('wp_ajax_network_stats_site_data', array( 'Network_Stats_Sites_Data','fetch_latest_data' ) );

class Network_Stats_Sites_Data extends Network_Stats_Data {
	
	function __construct( $name = 'sites' ) {
		$this->name = $name;
		$this->start();
	}
	
	function fetch_latest_data( ) {
	
		
	}
	
	function temp_append_to_data() {
		
	
	}
	
	
	
}
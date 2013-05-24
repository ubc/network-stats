<?php 




class Network_Stats_Data {

	protected $name = null;
	protected $file_name = null;
	
	protected $setting;
	protected $data = array();
	protected $path_to;
	
	
	function __construct( $name ) {
		
		$this->name = sanitize_title($name);	
		$this->file_name = $this->name.'json.js';
		$this->setting = 'network-last-updated-'.$this->name;
		$this->path_to = NETWORKSTATS_PATH.'json/';
	}
	
	/**
	 * update function.
	 * 
	 * @access public
	 * @param mixed $data
	 * @return void
	 */
	function update( $data = null) {
		// echo 'hello';
		/*
		if( !empty($data) ){
			$data = $this->data;
		}
		*/
		//$this->update_file( $data );
		
		// update the date setting so know when it was last created
		//update_site_option( $this->setting, time() );
	}
	
	function append_to_data( $more_data ){
		$this->data[] = $more_data;
	}
	
	function update_file( $content ){
		
		if(! file_put_contents( $this->path_to. $this->file_name ,  $content ) ){
			
			$this->error[] = 'couldn\'t create file '.$this->file_name;
		}
	
	}
	
	/**
	 * delete function.
	 * 
	 * @access public
	 * @return void
	 */
	function delete() {
		
		
	}
	
	/**
	 * get function.
	 *
	 * @access public
	 * @param mixed $data
	 * @return void
	 */
	function get() {
		
		
	}
	
	/**
	 * get_updated_since function.
	 * Time since last update
	 * @access public
	 * @return void
	 */
	function get_updated_since(){
	
		return $this->time_elapsed( time() - get_site_option( $this->setting ) ); 
	}
	
	/**
	 * time_elapsed function.
	 * source : http://php.net/manual/en/function.time.php
	 * @access public
	 * @param mixed $secs
	 * @return void
	 */
	function time_elapsed( $secs ) {
	    $bit = array(
	        ' year'        => $secs / 31556926 % 12,
	        ' week'        => $secs / 604800 % 52,
	        ' day'        => $secs / 86400 % 7,
	        ' hour'        => $secs / 3600 % 24,
	        ' minute'    => $secs / 60 % 60,
	        ' second'    => $secs % 60
	        );
	       
	    foreach($bit as $k => $v){
	        if($v > 1)$ret[] = $v . $k . 's';
	        if($v == 1)$ret[] = $v . $k;
	        }
	    array_splice($ret, count($ret)-1, 0, 'and');
	    $ret[] = 'ago.';
	   
	    return join(' ', $ret);
    }
    
	/**
	 * get_url function.
	 * 
	 * @access public
	 * @return void
	 */
	function get_url() {
		return NETWORKSTATS_URL. 'json/'.$this->file_name;
			
	}
	


}



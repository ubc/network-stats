<?php 




class Network_Stats_Data {

	protected $name = null;
	protected $file_name = null;
	
	protected $setting;
	protected $data = array();
	protected $path_to;
	
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @param mixed $name
	 * @return void
	 */
	function __construct( $name ) {
		
		$this->name = sanitize_title($name);	
		$this->start();
	}
	
	/**
	 * start function.
	 * 
	 * @access public
	 * @return void
	 */
	function start(){
		$this->file_name = $this->name.'.json.js';
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
	function update( $data = null ) {
		// echo 'hello';
		
		if( empty( $data ) ){
			$data = $this->data;
		}
		
		$this->update_file( json_encode( $data ) );
		
		// update the date setting so know when it was last created
		update_site_option( $this->setting, time() );
	}
	
	/**
	 * append_to_data function.
	 * 
	 * @access public
	 * @param mixed $more_data
	 * @return void
	 */
	function append_to_data( $more_data ){
		
		$this->data[] = $more_data;
	}
	
	/**
	 * update_file function.
	 * 
	 * @access public
	 * @param mixed $content
	 * @return void
	 */
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
		
		$json = json_decode( file_get_contents( $this->path_to . $this->file_name ) );
		if( isset( $json ) ) {

			return $json;

		}
		else {

			return $this->error[] = "Problem retrieving the data file";

		}
		
	}
	
	/**
	 * get_updated_since function.
	 * Time since last update
	 * @access public
	 * @return void
	 */
	function updated_since(){
	
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
		if( $secs < 60 )
		return 'less then a minute';
	    $bit = array(
	        ' year'        => $secs / 31556926 % 12,
	        ' week'        => $secs / 604800 % 52,
	        ' day'        => $secs / 86400 % 7,
	        ' hour'        => $secs / 3600 % 24,
	        ' min'    => $secs / 60 % 60,
	        ' sec'    => $secs % 60
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
		if( file_exists(NETWORKSTATS_PATH.'json/'.$this->file_name) ) 
			return NETWORKSTATS_URL. 'json/'.$this->file_name;
		else
			return false;
		
	}
	


}



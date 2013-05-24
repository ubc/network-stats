<?php 

	
	$theme_data_object = new Network_Stats_Themes_Data();
	$url = $theme_data_object->get_url();
	
	
	if( empty($url) ){
		
		$theme_data_object->fetch_latest_data();
		
		$url = $theme_data_object->get_url();
	}
	
	
	?>
	<p>Last updated <em><?php echo $theme_data_object->updated_since(); ?></em></p>
	
	
	<script>
	console.log( jQuery );
	
	jQuery.ajax({
		dataType: "json",
		url: '<?php echo $url; ?>',
		success: function(data){
			console.log(data);
			
		}
		});
	</script>
	
	
	

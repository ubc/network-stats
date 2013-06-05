<?php 


	
	$data_object = new Network_Stats_Sites_Data();
	
	$url = $data_object->get_url();
	
	if( empty( $url ) ) { ?>
		
	<?php 
	}
	
	?>
	<p>Last updated <em><?php echo $data_object->updated_since(); ?></em></p>
	
	<div id="data">
		
	</div>
	
	<script>
		var network_stats_refresh_data = <?php echo ( empty( $url ) ? 'true': 'false'); ?> 		
		var network_stats_page = 'sites';
		var json_url = '<?php echo $url; ?>';
	</script>
	




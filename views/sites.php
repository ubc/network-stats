<?php 


	
	$data_object = new Network_Stats_Sites_Data();
	$url = $data_object->get_url();
	
	
	if( empty( $url ) ) { ?>
		<script type="">
			var data = {
				action: 'network_stats_site_data'
			};
			
			
			jQuery.post(ajaxurl, data, function(response) {
				
					alert('Got this from the server: ' + response);
			});
			
		</script>
	<?php 
	}
	
	?>
	<p>Last updated <em><?php echo $data_object->updated_since(); ?></em></p>
	
	
	<script type="">
	
	
	jQuery.ajax({
		dataType: "json",
		url: '<?php echo $url; ?>',
		success: function(data){
			console.log(data);
		}
		});
	</script>
	




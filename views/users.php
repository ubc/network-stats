<?php

$users_data_object = new Network_Stats_Users_Data();
$url = $users_data_object->get_url();

if( empty( $url ) ) {

	//$users_data_object->fetch_latest_data();
	?>
	<script type="text/javascript">
		var data = {
			action: 'network_stats_users_data'
		};

	</script>
	<?php

}

?>

<p>Last updated <em><?php echo $users_data_object->updated_since(); ?></em></p>

<script type="text/javascript">
	jQuery.ajax( {
		dataType: "json",
		url: "<?php echo $url; ?>",
		success: function(data) {
			console.log(data);
		}
	} );
</script>
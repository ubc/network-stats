<?php

$plugin_data_object = new Network_Stats_Plugins_Data();
$url = $plugin_data_object->get_url();

if( empty( $url ) ) {

	$plugin_data_object->fetch_latest_data();

}

$plugins_json_data = $plugin_data_object->get();

$total_plugins_num = $plugin_data_object->report_plugins_total();
$table_array = $plugin_data_object->generate_plugins_table( $plugins_json_data );
//echo json_encode( $table_array );
?>

<p><b>Total number of plugins available: </b><em><?php echo $total_plugins_num ?></em></p>

<script>
	/*var dataset = <?php echo json_encode( $table_array ); ?>	// dataset to be used by the pie chart
	var dataset_dict = {};

	for(var i = dataset.length - 1; i >= 0; i--) {
		dataset_dict[dataset[i]['name']] = dataset[i]['num_sites'];
	}

	for(var key in dataset_dict ) {
		// for-in loop goes over all properties including inherited properties
		// let's use only our own properties
		if( dataset_dict.hasOwnProperty(key) ) {
			console.log("name: " + key + ", num_sites: " + dataset_dict[key]);
		}
	}*/
</script>
<h2>Plugins Usage Table</h2>
<table border="1">
	<tr>
		<th>Plugin Name</th>
		<th>Number of Sites</th>
		<th>User/Network Enabled</th>
		<th>List of Sites</th>
	</tr>
	<?php
	foreach( $table_array as $row ) {
		//var_dump( $row['sites'] );
		//echo '<br/><br/>';
		?>
		<tr>
			<td><?php echo $row['name']; ?></td>
			<td><?php echo $row['num_sites'] ?></td>
			<td><?php echo $row['user_network'] ?></td>
			<td>
				<!-- need to be able to add a way to collapse this table -->
				<ul>
				<?php
				foreach( $row['sites'] as $site ) {
					echo '<li>' . $site . '</li>';
				}
				?>
				</ul>
			</td>
		</tr>
		<?php
	}
	?>
</table>

<p>Last updated <em><?php echo $plugin_data_object->updated_since(); ?></em></p>

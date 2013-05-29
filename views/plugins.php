<?php

$plugin_data_object = new Network_Stats_Plugins_Data();
$url = $plugin_data_object->get_url();

if( empty( $url ) ) {

	$plugin_data_object->fetch_latest_data();

}

$json_data = $plugin_data_object->get();

$total_plugins_num = $plugin_data_object->report_plugins_total();
?>
<p><b>Total number of plugins available: </b><em><?php echo $total_plugins_num ?></em></p>

<h2>Total Plugin Usage</h2>
<?php $table_array = $plugin_data_object->generate_plugins_table( $json_data ); ?>
<table border="1">
	<tr>
		<th>Plugin Name</th>
		<th>Number of Sites</th>
		<th>User/Network Enabled</th>
		<th>List of Sites</th>
	</tr>
	<?php
	foreach( $table_array as $row ) {
		echo '<tr>';
			echo '<td>' . $row['name'] . '</td>';
			echo '<td>' . $row['num_sites'] . '</td>';
			echo '<td>' . $row['user_network'] . '</td>';
			echo '<td>' . $row['sites'] . '</td>';
		echo '</tr>';
	}
	?>
</table>

<p>Last updated <em><?php echo $plugin_data_object->updated_since(); ?></em></p>
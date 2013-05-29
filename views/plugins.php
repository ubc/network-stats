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
<?php  //$plugin_data_object->report_plugins_usage( $json_data ); ?>
<table border="1">
	<tr>
		<th>Site URL</th>
		<th>Plugins</th>
	</tr>
	<?php
		foreach( $json_data as $item ) {
	?>
			<tr>
				<td><?php echo $plugin_data_object->report_sites_plugins( $item['site_url'] ); ?></td>
				<td><?php echo $plugin_data_object->report_sites_plugins( $item['plugins'] ); ?></td>
			</tr>
	<?php
		}
	?>
</table>

<p>Last updated <em><?php echo $plugin_data_object->updated_since(); ?></em></p>
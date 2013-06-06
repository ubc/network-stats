<?php

$plugin_data_object = new Network_Stats_Plugins_Data();
$url = $plugin_data_object->get_url();
?>
<p>Last updated <em><?php echo $plugin_data_object->updated_since(); ?></em></p>
<h3>Plugins Usage Table <small id="plugins-total">Total number of plugins available: </small></h3>


<table id="data-table" class="widefat">
	<thead>
		<tr>
			<th>Plugin Name</th>
			<th>Number of Sites</th>
			<th>User/Network Enabled</th>
			<th>List of Sites</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
	<tfoot>
		<tr>
			<th>Plugin Name</th>
			<th>Number of Sites</th>
			<th>User/Network Enabled</th>
			<th>List of Sites</th>
		</tr>

	</tfoot>
	<?php
	/*
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
	*/
	?>
</table>	

<script>
	var network_stats_refresh_data = <?php echo ( empty( $url ) ? 'true': 'false'); ?> 		
	var network_stats_page = 'plugins';
	var json_url = '<?php echo $url; ?>';
</script>
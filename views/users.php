<?php

$users_data_object = new Network_Stats_Users_Data();
$url = $users_data_object->get_url();

?>
<p>Last updated <em><?php echo $users_data_object->updated_since(); ?></em></p>

<p id="users-total">Total number of users: </p>

<table id="data-table" class="widefat">
	<thead>
		<tr>
			<th>User ID</th>
			<th>Name</th>
			<th>Email</th>
			<th>Role(s)</th>
			<th>Registered</th>
			<th>Site(s)</th>
		</tr>
	</thead>
	<tbody>

	</tbody>
	<tfoot>
		<tr>
			<th>User ID</th>
			<th>Name</th>
			<th>Email</th>
			<th>Role(s)</th>
			<th>Registered</th>
			<th>Site(s)</th>
		</tr>
	</tfoot>
</table>

<script type="text/javascript">
	var network_stats_refresh_data = <?php echo ( empty( $url ) ? 'true': 'false'); ?> 		
	var network_stats_page = 'users';
	var json_url = '<?php echo $url; ?>';
</script>
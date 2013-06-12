<?php

$users_data_object = new Network_Stats_Users_Data();
$url = $users_data_object->get_url();

$users_test = get_users('blog_id=null');


?>

<p>Last updated <em><?php echo $users_data_object->updated_since(); ?></em></p>

<p id="users-total">Total number of users: </p>
<p id="average-sites-per-user">Average sites per user: </p>

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
		<!-- table contents generated using JSON data -->
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

<h3>Activity</h3>
<div id="user-activity"></div>

<h3>User Pie Chart</h3>
<div id="user-pie-chart"></div>

<h3>Faculties Bar Graph</h3>
<div id="faculties-bar-graph"></div>

<h3>Role Bar Graph</h3>
<div id="role-bar-graph"></div>

<h3>Registration per Time</h3>
<div id="registration-per-time"></div>

<h3>Sites per User</h3>
<div id="user-number-sites"></div>

<script type="text/javascript">
	var network_stats_refresh_data = <?php echo ( empty( $url ) ? 'true': 'false'); ?> 		
	var network_stats_page = 'users';
	var json_url = '<?php echo $url; ?>';
</script>
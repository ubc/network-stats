<?php

$users_data_object = new Network_Stats_Users_Data();
$url = $users_data_object->get_url();

if( empty( $url ) ) {

	$users_data_object->fetch_latest_data();

}

$users_json_data = $users_data_object->get();
$users = $users_data_object->generate_users_data( $users_json_data );
//var_dump( $users );
?>
<table border="1">
	<tr>
		<th>User Email</th>
		<th>Role</th>
		<th>Registration Date</th>
		<th>Site ID</th>
		<th>Site URL</th>
	</tr>
	<?php
	foreach( $users as $user ) {
		foreach( $user as $data_array ) {
			//var_dump( $user );
			if( is_array( $data_array ) ) {
				foreach( $data_array as $data ) {
					?>
					<tr>
						<td><?php echo $data['email']; ?></td>
						<td><?php echo $data['role']; ?></td>
						<td><?php echo $data['registered']; ?></td>
						<td><?php echo $user['site_id']; ?></td>
						<td><?php echo $user['site_url']; ?></td>
						<?php //var_dump( $value ); ?>
					</tr>
					<?php
				}
			}
		}
	}
	?>
</table>

<p>Last updated <em><?php echo $users_data_object->updated_since(); ?></em></p>
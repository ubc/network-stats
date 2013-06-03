<?php

$users_data_object = new Network_Stats_Users_Data();
$url = $users_data_object->get_url();

if( empty( $url ) ) {

	$users_data_object->fetch_latest_data();

}

$users_json_data = $users_data_object->get();
$users_array = $users_data_object->generate_users_data( $users_json_data );

?>

<p>Last updated <em><?php echo $users_data_object->updated_since(); ?></em></p>
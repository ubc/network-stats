<?php

$users_data_object = new Network_Stats_Users_Data();
$url = $users_data_object->get_url();

?>
<p>Last updated <em><?php echo $users_data_object->updated_since(); ?></em></p>
<p id="users-total">Total number of users: </p>
<?php var_dump( $users_data_object->temp_var ); ?>
<script type="text/javascript">
	var network_stats_refresh_data = <?php echo ( empty( $url ) ? 'true': 'false'); ?> 		
	var network_stats_page = 'users';
	var json_url = '<?php echo $url; ?>';
</script>
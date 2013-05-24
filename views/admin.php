<?php
/**
* Represents the view for the administration dashboard.
*
* This includes the header, options, and other information that should provide
* The User Interface to the end user.
*
* @package PluginName
* @author Your Name <email@example.com>
* @license GPL-2.0+
* @link http://example.com
* @copyright 2013 Your Name or Company Name
*/
?>
<div class="wrap">

<?php screen_icon(); ?>
<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

<!-- TODO: Provide markup for your options page here. -->
	<h2 class="nav-tab-wrapper">
	<a href="#" class="nav-tab nav-tab-active">Sites</a>
	<a href="#" class="nav-tab ">Users</a>
	<a href="#" class="nav-tab">Plugins</a>
	<a href="#" class="nav-tab">Themes</a>
</h2>
</div>
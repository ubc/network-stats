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

include_once(NETWORKSTATS_PATH.'lib/class.network-stats-data.php');
include_once(NETWORKSTATS_PATH.'lib/class.network-stats-'.NetworkStats::$view.'-data.php');
?>
<div class="wrap">

<h2><?php echo esc_html( get_admin_page_title() ); ?> <div id="update-shell"></div></h2>

<!-- TODO: Provide markup for your options page here. -->
	<h3 class="nav-tab-wrapper">
		<?php 
		foreach( NetworkStats::$tabs as $tab ):
			
			NetworkStats::create_tab( $tab );
			
		endforeach;
		
	?>
	</h3>
	
	<?php 
		require_once(NETWORKSTATS_PATH .'views/'.NetworkStats::$view.'.php');
	?>
</div>
<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that also follow
 * WordPress coding standards and PHP best practices.
 *
 * @package   Network Stats
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 *
 * @wordpress-plugin
 * Plugin Name: Network Stats
 * Plugin URI:  github.com/ubc/network-stats
 * Description: Fun
 * Version:     1.0.0
 * Author:      TODO
 * Author URI:  TODO
 * Text Domain: plugin-name-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'NETWORKSTATS_PATH', plugin_dir_path(__FILE__) );
// TODO: replace `class-plugin-name.php` with the name of the actual plugin's class file
require_once( plugin_dir_path( __FILE__ ) . 'lib/class.network-stats.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
// TODO: replace NetworkStats with the name of the plugin defined in `class-plugin-name.php`
register_activation_hook( __FILE__, array( 'NetworkStats', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'NetworkStats', 'deactivate' ) );

// TODO: replace NetworkStats with the name of the plugin defined in `class-plugin-name.php`
NetworkStats::get_instance();
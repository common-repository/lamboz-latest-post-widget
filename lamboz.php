<?php
/**
 * Lamboz Latest Post Widget
 *
 * Plugin Name: Lamboz Latest Post Widget
 * Plugin URI:  
 * Description: Lamboz Latest Post Widget is a simple plugin use to display all the latest types of posts in the sidebar using widgets.
 * Version:     0.1
 * Author:      Lamboz Group
 * Author URI:  https://lambozgroup.com
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: lamboz-latest-post-widgets 
 *  *
 Lamboz Latest Post Widget is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Lamboz Latest Post Widget is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 */


define( 'LLPW_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'LLPW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
require_once( LLPW_PLUGIN_PATH . 'includes/class-lamboz-widgets.php' );

add_action( 'wp_enqueue_scripts', 'lamboz_scripts' );
function lamboz_scripts()
{
	wp_enqueue_style( 'lamboz-latest-post-css', LLPW_PLUGIN_URL . '/assets/lamboz.css');
}
?>

<?php
/**
 * The main WP-Models plugin file.
 *
 * @package WP Models
 * @author ActionHook <plugins@actionhook.com>
 * @version 0.1
 * @copyright 2013 ActionHook.com
 */

/*
Plugin Name: WP Models
Plugin URI: http://actionhook.com/wp-models
Description: A plugin to add models and photo shoots. <em>PLEASE NOTE:</em> This plugin requires PHP > 5.3.0 or greater.
Version: 0.1
Author: ActionHook <plugins@actionhook.com>
License: GPL2
 
 Copyright 2013  ActionHook.com  (email : plugins@actionhook.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//check for server requirements
if ( version_compare( phpversion(), '5.3.0', '<' ) ) {
    // php version isn't high enough
    add_action( 'admin_notices', 'wp_models_fail_php_check' );
} else {


	//include our base classes
	require_once( 'base/helper.php' );
	require_once( 'base/controllers/base_controller_plugin.php' );
	require_once( 'base/models/base_model_metabox.php' );
	require_once( 'base/models/base_model_cpt.php' );
	require_once( 'base/models/base_model_settings.php' );
	require_once( 'base/models/base_model_js_object.php' );
	
	require_once( 'app/controllers/plugin_controller.php' );
	
	$WP_Models = new WP_Models( 'wp-models', '0.1', plugin_dir_path( __FILE__ ), __FILE__, plugin_dir_url( __FILE__ ), 'wp-models' );
	
	require_once( 'wp-models-template-tags.php' );
}

/**
 * Add admin notices
 *
 * @package WP Models
 * @internal
 * @since WP Models 0.1
 */
function wp_models_fail_php_check() {
?>
<div class="error">
	<p><?php _e( 'WP-Models requires PHP5.3 or higher. Please contact your host to upgrade your web server.', 'wp-models' ); ?></p>
</div>
<?php
}
?>
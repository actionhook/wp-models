<?php
/**
 * The main WP Models plugin file.
 *
 * @package WP Models
 * @author ActionHook <plugins@actionhook.com>
 * @version 1.0.2
 * @copyright 2013 ActionHook.com
 */

/*
Plugin Name: WP Models
Plugin URI: http://wordpress.org/extend/plugins/wp-models
Description: WP-Models is a plugin designed for modeling agencies, model sites, or individuals that want an elegant solution to showcase themselves.
Version: 1.0.2
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


$plugin_path = plugin_dir_path( __FILE__ );
if( is_dir( $plugin_path . 'base' ) && is_dir( $plugin_path . 'app' ) ):
	//include our base classes
	include_once( 'base/helper.php' );
	include_once( 'base/controllers/base_controller_plugin.php' );
	include_once( 'base/models/base_model.php' );
	include_once( 'base/models/base_model_help_tab.php' );
	include_once( 'base/models/base_model_metabox.php' );
	include_once( 'base/models/base_model_cpt.php' );
	include_once( 'base/models/base_model_settings.php' );
	include_once( 'base/models/base_model_js_object.php' );
	include_once( 'app/controllers/plugin_controller.php' );
	include_once( 'wp-models-template-tags.php' );
	
	if( class_exists( 'WP_Models' ) ):
		$WP_Models = new WP_Models( 'wp-models', '1.0.2', $plugin_path, __FILE__, plugin_dir_url( __FILE__ ), 'wp-models' );
	else:
		add_action( 'admin_notices', 'wpm_missing_files' );
	endif;
else:
	add_action( 'admin_notices', 'wpm_missing_files' );
endif;


/**
 * Add admin notice for failed base check
 *
 * @package WP Models
 * @internal
 * @since WP Models 1.1
 */
function wpm_missing_files() {
?>
<div class="error">
	<p><?php _e( 'This installation of WP Models is missing critical files. Please delete and reinstall the plugin.', 'wp-models' ); ?></p>
</div>
<?php
}
?>
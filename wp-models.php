<?php
/**
 * File Description
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @author authtoken
 * @version
 * @since
 */

/*
Plugin Name: WP Models
Plugin URI: http://wp-models.com
Description: A plugin to add models and photo shoots.
Version: 0.1
Author: Daryl Lozupone
License: GPL2
 
 Copyright 2013  Daryl Lozupone  (email : dlozupone@renegadetechconsulting.com)

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

//include our base classes
require_once( 'base/helper.php' );
require_once( 'base/controllers/base_controller_plugin.php' );
require_once( 'base/models/base_model_metabox.php' );
require_once( 'base/models/base_model_cpt.php' );

require_once( 'app/controllers/plugin_controller.php' );

$WP_Models = new WP_Models( 'wp-models', '0.1', plugin_dir_path( __FILE__ ), __FILE__, plugin_dir_url( __FILE__ ), 'wp-models' );

require_once( 'wp-models-template-tags.php' );
?>
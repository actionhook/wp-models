<?php
/**
 * The WP Models Settings Model
 *
 * @package WP Models\Models
 * @author ActionHook.com <plugins@actionhook.com>
 * @since WP Models 0.1
 */
 /*
 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.
 
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 
 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
 
if ( ! class_exists( 'WP_Models_Settings_Model' ) ):
	/**
	 * The WP Models Settings Model
	 *
	 * @package WP Models\Models
	 * @version 0.1
	 * @since WP Models 0.1
	 */
	class WP_Models_Settings_Model extends Base_Model_Settings
	{
		/**
		 * Initialize the class properties
		 *
		 * @package WP Models\Models
		 * @param string $uri The plugin uri.
		 * @param string $path The plugin app views path.
		 * @param string $txtdomain The plugin text domain.
		 * @todo implement valid values
		 * @since 0.1
		 */
		protected function init( $uri, $path, $txtdomain )
		{
			global $WP_Models;
			
			$this->options = array(
				'wp-models' => array(
					'option_group' => 'wp_models',
					'option_name' => 'wp_models_general',
					'callback' => array( &$this, 'sanitize_input' )
				),
			);
			
			$this->pages = array(
				'wp-models-options' => array(
					'parent_slug'	=> 'options-general.php',
					'page_title'	=> __( 'WP Models Options', $txtdomain ),
					'menu_title'	=> __( 'WP Models', $txtdomain ),
					'capability'	=> 'manage_options',
					'menu_slug'		=> 'wp-models-options',
					'icon_url'		=> null,
					'callback'		=> null,
					'js'			=> array( new Base_Model_JS_Object( 'wp-models-admin-settings', $uri . 'js/wp-models-admin-settings.js', array( 'jquery' ), false, false, 'wpModelsL10n' ) ),
					'help_screen'	=> array(
							new Base_Model_Help_Tab( __( 'Overview', $txtdomain ), 'wp-models-settings-help', null, null, $path . 'help_screen_settings_general.php' )
					),
					'admin_notices' => array(
						get_option( 'wp_models_license_status', true ) == 'valid' ?
							'<div id="wp-models-license-status-message-admin" class="updated"><p>' . __( 'License status: Active', $txtdomain ) . '</p></div>' :
							'<div id="wp-models-license-status-message-admin" class="error"><p>' . sprintf( __( 'License status: %s', $txtdomain ), get_option( 'wp_models_license_status', true ) ) . '</p></div>'
					)
				)
			);
			
			$this->settings_sections = array(
				'wp-models-general' => array(
					'title' 	=> __( 'General Settings', $txtdomain ),
					'callback'	=> null,
					'page' 		=> 'wp-models-options'
				),
				'wp-models-flowplayer' => array(
					'title'		=> __( 'Flowplayer Settings', $txtdomain ),
					'callback'	=> null,
					'page'		=> 'wp-models-options',
					'content'	=> __( 'This section allows you to customize the different Flowplayer options.', $txtdomain )
				)
			);
			
			
			$this->settings_fields = array(
				'license_key' => array(
					'title'				=> __( 'License Key' ),
					'callback'			=> null,
					'page'				=> 'wp-models-options',
					'section'			=> 'wp-models-general',
					'default'			=> '',
					'args'				=> array(
						'type'			=> 'text',
						'id'			=> 'wp-models-license-key',
						'name'			=> 'wp_models_general[license_key]',
						'placeholder'	=> __( 'Enter License Key', $txtdomain ),
						'value'			=> $this->get_settings( 'wp_models_general', 'license_key' ),
						'after'			=> get_option( 'wp_models_license_status', true ) == 'valid' ? 
							$path . 'admin_ajax_license_key_active.php' :
							$path . 'admin_ajax_license_key_inactive.php'
					)
				),
				'use_filter' => array(
					'title'			=> __( 'Use content filter?', $txtdomain ),
					'callback'		=> null,
					'page'			=> 'wp-models-options',
					'section'		=> 'wp-models-general',
					'default'		=> false,
					'args' => array(
						'type'		=> 'checkbox',
						'id'		=> 'wp-models-use-filter',
						'name'		=> 'wp_models_general[use_filter]',
						'value'		=> $this->get_settings( 'wp_models_general', 'use_filter' )
					)
				),
				'flowplayer_style' => array(
					'title'			=> __( 'Flowplayer style', $txtdomain ),
					'callback'		=> null,
					'page'			=> 'wp-models-options',
					'section'		=> 'wp-models-flowplayer',
					'default'		=> 1,
					'args' => array(
						'type'		=> 'select',
						'id'		=> 'wp-models-flowplayer-style',
						'name'		=> 'wp_models_general[flowplayer_style]',
						'value' 	=> $this->get_settings( 'wp_models_general', 'flowplayer_style' ),
						'options'	=> array(
							'Minimalist'	=> 1,
							'Functional'	=> 2,
							'Playful'		=> 3
						)
					)
				)
			);
			
			add_action( 'update_option_wp_models_general', array( &$this, 'update_option_wp_models_general' ),10,2 );
		}
		
		public function sanitize_input( $input )
		{
			if( isset( $input['flowplayer_style'] ) && ! in_array( $input['flowplayer_style'], array( 1,2,3 ) ) )
				$input['flowplayer_style'] = 1;
			
			return $input;
		}
		
		public function add_settings_section( $section )
		{
			$this->settings_sections = array_merge( $this->settings_sections, $section );
		}
		
		public function add_settings_field( $settings )
		{
			$this->settings_fields = array_merge( $this->settings_fields, $settings );
		}
		
		public function activate()
		{
			update_option( $this->options['wp-models']['option_name'], array( 'use_filter' => true, 'flowplayer_style' => 1 ) );
		}
		
		public function update_option_wp_models_general( $old_value, $new_value )
		{
			global $WP_Models;
			
			$args = array(
				'version' => $WP_Models->get_version()
			);
			
			$edd = new EDD_Interface( 'http://actionhook.com', $WP_Models->main_plugin_file(), $args );
			
	 		$license_status = $edd->check_license( 
	 			$new_value['license_key'], 'WP Models Pro' );
	 		//die( print_r($license_status ));
			update_option( 'wp_models_license_status', $license_status );
		}
	}
endif;
?>
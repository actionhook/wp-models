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
			$this->options = array(
				'wp-models' => array(
					'option_group' => 'wp_models',
					'option_name' => 'wp_models_general',
					'callback' => array( &$this, 'sanitize_input' )
				),
			);
			
			$this->pages = array(
				'wp-models-options' => array(
					'page_title'	=> __( 'General Options', $txtdomain ),
					'menu_title'	=> __( 'WP Models', $txtdomain ),
					'capability'	=> 'manage_options',
					'menu_slug'		=> 'wp-models-options',
					'icon_url'		=> null,
					'callback'		=> null,
					'position'		=> '99.3141579',
					'view'			=> 'admin_options.php',
					'css'			=> array( 
						array( 'handle' => 'wp-models-admin-settings', 'src' => $uri . 'css/wp-models-admin-settings.css', 'deps' => null, 'ver' => false, 'media' => 'all' ),
						array( 'handle' => 'twitter-bootstrap', 'src' => '//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.no-icons.min.css', 'deps' => null, 'ver' => false, 'media' => 'all' ),
						array( 'handle' => 'font-awesome', 'src' => '//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.css', 'deps'=> 'twiter-bootstrap', 'ver' => false, 'media' => 'all' )
					),
					'help_screen'	=> array(
							new Base_Model_Help_Tab( __( 'Overview', $txtdomain ), 'wp-models-settings-help', null, null, $path . 'help_screen_settings_general.php' )
					),
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
							1 => 'Minimalist',
							2 => 'Functional',
							3 => 'Playful'
						)
					)
				),
				'storage_location' 	=> array(
					'title'			=> __( 'Storage Location', $txtdomain ),
					'callback'		=> null,
					'page'			=> 'wp-models-options',
					'section'		=> 'wp-models-general',
					'default'		=> 'local',
					'args' => array(
						'type'		=> 'select',
						'id'		=> 'wp-models-storage_location',
						'name'		=> 'wp_models_general[storage_location]',
						'value'		=> $this->get_settings( 'wp_models_general', 'storage_location' ),
						'options'	=> array('local'=>'Local Filesystem')
					)
				)
			);
		}
		
		/**
		 * The options sanitization callback
		 *
		 * @package WP Models\Models
		 * @param array $input The POSTed data.
		 * @return array The sanitized input.
		 * @since 0.1
		 */
		public function sanitize_input( $input )
		{
			if( isset( $input['flowplayer_style'] ) && ! in_array( $input['flowplayer_style'], array( 1,2,3 ) ) )
				$input['flowplayer_style'] = 1;
			
			foreach( $input as $key => $value ):
				$input[$key] = sanitize_text_field( $value );
			endforeach;
				
			return $input;
		}
		
		/**
		 * Run on plugin activation.
		 *
		 * @package WP Models\Models
		 * @since 0.1
		 */
		public function activate()
		{
			$options = get_option( $this->options['wp-models']['option_name'] );
			
			if( ! isset( $options['use_filter'] ) )
				$options['use_filter'] = true; 
			
			if( ! isset( $options['flowplayer_style'] ) )
				$options['flowplayer_style'] = 1;
			
			update_option( $this->options['wp-models']['option_name'], $options );
		}
		
		public function get_storage_location()
		{
			return $this->get_settings( 'wp_models_general', 'storage_location' );
		}
	}
endif;
?>
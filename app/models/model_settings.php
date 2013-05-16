<?php
/**
 * The WP Models Settings Model
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @author authtoken
 * @since WP Models 0.1
 */
if ( ! class_exists( WP_Models_Settings_Model ) ):
	/**
	 * The WP Models Settings Model
	 *
	 * @package pkgtoken
	 * @subpackage subtoken
	 * @version 0.1
	 * @since WP Models 0.1
	 */
	class WP_Models_Settings_Model extends Base_Model_Settings
	{
		protected function init( $txtdomain )
		{
			$this->options = array(
				'wp-models' => array(
					'option_group' => 'wp_models',
					'option_name' => 'wp_models_general',
					'callback' => array( &$this, 'sanitize_input' )
				)
			);
			
			$this->pages = array(
				'wp-models-options' => array(
					'parent_slug'	=> 'options-general.php',
					'page_title'	=> 	__( 'WP Models Options', $txtdomain ),
					'menu_title'	=> __( 'WP Models', $txtdomain ),
					'capability'	=> 'manage_options',
					'menu_slug'		=> 'wp-models-options',
					'icon_url'		=> null,
					'callback'		=> null,
					'js'			=> array()
				)
			);
			
			$this->settings_sections = array(
				'wp-models-general' => array(
					'title' => __( 'General Settings', $txtdomain ),
					'callback' => null,
					'page' => 'wp-models-options',
					'content' => 'The general settings, bro.'
				)
			);
			
			$this->settings_fields = array(
				'use_filter' => array(
					'title' => __( 'Use content filter?', $txtdomain ),
					'callback' => null,
					'page' => 'wp-models-options',
					'section' => 'wp-models-general',
					'args' => array(
						'type' => 'checkbox',
						'id' => 'wp-models-use-filter',
						'name' => 'wp_models_general[use_filter]',
						'value' => $this->get_settings( 'wp_models_general', 'use_filter' )
					)
				)
			);
			
			/*
$this->settings = array(
				'use_amazon' => false,
				'amazon_bucket' => 'wp-models',
				'amazon_accessKeyId' => 'AKIAJ7QBMH6DECYGW7WQ',
				'amazon_secret' => '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',
				'amazon_storage_class' => 	STANDARD,
				'storage_locations' => array( 'local', 'amazons3' )
			);
*/
		}
		
		
		
		public function get_storage_settings()
		{
			if( $this->settings['use_amazon'] ):
 				$settings = array(
	 				'location' => 'amazonS3',
	 				'access_key' => $this->settings['amazon_accessKeyId'],
	 				'secret_key' => $this->settings['amazon_secret'],
	 				'bucket' =>$this->settings['amazon_bucket']
 				);
	 		else:
	 			$settings = array(
		 			'location' => 'local',
		 			'access_key' => null,
		 			'secret_key' => null,
		 			'bucket' => null
	 			);
	 		endif;
	 		
	 		return $settings;
		}
	}
endif;
?>
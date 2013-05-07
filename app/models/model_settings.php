<?php
/**
 * The WP Models Settings Model
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @author authtoken
 * @version
 * @since
 */

if ( ! class_exists( WP_Models_Settings_Model ) ):
	class WP_Models_Settings_Model
	{
		/**
		 * The plugin settings
		 *
		 * @package pkgtoken
		 * @subpackage subtoken
		 * @var array
		 * @since 
		 */
		protected $settings;
		
		public function __construct()
		{
			$this->settings = array(
				'use_amazon' => true,
				'amazon_bucket' => 'wp-models',
				'amazon_accessKeyId' => 'AKIAJ7QBMH6DECYGW7WQ',
				'amazon_secret' => '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',
				'amazon_storage_class' => 	STANDARD,
				'storage_locations' => array( 'local', 'amazons3' )
			);
		}
		
		public function get_settings()
		{
			return $this->settings;
		}
	}
endif;
?>
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
				'use_amazon' => false,
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
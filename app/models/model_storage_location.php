<?php
/**
 * The storage location model.
 *
 * @package WP Models\Models
 * @author authtoken
 * @copyright 2013
 * @version
 * @since
 */

if( ! class_exists( 'WP_Models_Model_Storage_Location' ) ):
	/**
	 * The storage location model
	 *
	 * @package WP Models\Models
	 * @version 0.1
	 * @since WP Models 1.1
	 */
	class WP_Models_Model_Storage_Location
	{
		/**
		 * The storage location display name. Used in settings fields, etc.
		 *
		 * @package WP Models\Models
		 * @var string
		 * @since 0.1
		 */
		private $_display_name;
		
		/**
		 * The storage location access key.
		 * 
		 * @package WP Models\Models
		 * @var string
		 * @since 0.1
		 */
		private $_access_key;
		
		/**
		 * The storage location secret key.
		 * 
		 * @package WP Models\Models
		 * @var string
		 * @since 0.1
		 */
		private $_secret_key;
		
		/**
		 * The storage location path.
		 *
		 * This is the path within the storage location to the files. An an example, the Amazon S3 bucket,
		 * in which to store files.
		 * 
		 * @package WP Models\Models
		 * @var string
		 * @since 0.1
		 */
		private $_storage_bucket;
		
		private $_storage_bucket_uri;
		
		/**
		 * The function to be called to retrieve items from this location.
		 *
		 * @package WP Models\Models
		 * @var mixed
		 * @since 0.1
		 */
		private $_get_callback;
		
		/**
		 * The function to be called to send media to this location.
		 *
		 * @package WP Models\Models
		 * @var mixed
		 * @since 0.1
		 */
		private $_post_callback;
		
		/**
		 * The function to be called to delete media from this location
		 *
		 * @package WP Models\Models
		 * @var mixed
		 * @since 0.1
		 */
		private $_delete_callback;
		
		/**
		 * The class constructor.
		 *
		 * @package WP Models\Models
		 * @param string $access_key
		 * @param string $secret_key
		 * @param string $storage_bucket
		 * @param mixed $get_callback
		 * @param mixed $post_callback
		 * @param mixed $delete_callback
		 * @since 0.1
		 */
		public function __construct( $display_name, $access_key, $secret_key, $storage_bucket, $storage_bucket_uri, $get_callback, $post_callback, $delete_callback )
		{
			$this->_display_name		= $display_name;
			$this->_access_key			= $access_key;
			$this->_secret_key			= $secret_key;
			$this->_storage_bucket		= $storage_bucket;
			$this->_storage_bucket_uri	= $storage_bucket_uri;
			$this->_get_callback		= $get_callback;
			$this->_post_callback		= $post_callback;
			$this->_delete_callback		= $delete_callback;
		}
		
		/**
		 * Get the display name
		 *
		 * @package WP Models\Models
		 * @return string $_display_name
		 * @since 0.1
		 */
		public function get_display_name()
		{
			return $this->_display_name;
		}
		
		/**
		 * Get the access key.
		 *
		 * @package WP Models\Models
		 * @since 0.1
		 */
		public function get_access_key() {
			return $this->_access_key;
		}
		
		/**
		 * Get the secret key.
		 *
		 * @package WP Models\Models
		 * @since 0.1
		 */
		public function get_secret_key() {
			return $this->_secret_key;
		}
		
		/**
		 * Get the storage bucket.
		 *
		 * @package WP Models\Models
		 * @since 0.1
		 */
		public function get_storage_bucket() {
			return $this->_storage_bucket;
		}
		
		/**
		 * Get the storage bucket uri.
		 *
		 * @package WP Models\Models
		 * @since 0.1
		 */
		public function get_storage_bucket_uri() {
			return $this->_storage_bucket_uri;
		}
		
		/**
		 * Get the get callback.
		 *
		 * @package WP Models\Models
		 * @since 0.1
		 */
		public function get_get_callback() {
			return $this->_get_callback;
		}
		
		/**
		 * Get the post callback.
		 *
		 * @package WP Models\Models
		 * @since 0.1
		 */
		public function get_post_callback() {
			return $this->_post_callback;
		}
		
		/**
		 * Get the delete callback.
		 *
		 * @package WP Models\Models
		 * @since 0.1
		 */
		public function get_delete_callback() {
			return $this->_delete_callback;
		}
	}
endif;
?>
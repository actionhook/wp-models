<?php
/**
 * The storage location model.
 *
 * @package pkgtoken
 * @author authtoken
 * @copyright 2013
 * @version
 * @since
 */

if( ! class_exists( 'WP_Models_Model_Storage_Location' ) ):
	/**
	 * The storage location model
	 *
	 * @package pkgtoken
	 * @version 0.1
	 * @since WP Models 1.1
	 */
	class WP_Models_Model_Storage_Location
	{
		private $_access_key;
		private $_secret_key;
		private $_storage_bucket;
		
		/**
		 * The function to be called to retrieve items from this location.
		 *
		 * @package pkgtoken
		 * @var mixed
		 * @since 0.1
		 */
		private $_get_callback;
		
		/**
		 * The function to be called to send media to this location.
		 *
		 * @package pkgtoken
		 * @var mixed
		 * @since 0.1
		 */
		private $_post_callback;
		
		/**
		 * The function to be called to delete media from this location
		 *
		 * @package pkgtoken
		 * @var mixed
		 * @since 0.1
		 */
		private $_delete_callback;
		
		/**
		 * The class constructor.
		 *
		 * @package pkgtoken
		 * @param string $access_key
		 * @param string $secret_key
		 * @param string $storage_bucket
		 * @param mixed $get_callback
		 * @param mixed $post_callback
		 * @param mixed $delete_callback
		 * @since 0.1
		 */
		public function __construct( $access_key, $secret_key, $storage_bucket, $get_callback, $post_callback, $delete_callback )
		{
			$this->_access_key = $access_key;
			$this->_secret_key = $secret_key;
			$this->_storage_bucket = $storage_bucket;
			$this->_get_callback = $get_callback;
			$this->_post_callback = $post_callback;
			$this->_delete_callback = $delete_callback;
		}
		
		/**
		 * Get the access key.
		 *
		 * @package pkgtoken
		 * @since 0.1
		 */
		public function get_access_key() {
			return $this->_access_key;
		}
		
		/**
		 * Get the secret key.
		 *
		 * @package pkgtoken
		 * @since 0.1
		 */
		public function get_secret_key() {
			return $this->_secret_key;
		}
		
		/**
		 * Get the storage bucket.
		 *
		 * @package pkgtoken
		 * @since 0.1
		 */
		public function get_storage_bucket() {
			return $this->_storage_bucket;
		}
		
		/**
		 * Get the get callback.
		 *
		 * @package pkgtoken
		 * @since 0.1
		 */
		public function get_get_callback() {
			return $this->_get_callback;
		}
		
		/**
		 * Get the post callback.
		 *
		 * @package pkgtoken
		 * @since 0.1
		 */
		public function get_post_callback() {
			return $this->_post_callback;
		}
		
		/**
		 * Get the delete callback.
		 *
		 * @package pkgtoken
		 * @since 0.1
		 */
		public function get_delete_callback() {
			return $this->_delete_callback;
		}
	}
endif;
?>
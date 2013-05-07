<?php
/**
 * Main plugin controller.
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @author authtoken
 * @since 0.1
 */

if ( ! class_exists( WP_Models ) ):
	/**
	 * The main WP_Models controllers class
	 *
	 * @package pkgtoken
	 * @subpackage subtoken
	 * @version 0.1
	 * @since 0.1
	 */
	 class WP_Models extends Base_Plugin_Controller
	 {
	 	public function init()
	 	{
	 		//get the plugin settings
	 		require_once( $this->models_path . '/model_settings.php' );
	 		$settings = new WP_Models_Settings_Model();
	 		$this->settings = $settings->get_settings();
			
	 		//set up the plugin custom post types
	 		require_once( $this->models_path . '/model_cpt_models.php' );
	 		require_once( $this->models_path . '/model_cpt_shoots.php' );
	 		$this->cpts = array(
	 			'models' => new WP_Models_CPT_Models_Model( $this->txtdomain ),
	 			'shoots' => new WP_Models_CPT_Shoots_Model( $this->txtdomain )
	 		);
	 		
	 		//setup our nonce name and action
	 		$this->nonce_name = '_wp_models_nonce';
	 		$this->nonce_action = '5tyhjDR%6%$%^&*IuhbnmknbGTRFGHJN';
	 			
	 		add_filter( 'filter_metabox_callback_args', array( &$this, 'setup_metabox_args' ), 10, 2 );
	 		
	 		//add our ajax callbacks
	 		add_action( 'wp_ajax_wp_models_ajax_media_upload', array( &$this, 'ajax_media_upload' ) );
	 		add_action( 'wp_ajax_wp_models_shoot_media', array( &$this, 'ajax_get_shoot_media' ) );
	 		add_action( 'wp_ajax_wp_models_delete_shoot_pic', array( &$this, 'ajax_delete_shoot_media' ) );
	 		add_action( 'wp_ajax_wp_models_delete_shoot_vid', array( &$this, 'ajax_delete_shoot_media' ) );
	 		
	 		//filter js l10n as neccessary
	 		add_filter( 'filter_script_localizations', array( &$this, 'filter_shoot_cpt_admin_js' ), 10, 2 );
	 		
	 		// Support the file format webm mimetype
			add_filter( 'upload_mimes', array( &$this, 'custom_mimes' ) );
	 	}
	 	
	 	/**
	 	 * Add additional metabox callback args as necessary for views.
	 	 *
	 	 * @package pkgtoken
	 	 * @subpackage subtoken
	 	 * @param object $post The WP post object.
	 	 * @param array $metabox The WP metabox array.
	 	 * @return array $metabox The modified WP metabox array.
	 	 * @since 
	 	 */
	 	public function setup_metabox_args( $post, $metabox )
	 	{	
	 		switch( $metabox['id'] )
	 		{
	 			case 'wp-models-shoot-models':
	 				$models = $this->cpts['models']->get_models();
	 				foreach( $models as $model ):
	 					$metabox['args']['models'][$model->ID] = $model->post_title;
	 				endforeach;
	 				break;
	 		}
	 		
	 		return $metabox;
	 	}
	 	
	 	public function ajax_media_upload()
	 	{
	 		//check for security
	 		if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( $this->nonce_name, 'nonce' ) )
	 			die();
	 		
	 		$this->cpts['shoots']->save_shoot_media( $_POST, $_FILES, true );
	 		print_r( $_POST);
	 		die();
	 	}
	 	
	 	public function ajax_get_shoot_media()
	 	{
	 		//configure the get_shoot_media parameters and include required files based on storage location
	 		/**
	 		 * @todo change settings to use storage_location
	 		 */
	 		if( $this->settings['use_amazon'] ):
	 			require_once( trailingslashit( $this->path) . 'lib/s3.php' );
 				$location = 'amazonS3';
 				$access_key = $this->settings['amazon_accessKeyId'];
 				$secret_key = $this->settings['amazon_secret'];
 				$bucket = $this->settings['amazon_bucket'];
	 		else:
	 			$location = 'local';
	 			$access_key = null;
	 			$secret_key = null;
	 			$bucket = null;
	 		endif;
			
			//get the shoot media
			$shoot_media = $this->cpts['shoots']->get_shoot_media( $_POST['post'], $_POST['type'], $location, $access_key, $secret_key, $bucket );
			
			//if we have an array of photos, include the view
	 		if ( is_array( $shoot_media ) ):
		 		ob_start();
		 		require_once( trailingslashit( $this->views_path ) . 'admin_ajax_shoot_'. $_POST['type'] . '_html.php' );
		 		$html = ob_get_clean();
	 		endif;
	 		
	 		if ( $html == '' ):
	 			if ( $_POST['type'] == 'pics' ):
	 				$html = __( 'There are no pictures associated with this shoot.', $this->txtdomain );
	 			else:
	 				$html = __( 'There are no videos associated with this shoot.', $this->txtdomain );
	 			endif;
	 		endif;
	 		
	 		die( $html );
	 	}
	 	
	 	public function ajax_delete_shoot_media()
	 	{
	 		//check for security
	 		if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( $this->nonce_name, 'nonce' ) )
	 			die( 'Security check failed' );
	 				
	 		print_r( $_POST );
	 		if ( $_POST['action'] == 'wp_models_delete_shoot_pic' ):
	 			$type = 'pics';
	 		elseif ( $_POST['action'] == 'wp_models_delete_shoot_vid' ):
	 			$type = 'vids';
	 		endif;
	 			
	 		$result = $this->cpts['shoots']->delete_shoot_media( $_POST['post_id'], $_POST['media'], $type );
	 		die( $result );
	 	}
	 	
	 	public function custom_mimes( $mimes )
	 	{
			$mimes['webm'] = 'video/webm';
			return $mimes;
		}
		
		/**
		 * Filter the arguments for the wp-models-cpt-shoots-admin js
		 *
		 * @package pkgtoken
		 * @subpackage subtoken
		 * @since 
		 */
		public function filter_shoot_cpt_admin_js( $handle, $args )
		{
			if ( $handle == 'wp-models-cpt-shoots-admin' && $this->settings['use_amazon'] ):
				require_once( trailingslashit( $this->path ) . 'lib/class-s3.php' );
				
				//get the Amazon S3 settings
				$bucket = $this->settings['amazon_bucket'];
				$accessKeyId = $this->settings['amazon_accessKeyId'];
				$secret = $this->settings['amazon_secret'];
				//This is for setting either Standard or Reduced Redundancy Storage. Currently, STANDARD is always selected even when
				//the value of this is REDUCED_REDUNDANCY
				$storage_class = $this->settings['amazon_storage_class'];
				
				$S3 = new S3_Helper_Functions( $bucket, $accessKeyId, $secret );
				
				//set the Amazon S3 upload policy-- see http://docs.aws.amazon.com/AmazonS3/2006-03-01/dev/HTTPPOSTForms.html
				$policy = array(
					// ISO 8601 - date('c'); generates uncompatible date, so better do it manually
					'expiration' => date('Y-m-d\TH:i:s.000\Z', strtotime('+1 day')),  
					'conditions' => array(
						array('bucket' => $bucket),
						array('acl' => 'public-read'),
						array('starts-with', '$key', ''),
						array('starts-with', '$Content-Type', ''),
						// "Some versions of the Adobe Flash Player do not properly handle HTTP responses that have an empty body. 
						// To configure POST to return a response that does not have an empty body, set success_action_status to 201.
						// When set, Amazon S3 returns an XML document with a 201 status code." 
						// http://docs.amazonwebservices.com/AmazonS3/latest/dev/HTTPPOSTFlash.html
						array('success_action_status' => '201'),
						// Plupload internally adds name field, so we need to mention it here
						array('starts-with', '$name', ''), 	
						// One more field to take into account: Filename - gets silently sent by FileReference.upload() in Flash
						// http://docs.amazonwebservices.com/AmazonS3/latest/dev/HTTPPOSTFlash.html
						array('starts-with', '$Filename', ''), 
					)
				);
				
				//encode the policy
				$policy = $S3->encode_policy( $policy );
				//sign the policy
				$signature = $S3->sign_policy( $policy );
				
				//set the args to be passed to the js
				$args['storage'] = 'S3';
				$args['storage_class'] = $storage_class;
				$args['url'] = sprintf( 'https://%s.s3.amazonaws.com:443/', $bucket );
				$args['bucket'] = $bucket;
				$args['accessKeyId'] = $accessKeyId;
	 			$args['policy'] = $policy;
	 			$args['signature'] = $signature;
			endif;
			
			//return the modified args
			return $args;
		}
		
	 	public function delete()
	 	{
	 		//delete the shoot models table
			//remove all meta from postmeta
			//create the wp_models upload directory and add index.php
	 	}
	 }
endif;
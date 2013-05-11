<?php
/**
 * Main plugin controller.
 *
 * @package WP Models
 * @author authtoken
 */

if ( ! class_exists( WP_Models ) ):
	/**
	 * The main WP_Models controller class
	 *
	 * @package WP Models
	 *
	 * @version 0.1
	 * @since WP Models 0.1
	 * @todo add activate function that creates db table
	 */
	 class WP_Models extends Base_Plugin_Controller
	 {
	 	/**
	 	 * Initialize the plugin
	 	 *
	 	 * @package WP Models
	 	 *
	 	 * @since 0.1
	 	 */
	 	public function init()
	 	{
	 		//require necessary files
	 		require_once( $this->models_path . '/model_cpt_models.php' );
	 		require_once( $this->models_path . '/model_cpt_shoots.php' );
	 		
	 		define( '_WP_MODELS_CPT_MODELS_SLUG', WP_Models_CPT_Models_Model::get_slug() );
	 		define( '_WP_MODELS_CPT_SHOOTS_SLUG', WP_Models_CPT_Shoots_Model::get_slug() );
	 			
	 		//get the plugin settings
	 		require_once( $this->models_path . '/model_settings.php' );
	 		$this->settings = new WP_Models_Settings_Model();
			
	 		//set up the plugin custom post types
	 		$this->cpts = array(
	 			_WP_MODELS_CPT_MODELS_SLUG => new WP_Models_CPT_Models_Model( $this->uri, $this->txtdomain ),
	 			_WP_MODELS_CPT_SHOOTS_SLUG => new WP_Models_CPT_Shoots_Model( $this->uri, $this->txtdomain )
	 		);
	 		
	 		//print_r($this->cpts);
	 		
	 		//setup our nonce name and action
	 		$this->nonce_name = '_wp_models_nonce';
	 		$this->nonce_action = '5tyhjDR%6%$%^&*IuhbnmknbGTRFGHJN';
	 		
	 		//filter metabox callback args as necessary
	 		add_filter( 'filter_metabox_callback_args', array( &$this, 'setup_metabox_args' ), 10, 2 );
	 		
	 		//add our ajax callbacks
	 		add_action( 'wp_ajax_wp_models_media_upload', 	array( &$this, 'ajax_media_upload' ) );
	 		add_action( 'wp_ajax_wp_models_get_media', 		array( &$this, 'ajax_get_media' ) );
	 		add_action( 'wp_ajax_wp_models_delete_shoot_pic', 	array( &$this, 'ajax_delete_media' ) );
	 		add_action( 'wp_ajax_wp_models_delete_shoot_vid', 	array( &$this, 'ajax_delete_media' ) );
	 		
	 		//filter js l10n as necessary
	 		add_filter( 'filter_admin_scripts_l10n_args-wp-models-cpt-shoots-admin',	array( &$this, 'filter_shoot_cpt_admin_js' ), 10 );
	 		
	 		// Support the file format webm mimetype
			add_filter( 'upload_mimes', array( &$this, 'custom_mimes' ) );
			
			//add our content filters
			add_filter( 'the_content',	array( &$this, 'render_model_single' ) );
	 	}
	 	
	 	/**
	 	 * Add additional metabox callback args as necessary for views.
	 	 *
	 	 * @package WP Models
	 	 *
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
	 				$models = $this->cpts[_WP_MODELS_CPT_MODELS_SLUG]->get_models();
	 				foreach( $models as $model ):
	 					$metabox['args']['models'][$model->ID] = $model->post_title;
	 				endforeach;
	 				break;
	 		}
	 		
	 		return $metabox;
	 	}
	 	
	 	
	 	/**
	 	 * The ajax media upload callback.
	 	 *
	 	 * @package WP Models
	 	 *
	 	 * @since 0.1
	 	 */
	 	public function ajax_media_upload()
	 	{
	 		//check for security
	 		if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( $this->nonce_name, 'nonce' ) )
	 			die( 'NONCE CHECK FAILED' );
		
	 		$result = $this->cpts[$_POST['post_type']]->save_media( $_POST, $_FILES, true );
	 		print_r( $_POST);
	 		die( $result );
	 	}
	 	
	 	/**
	 	 * The ajax media render callback
	 	 *
	 	 * @package WP Models
	 	 *
	 	 * @since 0.1
	 	 */
	 	public function ajax_get_media()
	 	{
	 	
	 		//print_r( $_POST );
	 		//configure the get_shoot_media parameters and include required files based on storage location
	 		/**
	 		 * @todo change settings to use storage_location
	 		 */
	 		 
	 		$settings = $this->settings->get_storage_settings();
	 		
	 		if( $settings['location'] == 'amazonS3' )
	 			require_once( trailingslashit( $this->path) . 'lib/s3.php' );
			
			//get the post media
			$post_media = $this->cpts[$_POST['post_type']]->get_media( 
				$_POST['post'],
				$_POST['media_type'],
				$settings['location'],
				$settings['access_key'],
				$settings['secret_key'],
				$settings['$bucket']
			);
			
			//set variables for the template
			$uri = $this->uri;
			
			//if we have an array of media items, include the appropriate view
	 		if ( is_array( $post_media ) ):
		 		ob_start();
		 		require_once( trailingslashit( $this->views_path ) . 'admin_ajax_'. $_POST['media_type'] . '_html.php' );
		 		$html = ob_get_clean();
	 		endif;
	 		
	 		if ( $html == '' ):
	 			if ( $_POST['media_type'] == 'pics' ):
	 				$html = __( 'There are no pictures associated with this .', $this->txtdomain );
	 			else:
	 				$html = __( 'There are no videos associated with this .', $this->txtdomain );
	 			endif;
	 		endif;
	 		
	 		die( $html );
	 	}
	 	
	 	/**
	 	 * The callback for the ajax delete media handler.
	 	 *
	 	 * @package WP Models
	 	 *
	 	 * @since 0.1
	 	 */
	 	public function ajax_delete_media()
	 	{
	 		//check for security
	 		if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( $this->nonce_name, 'nonce' ) )
	 			die( 'Security check failed' );
	 				
	 		//print_r( $_POST );
	 		if ( $_POST['action'] == 'wp_models_delete_shoot_pic' ):
	 			$type = 'pics';
	 		elseif ( $_POST['action'] == 'wp_models_delete_shoot_vid' ):
	 			$type = 'vids';
	 		endif;
	 		
	 		$settings = $this->settings->get_storage_settings();
	 		
	 		$result = $this->cpts[$_POST['post_type']]->delete_media( $_POST['post_id'], $_POST['media'], $type, $settings['location'] );
	 		die( $result );
	 	}
	 	
	 	/**
	 	 * Add mime types to WP
	 	 *
	 	 * @package WP Models
	 	 *
	 	 * @param array $mimes The exising mimes object.
	 	 * @since 0.1
	 	 */
	 	public function custom_mimes( $mimes )
	 	{
			$mimes['webm'] = 'video/webm';
			return $mimes;
		}
		
		/**
		 * Filter the arguments for the wp-models-cpt-shoots-admin js
		 *
		 * @package WP Models
		 *
		 * @param string $handle The script handle registered with wp_enquque_script.
		 * @param array $args Contains key/value pairs of script localizations.
		 * @since 0.1
		 */
		public function filter_shoot_cpt_admin_js( $args )
		{
			$settings = $this->settings->get_storage_settings();
			
			if ( $settings['location'] == 'amazonS3' ):
				require_once( trailingslashit( $this->path ) . 'lib/class-s3.php' );
				
				//get the Amazon S3 settings
				$bucket = $settings['bucket'];
				$accessKeyId = $settings['accessKeyId'];
				$secret = $settings['secret'];
				//This is for setting either Standard or Reduced Redundancy Storage. Currently, STANDARD is always selected even when
				//the value of this is REDUCED_REDUNDANCY
				$storage_class = $settings['storage_class'];
				
				$S3 = new S3_Helper_Functions( $bucket, $accessKeyId, $secret );
				
				//set the Amazon S3 upload policy-- see http://docs.aws.amazon.com/AmazonS3/2006-03-01/dev/HTTPPOSTForms.html
				$policy = array(
					// ISO 8601 - date('c'); generates uncompatible date, so better do it manually
					'expiration' => date('Y-m-d\TH:i:s.000\Z', strtotime('+1 day')),  
					'conditions' => array(
						array('bucket' => $bucket),
						array('acl' => 'private'),
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
			
			//add the nonce for media uploads/deletes
			$args['nonce'] = wp_create_nonce( $this->nonce_name );
			
			//return the modified args
			return $args;
		}
		
		/**
		 * Render the single model page view.
		 *
		 * This view is rendered using the WP filter the_content. This is done to ensure compatibility with all themes and membership plugins.
		 *
		 * @package pkgtoken
		 * @subpackage subtoken
		 * @param string $content The WP post content.
		 * @since 0.1
		 */
		public function render_model_single( $content )
		{
			global $post;
			
			if( is_single() && $post->post_type == $this->cpts[$post->post_type]->get_slug() ):
				$settings = $this->settings->get_storage_settings();
	 		
		 		if( $settings['location'] == 'amazonS3' )
		 			require_once( trailingslashit( $this->path) . 'lib/s3.php' );
				
				//get the post media
				$post_pics = $this->cpts[$post->post_type]->get_media( 
					$post->ID,
					'pics',
					$settings['location'],
					$settings['access_key'],
					$settings['secret_key'],
					$settings['$bucket']
				);
				
				$post_vids = $this->cpts[$post->post_type]->get_media( 
					$post->ID,
					'vids',
					$settings['location'],
					$settings['access_key'],
					$settings['secret_key'],
					$settings['$bucket']
				);
				
				//get the model details
				$meta = get_post_meta( $post->ID, $this->cpts[_WP_MODELS_CPT_MODELS_SLUG]->get_metakey(), true );
				
				//create the info string from meta keys/values
				if( is_array( $meta ) ):
					foreach( $meta as $key => $item ):
						$model_info .= ucfirst( str_replace('model_', '', $key ) ) . ': ' . $item . ' ';			
					endforeach;
				endif;
				
				//allow the end user to filter the info line
				$model_info = apply_filters( 'wp_models_filter_model_info', $model_info, $post->ID, $meta );
				
				//include the view
				ob_start();
				require_once( trailingslashit( $this->views_path ) . 'single_model.php' );
				$content = ob_get_clean();
			endif;
			
			return $content;
		}
		
		
		public static function activate()
		{
		}
		
		/**
		 * The plugin deletion callback
		 *
		 * @package WP Models
		 *
		 * @since 0.1
		 * @todo implement this function
		 */
	 	public function delete()
	 	{
	 		//delete the shoot models table
			//remove all meta from postmeta
			//create the wp_models upload directory and add index.php
	 	}
	 }
endif;
<?php
/**
 * The Models custom post type model.
 *
 * @package WP Models
 * @subpackage Custom Post Types
 * @author authtoken
 * @since 0.1
 */

if ( ! class_exists( WP_Models_CPT_Models_Model ) ):
	/**
	 * The Models model class.
	 *
	 * @package WP Models
	 * @subpackage Custom Post Types
	 * @version 0.1
	 * @since WP Models 0.1
	 */
	 class WP_Models_CPT_Models_Model extends Base_CPT_Model
	 {
	 	protected static $slug = 'wp-models-model';
	 	protected static $metakey = '_wp-models-model';
	 	/**
		 * The media upload directory path
		 *
		 * @package WP Models
		 * @subpackage Custom Post Types
		 * @var string
		 * @since 0.1
		 */
		protected $media_upload_dir;
		
		/**
		 * The media upload directory uri
		 *
		 * @package WP Models
		 * @subpackage Custom Post Types
		 * @var string
		 * @since 0.1
		 */
		protected $media_upload_uri;
		
	 	/**
	 	 * The class constructor.
	 	 *
	 	 * @package WP Models
	 	 * @subpackage Custom Post Types
	 	 * @param string $txtdomain The plugin textdomain. used to localize the arguments.
	 	 * @since 0.1
	 	 */
	 	public function __construct( $uri, $txtdomain )
	 	{
	 		$uploads_dir = wp_upload_dir();
	 		
	 		//self::$slug = 'wp-models-model';
	 		/* $this->noncename = 'wp-models-model'; */
	 		$this->init_args( $uri, $txtdomain );
	 		$this->init_shortcodes();
	 		$this->media_upload_dir = trailingslashit( $uploads_dir['basedir'] ) . self::$slug;
	 		$this->media_upload_uri = trailingslashit( content_url() ) . 'uploads/' . self::$slug;
	 	}
	 	
	 	/**
		 * initialize the CPT arguments for register_post_type
		 *
		 * @package WP Models
		 * @subpackage Custom Post Types
		 * @param string $txtdomain
		 * @see http://codex.wordpress.org/Function_Reference/register_post_type
		 * @since 0.1
		 */
		protected function init_args( $uri, $txtdomain = '' )
		{
			if ( $txtdomain == '' and isset( $this->txtdomain ) )
				$txtdomain = $this->txtdomain;
				
			$labels = array(
				'name'                => _x( 'Models', 'Post Type General Name', $txtdomain ),
				'singular_name'       => _x( 'Model', 'Post Type Singular Name', $txtdomain ),
				'menu_name'           => __( 'Models', $txtdomain ),
				'parent_item_colon'   => __( 'Parent Model', $txtdomain ),
				'all_items'           => __( 'All Models', $txtdomain ),
				'view_item'           => __( 'View Model', $txtdomain ),
				'add_new_item'        => __( 'Add New Model', $txtdomain ),
				'add_new'             => __( 'New Model', $txtdomain ),
				'edit_item'           => __( 'Edit Model', $txtdomain ),
				'update_item'         => __( 'Update Model', $txtdomain ),
				'search_items'        => __( 'Search models', $txtdomain ),
				'not_found'           => __( 'No models found', $txtdomain ),
				'not_found_in_trash'  => __( 'No models found in Trash', $txtdomain ),
			);

			$this->args = array(
				'description'         	=> __( 'Models', $txtdomain ),
				'labels'              	=> $labels,
				'supports'            	=> array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
				'taxonomies'          	=> null,
				'hierarchical'        	=> false,
				'public'              	=> true,
				'show_ui'             	=> true,
				'show_in_menu'        	=> true,
				'show_in_nav_menus'   	=> true,
				'show_in_admin_bar'   	=> true,
				'menu_icon'           	=> trailingslashit( $uri ) . 'images/model-icon.png',
				'can_export'          	=> true,
				'has_archive'         	=> true,
				'exclude_from_search' 	=> false,
				'publicly_queryable'  	=> true,
				'rewrite' 			  	=> array( 'slug' => 'models' )
			);
		}
		
		/**
		 * Description
		 *
		 * @package pkgtoken
		 * @subpackage subtoken
		 * @since 
		 */
		public function init_shortcodes()
		{
			$this->shortcodes = array(
				'wpmodelsmodelinfo' => array( &$this, 'wp_models_model_info' ),
				'wpmodelsmodelage' => array( &$this, 'wp_models_model_age' ),
				'wpmodelsmodelheight' => array( &$this, 'wp_models_model_height' ),
				'wpmodelsmodelweight' => array( &$this, 'wp_models_model_weight' ),
				'wpmodelsmodelbust' => array( &$this, 'wp_models_model_bust' ),
				'wpmodelsmodelwaist' => array( &$this, 'wp_models_model_waist' ),
				'wpmodelsmodelhips' => array( &$this, 'wp_models_model_hips' )
			);
		}
		/**
		 * Initialize the admin_scripts property.
		 *
		 * @package WP Models
		 * @subpackage Custom Post Types
		 * @param object $post The WP post object.
		 * @param string $txtdomain The plugin text domain.
		 * @param string $uri The plugin js uri ( e.g. http://example.com/wp-content/plugins/myplugin/js )
		 * @since 0.1
		 */
		public function init_admin_scripts( $post, $txtdomain, $uri )
		{
			$this->admin_scripts = array(
	 			array(
	 				'handle' => 'jquery-plupload-queue',
	 				'src' => $uri . 'plupload/jquery.plupload.queue/jquery.plupload.queue.js',
	 				'deps' => array( 'plupload-all' ),
	 				'ver' => '1.5.7',
	 				'in_footer' => false
	 			),
	 			array(
		 			'handle' => 'wp-models-admin-cpt',
		 			'src' => $uri . 'wp-models-admin-cpt.js',
		 			'deps' => array( 'jquery-plupload-queue' ),
		 			'ver' => false,
		 			'in_footer' => true
	 			),
	 			array(
	 				'handle' => 'flowplayer',
	 				'src' => $uri . 'flowplayer/flowplayer.js',
	 				'deps' => array( 'jquery' ),
	 				'ver' => '5.4.17',
	 				'in_footer' => false
	 			),
	 			array(
					'handle' => 'colorbox',
					'src' => trailingslashit( $uri ) . 'colorbox/jquery.colorbox.js',
					'deps' => array( 'jquery' ),
					'ver' => '1.4.15',
					'in_footer' => false
				)
	 		);
	 		$this->admin_scripts_l10n = array(
	 			array(
	 				'script' => 'wp-models-admin-cpt',
	 				'var' => 'wpModelsL10n',
	 				'args' => array(
	 					'storage' => 'local',	//deafult to local. Set later by plugin controller
	 					'url' => admin_url( 'admin-ajax.php' ),
	 					'post_id' => $post->ID,
	 					'post_type' => self::$slug
	 				)
	 			)
	 		);
		}
		
		/**
		 * initialize the admin_css property
		 *
		 * @package pkgtoken
		 * @subpackage subtoken
		 * @param string $uri The uri to the plugin css directory (e.g. http://example.com/wp-content/plugins/myplugin/css ).
		 * @since 0.1
		 */
		public function init_admin_css( $uri )
		{
			$uri = trailingslashit( $uri );
			
			$this->admin_css = array(
	 			array(
	 				'handle' => 'jquery-plupload-queue',
	 				'src' => $uri .  'plupload/jquery.plupload.queue/css/jquery.plupload.queue.css',
	 				'deps' => false,
	 				'ver' => false,
	 				'media' => 'all'
	 			),
	 			array(
	 				'handle' => 'wp-models-admin',
	 				'src' => $uri .  'wp-models-admin.css',
	 				'deps' => false,
	 				'ver' => false,
	 				'media' => 'all'
	 			),
	 			array(
	 				'handle' => 'flowplayer',
	 				'src' => $uri .  'flowplayer/functional.css',
	 				'deps' => false,
	 				'ver' => false,
	 				'media' => 'all'
	 			),
	 			array(
	 				'handle' => 'colorbox',
	 				'src' => trailingslashit( $uri ) .  'colorbox/colorbox.css',
	 				'deps' => false,
	 				'ver' => false,
	 				'media' => 'all'
	 			)
	 		);
		}
		
		public function init_css( $uri )
		{
			$this->css = array(
	 			array(
	 				'handle' => 'wp-models',
	 				'src' => trailingslashit( $uri ) .  'wp-models.css',
	 				'deps' => false,
	 				'ver' => false,
	 				'media' => 'all'
	 			),
	 			array(
	 				'handle' => 'colorbox',
	 				'src' => trailingslashit( $uri ) .  'colorbox/colorbox.css',
	 				'deps' => false,
	 				'ver' => false,
	 				'media' => 'all'
	 			),
	 			array(
	 				'handle' => 'flowplayer',
	 				'src' => trailingslashit( $uri ) .  'flowplayer/minimalist.css',
	 				'deps' => false,
	 				'ver' => false,
	 				'media' => 'all'
	 			)
	 		);
		}
		
		public function init_scripts( $uri )
		{
			$this->scripts = array(
				array(
					'handle' => 'colorbox',
					'src' => trailingslashit( $uri ) . 'colorbox/jquery.colorbox.js',
					'deps' => array( 'jquery' ),
					'ver' => '1.4.15',
					'in_footer' => false
				),
				array(
					'handle' => 'flowplayer',
					'src' => trailingslashit( $uri ) . 'flowplayer/flowplayer.min.js',
					'deps' => array( 'jquery' ),
					'ver' => '5.4.1',
					'in_footer' => false
				),
				array(
					'handle' => 'wp-models-single-model',
					'src' => trailingslashit( $uri ) . 'wp-models-single.js',
					'deps' => array( 'colorbox', 'flowplayer' ),
					'ver' => false,
					'in_footer' => false
				)
			);
		}
		
		/**
		 * initialize the CPT meta boxes
		 *
		 * @package WP Models
		 * @subpackage Custom Post Types
		 *
		 * @param string $post_id
		 * @param string $txtdomain The text domain to use for the label translations.
		 * @since 0.1
		 * @see http://codex.wordpress.org/Function_Reference/add_meta_boxes
		 */
		public function init_metaboxes( $post_id, $txtdomain = '' )
		{
			if ( $txtdomain = '' and isset( $this->txtdomain ) )
				$txtdomain = $this->txtdomain;
				
			$meta = get_post_meta( $post_id, self::$metakey, true );
			$meta = Helper_Functions::sanitize_text_field_array( $meta );
			
			$this->metaboxes = array(
				new WP_Metabox(
					self::$slug . '-model-details',
					__( 'Model Details', $txtdomain ),
					null,
					self::$slug,
					'side',
					'default',
					array (
						'view' => 'metabox_model_details.php',
						/* 'model_age' => isset( $meta['model_age'] ) ? $meta['model_age'] : '' */
						'meta' => $meta
					)
				),
				new WP_Metabox(
					self::$slug . '-model-pics',
					__( 'Model Pictures', $txtdomain ),
					null,
					self::$slug,
					'normal',
					'high',
					array (
						'view' => 'metabox_pics_html.php'
					) 
				),
				new WP_Metabox(
					self::$slug . '-model-vids',
					__( 'Model Videos', $txtdomain ),
					null,
					self::$slug,
					'normal',
					'high',
					array (
						'view' => 'metabox_vids_html.php'
					) 
				)
			);
		}
		
		/**
		 * Get the CPT messages
		 *
		 * @package WP Models
		 * @subpackage Custom Post Types
		 * @param object $post The WP post object.
		 * @param string $txtdomain The text domain to use for localization.
		 * @return array $messages The messages array.
		 * @since 0.1
		 */
		public function get_post_updated_messages( $post, $txtdomain ) 
		{
			
			$messages = array(
				0 => null, // Unused. Messages start at index 1.
				1 => sprintf( __('Model updated. <a href="%s">View model</a>', $txtdomain), esc_url( get_permalink($post_ID) ) ),
				2 => __('Custom field updated.', $txtdomain),
				3 => __('Custom field deleted.', $txtdomain),
				4 => __('Model updated.', $txtdomain),
				/* translators: %s: date and time of the revision */
				5 => isset($_GET['revision']) ? sprintf( __('Model restored to revision from %s', $txtdomain), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6 => sprintf( __('Model published. <a href="%s">View model</a>', $txtdomain), esc_url( get_permalink($post_ID) ) ),
				7 => __('Model saved.', $txtdomain),
				8 => sprintf( __('Model submitted. <a target="_blank" href="%s">Preview model</a>', $txtdomain), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
				9 => sprintf( __('Model scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview model</a>', $txtdomain),
				  // translators: Publish box date format, see http://php.net/date
				  date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
				10 => sprintf( __('Model draft updated. <a target="_blank" href="%s">Preview model</a>', $txtdomain), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) )
			);
		
			return $messages;
		}
		
		/**
		 * Save the post meta.
		 *
		 * @package WP Models
		 * @subpackage Custom Post Types
		 * @param string $post_data The $_POST data
		 * @since 0.1
		 */
		public function save( $post_data )
		{
			if ( isset( $post_data['wp-models-model-age'] ) )
				$meta['model_age'] = sanitize_text_field( $post_data['wp-models-model-age'] );
			
			if ( isset( $post_data['wp-models-model-height'] ) )
				$meta['model_height'] = sanitize_text_field( $post_data['wp-models-model-height'] );
			
			if ( isset( $post_data['wp-models-model-weight'] ) )
				$meta['model_weight'] = sanitize_text_field( $post_data['wp-models-model-weight'] );
				
			if ( isset( $post_data['wp-models-model-bust'] ) )
				$meta['model_bust'] = sanitize_text_field( $post_data['wp-models-model-bust'] );
				
			if ( isset( $post_data['wp-models-model-waist'] ) )
				$meta['model_waist'] = sanitize_text_field( $post_data['wp-models-model-waist'] );
					
			if( isset( $post_data['wp-models-model-hips' ] ) )
				$meta['model_hips'] = sanitize_text_field( $post_data['wp-models-model-hips'] );
			
			update_post_meta( $post_data['post_ID'], self::$metakey, $meta );
		}
		
		public function delete( $post_id )
		{
			//delete the media directory for this post
		}
		
		
		/**
		 * Get the model meta info
		 *
		 * @package pkgtoken
		 * @subpackage subtoken
		 * @param string $post_id The WP post id
		 * @return string $info The model meta info
		 * @since 0.1
		 */
		public static function get_info( $post_id)
		{
			$meta = get_post_meta( $post_id, self::$metakey, true );
			
			//create the info string from meta keys/values
			if( is_array( $meta ) ):
				foreach( $meta as $key => $item ):
					$info .= ucfirst( str_replace('model_', '', $key ) ) . ': ' . $item . ' ';			
				endforeach;
			endif;
			
			//allow the end user to filter the info line
			$info = apply_filters( 'wp_models_filter_model_info', $info, $post_id, $meta );
			
			return $info;
		}
		
		/**
		 * Get all media of a certain type attached to the shoot.
		 *
		 * This function will call the appropriate content getter function based upon the $location property. This currently supports local storage as well as Amazon S3.
		 * It will return an array containing information regarding the files present. Each item in the array will itself be an array with the following elements:
		 * 		uri- the media item uri
		 * 		filename- the media item filename
		 * 		filetype- the file extension (jpg, png, etc)
		 * 		mimetype- the file mime type (image/jpg, video/webm, etc)
		 
		 * @package WP Models
		 * @subpackage Custom Post Types
		 * @param string $post_id The WP post ID.
		 * @param string $type The media type (pics, vids). This is used to determine storage location directories.
		 * @param string $location The storage location used by this plugin ( local, amazons3 ).
		 * @param string $access_key The remote storage service access key.
		 * @param string $secret_key The remote storage service secret key.
		 * @param string $bucket The remote storage service storage location.
		 * @return array $contents 
		 * @since 0.1
		 */
		public function get_media( $post_id, $type, $location = 'local', $access_key = null, $secret_key = null , $bucket = null )
		{
			switch( $location ){
				case 'amazonS3':
					if ( is_null( $access_key ) || is_null( $secret_key) || is_null( $bucket ) )
						return new WP_ERROR;
					return $this->get_shoot_media_amazonS3( $post_id, $type, $access_key, $secret_key, $bucket );
					break;
				
				default:	//local storage
					return $this->get_shoot_media_local( $post_id, $type );
					break;
			}
		}
		
		/**
		 * Get shoot media stored locally.
		 *
		 * @package WP Models
		 * @subpackage Custom Post Types
		 * @param string $post_id
		 * @param string $type the media type (pics, vids)
		 * @return array $contents An array containing the following elements:
		 * 		uri- the media item uri
		 * 		filename- the media item filename
		 * 		filetype- the file extension (jpg, png, etc)
		 * 		mimetype- the file mime type (image/jpg, video/webm, etc)
		 * @since 0.1
		 */
		private function get_shoot_media_local( $post_id, $type )
		{
			if ( 'pics' == $type ):
				$valid_types = array( 'png', 'jpg', 'gif' );
			else:
				$valid_types = array( 'mp4', 'ogv', 'webm' );
			endif;
			
			$target = sprintf( '%1$s/%2$s/%3$s',
	 			untrailingslashit( $this->media_upload_dir ),
	 			$post_id,
	 			$type
	 		);
	 		
			if ( is_dir( $target ) ):
				if ( $files = scandir( $target ) ):
					foreach( $files as $entry ):
						$filetype = wp_check_filetype( $entry );
						if( in_array( $filetype['ext'], $valid_types ) )
							$contents[] = array(
								'uri' => sprintf( '%1$s/%2$s/%3$s/%4$s',
									untrailingslashit( $this->media_upload_uri ),
									$post_id,
									$type,
									$entry
								),
								'filename' => $entry,
								/**
								 * @todo Are the following two parameters needed?
								 */
								'filetype' => $filetype['ext'],
								'mimetype' => $filetype['type']
							);
					endforeach;
				endif;
			endif;
			
			return $contents;
		}
		
		/**
		 * Save the media attached to this model
		 *
		 * @package WP Models
		 * @subpackage Custom Post Types
		 * @param object $post The $_POST object.
		 * @param object $files The $_FILES object.
		 * @param bool $log Log the file upload. Default is false.
		 * @since 0.1
		 */
		public function save_media( $post, $files, $log = false )
		{
			//verify the directory/subdirectories exist and have an index.php
			Helper_Functions::create_directory( $this->media_upload_dir );
			Helper_Functions::create_directory(trailingslashit( $this->media_upload_dir ) . $post['post_id'] );
			Helper_Functions::create_directory(trailingslashit( $this->media_upload_dir ) . $post['post_id'] . '/' . $post['type'] );
			
			$target = sprintf( '%1$s/%2$s/%3$s',
	 			untrailingslashit( $this->media_upload_dir ),
	 			$post['post_id'],
	 			$post['type']
	 		);
	 		
	 		Helper_Functions::plupload( $post, $files, $target, $log );
		}
		
		public function delete_media( $post_id, $media, $media_type, $location )
		{
			switch( $location )
			{
				case 'local':
					$target = trailingslashit( $this->media_upload_dir ) . trailingslashit( $post_id ) . trailingslashit( $media_type ) . $media;
					if( file_exists( $target ) )
						return unlink( $target );
					break;
			}
		}
		
		/**
		 * Get all models.
		 *
		 * @package WP Models
		 * @subpackage Custom Post Types
		 * @returns array $models An array containing the post objects for each model.
		 * @since 0.1
		 */
		public function get_models()
		{
			$args = array(
				'post_type' 	=> self::$slug,
				'orderby' 		=> 'title',
				'order' 		=> 'ASC'
			);
			
			$models = get_posts( $args );
			
			return $models;
		}
		
		/**
		 * Get a model's age
		 *
		 * @package WP Models
		 * @subpackage Custom Post Types
		 * @param string $model_id The WP post ID for this model.
		 * @since 0.1
		 */
		public static function get_model_age( $model_id )
		{
			$meta =  get_post_meta( $model_id, self::$metakey, true );
			return sanitize_text_field( $meta['model_age'] );
		}
		
		/**
		 * Get a model's height
		 *
		 * @package WP Models
		 * @subpackage Custom Post Types
		 * @param string $model_id The WP post ID for this model.
		 * @since 0.1
		 */
		public static function get_model_height( $model_id )
		{
			$meta =  get_post_meta( $model_id, self::$metakey, true );
			return sanitize_text_field( $meta['model_height'] );
		}
		/**
		 * Get a model's weight
		 *
		 * @package WP Models
		 * @subpackage Custom Post Types
		 * @param string $model_id The WP post ID for this model.
		 * @since 0.1
		 */
		public static function get_model_weight( $model_id )
		{
			$meta =  get_post_meta( $model_id, self::$metakey, true );
			return sanitize_text_field( $meta['model_weight'] );
		}
		/**
		 * Get a model's bust measurement
		 *
		 * @package WP Models
		 * @subpackage Custom Post Types
		 * @param string $model_id The WP post ID for this model.
		 * @since 0.1
		 */
		public static function get_model_bust( $model_id )
		{
			$meta =  get_post_meta( $model_id, self::$metakey, true );
			return sanitize_text_field( $meta['model_bust'] );
		}
		/**
		 * Get a model's waist measurement
		 *
		 * @package WP Models
		 * @subpackage Custom Post Types
		 * @param string $model_id The WP post ID for this model.
		 * @since 0.1
		 */
		public static function get_model_waist( $model_id )
		{
			$meta =  get_post_meta( $model_id, self::$metakey, true );
			return sanitize_text_field( $meta['model_waist'] );
		}
		/**
		 * Get a model's hips measurement
		 *
		 * @package WP Models
		 * @subpackage Custom Post Types
		 * @param string $model_id The WP post ID for this model.
		 * @since 0.1
		 */
		public static function get_model_hips( $model_id )
		{
			$meta =  get_post_meta( $model_id, self::$metakey, true );
			return sanitize_text_field( $meta['model_hips'] );;
		}
		
		/**
		 * The wp_models_model_age shortcode handler
		 *
		 * @package pkgtoken
		 * @subpackage subtoken
		 * @param array $args The shortcode arguments.
		 * @return string The model age.
		 * @since 0.1
		 */
		public function wp_models_model_info( $args )
		{
			global $post;
			return self::get_info( $post->ID );
		}
		
		/**
		 * The wp_models_model_age shortcode handler
		 *
		 * @package pkgtoken
		 * @subpackage subtoken
		 * @param array $args The shortcode arguments.
		 * @return string The model age.
		 * @since 0.1
		 */
		public function wp_models_model_age( $args )
		{
			global $post;
			return self::get_model_age( $post->ID );
		}
		
		/**
		 * The wp_models_model_height shortcode handler
		 *
		 * @package pkgtoken
		 * @subpackage subtoken
		 * @param array $args The shortcode arguments.
		 * @return string The model height.
		 * @since 0.1
		 */
		public function wp_models_model_height( $args )
		{
			global $post;
			return self::get_model_height( $post->ID );
		}
		
		/**
		 * The wp_models_model_weight shortcode handler
		 *
		 * @package pkgtoken
		 * @subpackage subtoken
		 * @param array $args The shortcode arguments.
		 * @return string The model weight.
		 * @since 0.1
		 */
		public function wp_models_model_weight( $args )
		{
			global $post;
			return self::get_model_weight( $post->ID );
		}
		
		/**
		 * The wp_models_model_bust shortcode handler
		 *
		 * @package pkgtoken
		 * @subpackage subtoken
		 * @param array $args The shortcode arguments.
		 * @return string The model bust measurement.
		 * @since 0.1
		 */
		public function wp_models_model_bust( $args )
		{
			global $post;
			return self::get_model_bust( $post->ID );
		}
		
		/**
		 * The wp_models_model_waist shortcode handler
		 *
		 * @package pkgtoken
		 * @subpackage subtoken
		 * @param array $args The shortcode arguments.
		 * @return string The model waist measurement.
		 * @since 0.1
		 */
		public function wp_models_model_waist( $args )
		{
			global $post;
			return self::get_model_waist( $post->ID );
		}
		
		/**
		 * The wp_models_model_hips shortcode handler
		 *
		 * @package pkgtoken
		 * @subpackage subtoken
		 * @param array $args The shortcode arguments.
		 * @return string $age The model age.
		 * @since 0.1
		 */
		public function wp_models_model_hips( $args )
		{
			global $post;
			return self::get_model_hips( $post->ID );
		}
		
		public function activate()
		{
		}
	 }
endif;
?>
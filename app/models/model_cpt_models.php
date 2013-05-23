<?php
/**
 * The Models custom post type model.
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

if ( ! class_exists( 'WP_Models_CPT_Models_Model' ) ):
	/**
	 * The Models model class.
	 *
	 * @package WP Models\Models
	 * @version 0.1
	 * @since WP Models 0.1
	 */
	 class WP_Models_CPT_Models_Model extends Base_Model_CPT
	 {
	 	/**
	 	 * The CPT slug.
	 	 *
	 	 * @package WP Models\Models
	 	 * @var string
	 	 * @since 0.1
	 	 */
	 	protected static $slug = 'wp-models-model';
	 	
	 	/**
	 	 * The key used to store meta info.
	 	 *
	 	 * @package WP Models\Models
	 	 * @var string
	 	 * @since 0.1
	 	 */
	 	protected static $metakey = '_wp-models-model';
	 	/**
		 * The media upload directory path
		 *
		 * @package WP Models\Models
		 * @var string
		 * @since 0.1
		 */
		protected $media_upload_dir;
		
		/**
		 * The media upload directory uri
		 *
		 * @package WP Models\Models
		 * @var string
		 * @since 0.1
		 */
		protected $media_upload_uri;
		
	 	/**
	 	 * The class constructor.
	 	 *
	 	 * @package WP Models\Models
	 	 * @param string $uri The plugin uri (e.g. http://example.com/wp-content/plugins/myplugin/ ).
	 	 * @param string $txtdomain The plugin textdomain. used to localize the arguments.
	 	 * @since 0.1
	 	 */
	 	public function __construct( $uri, $txtdomain )
	 	{
	 		//specify our upload directories for media attached to this post type
	 		$uploads_dir = wp_upload_dir();
	 		$this->media_upload_dir = trailingslashit( $uploads_dir['basedir'] ) . self::$slug;
	 		$this->media_upload_uri = trailingslashit( content_url() ) . 'uploads/' . self::$slug;
	 	}
	 	
	 	/**
		 * initialize the CPT arguments for register_post_type
		 *
		 * @package WP Models\Models
		 * @param string $uri The plugin uri (e.g. http://example.com/wp-content/plugins/myplugin/ ).
		 * @param string $txtdomain The plugin text domain. Used for localizations.
		 * @since 0.1
		 */
		protected function init_args( $uri, $txtdomain )
		{		
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
		 * Initialize the shortcodes property
		 *
		 * @package WP Models\Models
		 * @since 0.1
		 */
		public function init_shortcodes()
		{
			$this->shortcodes = array(
				'wpmodelsmodelage' => array( &$this, 'shortcode_wp_models_model_age' ),
				'wpmodelsmodelheight' => array( &$this, 'shortcode_wp_models_model_height' ),
				'wpmodelsmodelweight' => array( &$this, 'shortcode_wp_models_model_weight' ),
				'wpmodelsmodelbust' => array( &$this, 'shortcode_wp_models_model_bust' ),
				'wpmodelsmodelwaist' => array( &$this, 'shortcode_wp_models_model_waist' ),
				'wpmodelsmodelhips' => array( &$this, 'shortcode_wp_models_model_hips' )
			);
		}
		
		/**
		 * Inititalize the help screen tab
		 *
		 * @package WP Models\Models
		 * @param string $path The absolute path to the plugin app views directory.
		 * @param string $txtdomain The plugin text domain.
		 * @since 0.1
		 */
		protected function init_help_screen( $path, $txtdomain )
		{
			$this->help_screen = array(
				new Base_Model_Help_Tab( __( 'Overview', $txtdomain ), 'wp-models-help', null, null, $path . 'help_screen_wp_models_model_overview.html' ),
				new Base_Model_Help_Tab( __( 'Uploading Media', $txtdomain ), 'wp-models-help-upload', null, null, $path . 'help_screen_wp_models_upload.html' )
			);
		}
		
		/**
		 * Initialize the admin_scripts property.
		 *
		 * @package WP Models\Models
		 * @param object $post The WP post object.
		 * @param string $txtdomain The plugin text domain.
		 * @param string $uri The plugin js uri ( e.g. http://example.com/wp-content/plugins/myplugin/js )
		 * @since 0.1
		 */
		public function init_admin_scripts( $post, $txtdomain, $uri )
		{
			$uri = trailingslashit( $uri );
			
			$this->admin_scripts = array(
	 			new Base_Model_JS_Object( 
	 				'jquery-plupload-queue',
	 				$uri . 'plupload/jquery.plupload.queue/jquery.plupload.queue.js',
	 				array( 'plupload-all' ),
	 				'1.5.7',
	 				false
	 			),
	 			new Base_Model_JS_Object(
		 			'wp-models-admin-cpt',
		 			$uri . 'wp-models-admin-cpt.js',
		 			array( 'jquery-plupload-queue' ),
		 			false,
		 			true,
		 			'wpModelsL10n',
		 			array(
	 					'storage'		=> 'local',	//deafult to local. Set later by plugin controller
	 					'url'			=> admin_url( 'admin-ajax.php' ),
	 					'post_id'		=> $post->ID,
	 					'post_type'		=> self::$slug
	 				)
	 			),
	 			new Base_Model_JS_Object(
	 				'flowplayer',
	 				$uri . 'flowplayer/flowplayer.js',
	 				array( 'jquery' ),
	 				'5.4.17',
	 				false
	 			),
	 			new Base_Model_JS_Object(
					'colorbox',
					$uri . 'colorbox/jquery.colorbox.js',
					array( 'jquery' ),
					'1.4.15',
					false
				)
	 		);
	 	}
	 			
		/**
		 * initialize the admin_css property
		 *
		 * @package WP Models\Models
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
		
		/**
		 * Initialize the cpt frontend css.
		 *
		 * @package WP Models\Models
		 * @param string $uri The plugin uri (e.g. http://example.com/wp-content/plugins/myplugin/ ).
		 * @since 0.1
		 */
		public function init_css( $uri )
		{
			$uri = trailingslashit( $uri );
			
			$this->css = array(
	 			array(
	 				'handle' => 'wp-models',
	 				'src' => $uri .  'wp-models.css',
	 				'deps' => false,
	 				'ver' => false,
	 				'media' => 'all'
	 			),
	 			array(
	 				'handle' => 'colorbox',
	 				'src' => $uri .  'colorbox/colorbox.css',
	 				'deps' => false,
	 				'ver' => false,
	 				'media' => 'all'
	 			),
	 			array(
	 				'handle' => 'flowplayer',
	 				'src' => $uri .  'flowplayer/minimalist.css',
	 				'deps' => false,
	 				'ver' => false,
	 				'media' => 'all'
	 			)
	 		);
		}
		
		/**
		 * Initialize the cpt frontend js.
		 *
		 * @package WP Models\Models
		 * @param string $uri The plugin uri (e.g. http://example.com/wp-content/plugins/myplugin/ ).
		 * @since 0.1
		 */
		public function init_scripts( $uri )
		{
			$uri = trailingslashit( $uri );
			
			$this->scripts = array(
				new Base_Model_JS_Object(
					'colorbox',
					$uri . 'colorbox/jquery.colorbox.js',
					array( 'jquery' ),
					'1.4.15',
					false
				),
				new Base_Model_JS_Object(
					'flowplayer',
					$uri . 'flowplayer/flowplayer.min.js',
					array( 'jquery' ),
					'5.4.1',
					false
				),
				new Base_Model_JS_Object(
					'wp-models-single-model',
					$uri . 'wp-models-single.js',
					array( 'colorbox', 'flowplayer' ),
					false,
					false
				)
			);
		}
		
		/**
		 * initialize the CPT meta boxes
		 *
		 * @package WP Models\Models
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
				new Base_Model_Metabox(
					self::$slug . '-model-details',
					__( 'Model Details', $txtdomain ),
					null,
					self::$slug,
					'side',
					'default',
					array (
						'view'			=> 'metabox_model_details.php',
						'model_age'		=> isset( $meta['model_age'] )		? $meta['model_age'] : '',
						'model_height'	=> isset( $meta['model_height'] )	? $meta['model_height'] : '',
						'model_weight'	=> isset( $meta['model_weight'] )	? $meta['model_weight'] : '',
						'model_bust'	=> isset( $meta['model_bust'] )		? $meta['model_bust'] : '',
						'model_waist'	=> isset( $meta['model_waist'] )	? $meta['model_waist'] : '',
						'model_hips'	=> isset( $meta['model_hips'] )		? $meta['model_hips'] : '',
					)
				),
				new Base_Model_Metabox(
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
				new Base_Model_Metabox(
					self::$slug . '-model-pics-uploader',
					__( 'Model Pictures Uploader', $txtdomain ),
					null,
					self::$slug,
					'normal',
					'high',
					array (
						'view' => 'metabox_pics_uploader_html.php'
					) 
				),
				new Base_Model_Metabox(
					self::$slug . '-model-vids',
					__( 'Model Videos', $txtdomain ),
					null,
					self::$slug,
					'normal',
					'high',
					array (
						'view' => 'metabox_vids_html.php'
					) 
				),
				new Base_Model_Metabox(
					self::$slug . '-model-vids-uploader',
					__( 'Model Videos Uploader', $txtdomain ),
					null,
					self::$slug,
					'normal',
					'high',
					array (
						'view' => 'metabox_vids_uploader_html.php'
					) 
				)
			);
		}
		
		/**
		 * Get the CPT messages
		 *
		 * @package WP Models\Models
		 * @param object $post The WP post object.
		 * @param string $txtdomain The text domain to use for localization.
		 * @return array $messages The messages array.
		 * @since 0.1
		 */
		public function get_post_updated_messages( $post, $txtdomain ) 
		{
			$messages = array(
				0 => null, // Unused. Messages start at index 1.
				1 => sprintf( __('Model updated. <a href="%s">View model</a>', $txtdomain), esc_url( get_permalink($post->ID) ) ),
				2 => __('Custom field updated.', $txtdomain),
				3 => __('Custom field deleted.', $txtdomain),
				4 => __('Model updated.', $txtdomain),
				/* translators: %s: date and time of the revision */
				5 => isset($_GET['revision']) ? sprintf( __('Model restored to revision from %s', $txtdomain), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6 => sprintf( __('Model published. <a href="%s">View model</a>', $txtdomain), esc_url( get_permalink($post->ID) ) ),
				7 => __('Model saved.', $txtdomain),
				8 => sprintf( __('Model submitted. <a target="_blank" href="%s">Preview model</a>', $txtdomain), esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ) ),
				9 => sprintf( __('Model scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview model</a>', $txtdomain),
				  // translators: Publish box date format, see http://php.net/date
				  date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post->ID) ) ),
				10 => sprintf( __('Model draft updated. <a target="_blank" href="%s">Preview model</a>', $txtdomain), esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ) )
			);
		
			return $messages;
		}
		
		/**
		 * Save the post meta.
		 *
		 * @package WP Models\Models
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
		
		/**
		 * The WP delete_post action callback.
		 *
		 * This function deletes all pics and vids attached to this shoot upon post deletion.
		 *
		 * @package WP Models\Models
		 * @param string $post_id The WP post id.
		 * @since 0.1
		 */
		public function delete( $post_id )
		{
			//delete the media directory for this post
			Helper_Functions::remove_local_directory( trailingslashit( $this->media_upload_dir ) . $post_id, true );
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
		 
		 * @package WP Models\Models
		 * @param string $post_id The WP post ID.
		 * @param string $type The media type (pics, vids). This is used to determine storage location directories.
		 * @param string $location The storage location used by this plugin ( local, amazons3 ).
		 * @param string $access_key The remote storage service access key.
		 * @param string $secret_key The remote storage service secret key.
		 * @param string $bucket The remote storage service storage location.
		 * @return array $contents
		 * @since 0.1
		 */
		public function get_media( $post_id, $type )
		{
			return $this->get_shoot_media_local( $post_id, $type );
		}
		
		/**
		 * Get shoot media stored locally.
		 *
		 * @package WP Models\Models
		 * @param string $post_id
		 * @param string $type the media type (pics, vids)
		 * @return array|bool $contents NULL on absence of media. On success, an array containing the following elements:
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
				
				return $contents;
			else:
				return null;
			endif;
		}
		
		/**
		 * Save the media attached to this model
		 *
		 * @package WP Models\Models
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
		
		/**
		 * Delete an individual item attached to this post.
		 *
		 * @package WP Models\Models
		 * @param string $post_id The WP post id.
		 * @param string $media The media item filename.
		 * @param string $media_type The media type (e.g. pic, vid )
		 * @param string $location The storage location.
		 * @since 0.1
		 */
		public function delete_media( $post_id, $media, $media_type, $location = 'local' )
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
		 * WP 'the_post' action callback
		 *
		 * @package WP Models\Models
		 * @param object $post The WP post object.
		 * @param string $location The storage location.
		 * @return $post The modified post object.
		 * @since 0.1
		 */
		public function the_post( $post, $location = 'local'  )
		{
			if( $post->post_type == self::$slug ):
				
				$meta = get_post_meta( $post->ID, self::$metakey, true );
				
				$post->model_content = $post->post_content;
				$post->model_age = $meta['model_age'];
				$post->model_height = $meta['model_height'];
				$post->model_weight = $meta['model_weight'];
				$post->model_bust = $meta['model_bust'];
				$post->model_waist = $meta['model_waist'];
				$post->model_hips = $meta['model_hips'];
				
				$pics = $this->get_media($post->ID, 'pics', $location);
				if( isset( $pics ) ):
					$post->model_pics = $pics;
					$post->model_pic_count = count($post->model_pics);
					$post->model_current_pic = -1;
				endif;
				
				$vids = $this->get_media($post->ID, 'vids', $location);
				if( isset( $vids ) ):
					$post->model_vids = $vids;
					$post->model_vid_count = count($post->model_vids);
					$post->model_current_vid = -1;
				endif;
			endif;
			
			return $post;
		}
		
		/**
		 * Get all models.
		 *
		 * @package WP Models\Models
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
		 * Get the model meta info
		 *
		 * @package WP Models\Models
		 * @param string $post_id The WP post id.
		 * @return string|bool $info Array containing the model meta info on success, FALSE on failure.
		 * @since 0.1
		 */
		/*
public static function get_model_info( $post_id )
		{
			return get_post_meta( $post_id, self::$metakey, true );
		}
*/
		
		/**
		 * Get a model's age
		 *
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
		 * @package WP Models\Models
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
		 * @package WP Models\Models
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
		 * @package WP Models\Models
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
		 * @package WP Models\Models
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
		 * @package WP Models\Models
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
		 * @package WP Models\Models
		 * @param array $args The shortcode arguments.
		 * @return string The model age.
		 * @since 0.1
		 */
		public function shortcode_wp_models_model_age( $args )
		{
			global $post;
			return self::get_model_age( $post->ID );
		}
		/**
		 * The wp_models_model_height shortcode handler
		 *
		 * @package WP Models\Models
		 * @param array $args The shortcode arguments.
		 * @return string The model height.
		 * @since 0.1
		 */
		public function shortcode_wp_models_model_height( $args )
		{
			global $post;
			return self::get_model_height( $post->ID );
		}
		
		/**
		 * The wp_models_model_weight shortcode handler
		 *
		 * @package WP Models\Models
		 * @param array $args The shortcode arguments.
		 * @return string The model weight.
		 * @since 0.1
		 */
		public function shortcode_wp_models_model_weight( $args )
		{
			global $post;
			return self::get_model_weight( $post->ID );
		}
		
		/**
		 * The wp_models_model_bust shortcode handler
		 *
		 * @package WP Models\Models
		 * @param array $args The shortcode arguments.
		 * @return string The model bust measurement.
		 * @since 0.1
		 */
		public function shortcode_wp_models_model_bust( $args )
		{
			global $post;
			return self::get_model_bust( $post->ID );
		}
		
		/**
		 * The wp_models_model_waist shortcode handler
		 *
		 * @package WP Models\Models
		 * @param array $args The shortcode arguments.
		 * @return string The model waist measurement.
		 * @since 0.1
		 */
		public function shortcode_wp_models_model_waist( $args )
		{
			global $post;
			return self::get_model_waist( $post->ID );
		}
		
		/**
		 * The wp_models_model_hips shortcode handler
		 *
		 * @package WP Models\Models
		 * @param array $args The shortcode arguments.
		 * @return string $age The model age.
		 * @since 0.1
		 */
		public function shortcode_wp_models_model_hips( $args )
		{
			global $post;
			return self::get_model_hips( $post->ID );
		}
	 }
endif;
?>
<?php
/**
 * Main plugin controller.
 *
 * @package WP Models\Controllers
 * @author ActionHook.com <plugins@actionhook.com>
 * @since WP Models 0.1
 * @copyright 2013 ActionHook.com
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

if ( ! class_exists( 'WP_Models' ) ):
	/**
	 * The main WP_Models controller class
	 *
	 * @package WP Models\Controllers
	 *
	 * @version 0.1
	 * @since WP Models 0.1
	 */
	 class WP_Models extends Base_Controller_Plugin
	 {
	 	/**
	 	 * The storage locations available.
	 	 *
	 	 * An array of WP_Models_Model_Storage_Location objects.
	 	 *
	 	 * @package WP Models\Controllers
	 	 * @var array
	 	 * @since 1.1
	 	 */
	 	private $_storage_locations;
	 	
	 	/**
	 	 * The storage location in use by the plugin.
	 	 * 
	 	 * @package WP Models\Controllers
	 	 * @var object
	 	 * @see \WP Models\WP_Models_Model_Storage_Location 
	 	 * @since 0.1
	 	 */
	 	private $_current_storage_location;
	 	
	 	/**
	 	 * Initialize the plugin
	 	 *
	 	 * @package WP Models\Controllers
		 * @since 0.1
	 	 */
	 	public function init()
	 	{	
	 		//require necessary files
	 		require_once( $this->app_models_path . '/model_cpt_models.php' );
	 		require_once( $this->app_models_path . '/model_settings.php' );
	 		require_once( $this->app_path . 'helpers/plupload.php' );
	 		
	 		//get the plugin settings
	 		$this->settings_model = new WP_Models_Settings_Model( $this->uri, $this->app_views_path, $this->txtdomain );
	 		
	 		//intialize the storage locations
	 		$this->init_storage();
	 		
	 		$this->_init_admin_scripts();
	 		
	 		//setup our nonce name and action
	 		$this->nonce_name = '_wp_models_nonce';
	 		$this->nonce_action = '5tyhjDR%6%$%^&*IuhbnmknbGTRFGHJN';
	 		
	 		//set up the plugin custom post types
	 		$models = new WP_Models_CPT_Models_Model( $this->uri, $this->txtdomain );
	 		$this->cpts = array(
	 			$models->get_slug() => $models
	 		);
	 		
	 		$this->add_actions_and_filters();
	 	}
	 	
	 	/**
	 	 * Add action and filter callbacks.
	 	 *
	 	 * @package WP Models\Controllers
	 	 * @since 0.1
	 	 */
	 	private function add_actions_and_filters()
	 	{
	 		//add our ajax callbacks
	 		add_action( 'wp_ajax_wp_models_media_upload', 			array( &$this, 'ajax_media_upload' ) );
	 		add_action( 'wp_ajax_wp_models_get_media', 				array( &$this, 'ajax_get_media_admin' ) );
	 		add_action( 'wp_ajax_nopriv_wp_models_get_media', 		array( &$this, 'ajax_get_media' ) );
	 		add_action( 'wp_ajax_wp_models_delete_shoot_pic', 		array( &$this, 'ajax_delete_media' ) );
	 		add_action( 'wp_ajax_wp_models_delete_shoot_vid', 		array( &$this, 'ajax_delete_media' ) );
	 		
	 		//add other callbacks
	 		add_action( 'activated_plugins', 						array( &$this, 'activated_plugins' ) );

	 		//filter css as necessary
	 		add_filter( 'ah_base_filter_styles-flowplayer', 		array( &$this, 'filter_flowplayer_css' ) );
	 		
	 		//Add additional mimetypes for video uploads
			add_filter( 'upload_mimes', 							array( &$this, 'custom_mimes' ) );
			
			//filter the wp-models-admin-cpt js localization args
			add_filter( 'ah_base_filter_script_localization_args-wp-models-plupload',		array( &$this, 'filter_plupload_js' ) );
	 		add_filter( 'ah_base_filter_script_localization_args-wp-models-admin-cpt',		array( &$this, 'filter_admin_cpt_js' ) );
	 		add_filter( 'ah_base_filter_script_localization_args-wp-models-admin-settings',	array( &$this, 'filter_admin_cpt_js' ) );
	 		
	 		//filter the storage locations field
	 		add_filter( 'ah_filter_settings_field-storage_location', array( &$this, 'filter_settings_field_storage_location' ) );
	 		
			//add content filters if so desired
			$this->settings_model->get_settings( 'wp_models_general', 'use_filter' );
			
			if ( $this->settings_model->get_settings( 'wp_models_general', 'use_filter' ) )
				add_filter( 'the_content',	array( &$this, 'render_single_view' ), 100 );
				
			
			register_activation_hook( $this->main_plugin_file, array( &$this, 'activate' ) );
				
	 	}
	 	
	 	private function _init_admin_scripts()
	 	{	
	 		//initialize the scripts globally so they can be used by add-ons
	 		$this->admin_scripts = array(
	 			new Base_Model_JS_Object( 
	 				'jquery-plupload-queue',
	 				$this->js_uri . 'plupload/jquery.plupload.queue/jquery.plupload.queue.js',
	 				array( 'plupload-all' ),
	 				'1.5.7',
	 				false
	 			),
	 			new Base_Model_JS_Object(
		 			'wp-models-admin-cpt',
		 			$this->js_uri . 'wp-models-admin-cpt.js',
		 			array( 'jquery-plupload-queue' ),
		 			false,
		 			true,
		 			'wpModelsL10n',
		 			array(
	 					'storage'	=> 'local',	//deafult to local. Set later by plugin controller
	 					'url'		=> admin_url( 'admin-ajax.php' ),
	 					'foo'=>'bar',
	 				)
	 			),
	 			new Base_Model_JS_Object(
	 				'wp-models-plupload',
	 				$this->js_uri . 'wp-models-plupload.js',
	 				array( 'jquery-plupload-queue' ),
	 				false,
	 				false,
	 				'wpModelsPluploadL10n'
	 				
	 			),
	 			new Base_Model_JS_Object(
	 				'flowplayer',
	 				$this->js_uri . 'flowplayer/flowplayer.js',
	 				array( 'jquery' ),
	 				'5.4.17',
	 				false
	 			),
	 			new Base_Model_JS_Object(
					'colorbox',
					$this->js_uri . 'colorbox/jquery.colorbox.js',
					array( 'jquery' ),
					'1.4.15',
					false
				)
	 		);
	 	}
	 	
	 	/**
	 	 * The ajax media upload callback.
	 	 *
	 	 * @package WP Models\Controllers
		 * @since 0.1
	 	 */
	 	public function ajax_media_upload()
	 	{
	 		//check for security
	 		if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( $this->nonce_name, 'nonce' ) )
	 			die( 'NONCE CHECK FAILED' );
			
			//get the upload callback and storage bucket for the current storage location
			$callback = $this->storage_locations[$this->settings_model->get_storage_location()]->get_post_callback();
	 		$bucket = $this->storage_locations[$this->settings_model->get_storage_location()]->get_storage_bucket();
	 		
	 		//execute the callback
			if ( isset( $callback ) ):
				if ( is_array( $callback )  && method_exists( $callback[0], $callback[1] ) ):
					$result = call_user_func_array( $callback, array( $_POST, $_FILES, $bucket ) );
				elseif ( function_exists( $callback ) ):
					$result = call_user_func( $callback, $_POST, $_FILES, $bucket );
				endif;
			endif;
	 		
	 		die( $result );
	 	}
	 	
	 	/**
	 	 * The admin ajax media render callback
	 	 *
	 	 * @package WP Models\Controllers
		 * @since 0.1
		 * @todo combine with function below
	 	 */
	 	public function ajax_get_media_admin()
	 	{
	 		$view = trailingslashit( $this->app_views_path ) . 'admin_ajax_'. $_POST['media_type'] . '_html.php';
	 		die( $this->render_media( $_POST['post'], $_POST['post_type'], $_POST['media_type'], $view ) );
	 	}
	 	
	 	/**
	 	 * The ajax media render callback
	 	 *
	 	 * @package WP Models\Controllers
		 * @since 0.1
	 	 */
	 	public function ajax_get_media()
	 	{
	 		$view = trailingslashit( $this->app_views_path ) . 'ajax_'. $_POST['media_type'] . '_html.php';
	 		die( $this->render_media( $_POST['post'], $_POST['post_type'], $_POST['media_type'], $view ) );
	 	}
	 	
	 	/**
	 	 * The callback for the ajax delete media handler.
	 	 *
	 	 * @package WP Models\Controllers
		 * @since 0.1
		 * @todo change ajax action names
	 	 */
	 	public function ajax_delete_media()
	 	{
	 		//check for security
	 		if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( $this->nonce_name, 'nonce' ) )
	 			die( 'Security check failed' );
	 				
	 		if ( $_POST['action'] == 'wp_models_delete_shoot_pic' ):
	 			$type = 'pics';
	 		elseif ( $_POST['action'] == 'wp_models_delete_shoot_vid' ):
	 			$type = 'vids';
	 		endif;
	 		
	 		$result = $this->delete_media( $_POST['post_id'], $_POST['media'], $type, $this->storage_locations[$this->settings_model->get_storage_location()] );
	 		die( $result );
	 	}
	 	
	 	/**
	 	 * Render the media of a specific type attached to this post.
	 	 *
	 	 * @package WP Models\Controllers
	 	 * @param string $post_id The WP post id.
	 	 * @param string $post_type The post type.
	 	 * @param string $media_type The media type.
	 	 * @param string $view The view to used to render the content.
	 	 * @return string|bool The pics html. FALSE on failure.
	 	 * @since 0.1
	 	 */
	 	
	 	public function render_media( $post_id, $post_type, $media_type, $view = null )
	 	{
			//get the post media
			$post_media = $this->get_media( $post_id, $media_type, $this->storage_locations[$this->settings_model->get_storage_location()] );
			
			//if we have an array of media items, include the appropriate view
	 		if (  $post_media ):
	 			if ( is_null( $view ) )
	 				$view = trailingslashit( $this->app_views_path ) . 'ajax_'. $media_type . '_html.php';
	 			
	 			//set variables for the template
				$uri = $this->uri;
				$txtdomain = $this->txtdomain;
				
				switch( $media_type )
				{
					case 'pics':
						$title = sprintf( '%s %s', get_the_title(), _x( 'Pictures', $this->txtdomain ) );
						break;
					case 'vids':
						$title = sprintf( '%s %s', get_the_title(), _x( 'Videos', $this->txtdomain ) );
						break;
				}
				
				//Render the view
		 		ob_start();
		 		require_once( $view );
		 		return ob_get_clean();
	 		else:
	 			switch( $media_type )
	 			{
	 				case 'pics':
	 					return __( 'There are no pictures associated with this post.', $this->txtdomain );
	 					break;
	 				case 'vids':
	 					return __( 'There are no videos associated with this post.', $this->txtdomain );
	 					break;
	 				default:
	 					return false;
	 					break;
	 			}
	 		endif;
	 	}
	 	
	 	/**
	 	 * Add mime types to WP
	 	 *
	 	 * @package WP Models\Controllers
		 * @param array $mimes The exising mimes object.
	 	 * @since 0.1
	 	 */
	 	public function custom_mimes( $mimes )
	 	{
			$mimes['webm'] = 'video/webm';
			$mimes['ogv'] = 'video/ogv';
			return $mimes;
		}
		
		/**
		 * Filter the arguments for the wp-models-cpt-shoots-admin js
		 *
		 * @package WP Models\Controllers
		 * @param array $args Contains key/value pairs of script localizations.
		 * @since 0.1
		 */
		public function filter_admin_cpt_js( $args )
		{
			//add the nonce for media uploads/deletes
			$args['nonce'] = wp_create_nonce( $this->nonce_name );
			
			return $args;
		}
		
		public function filter_plupload_js( $l10n )
		{
			$l10n = $this->_plupload_i18n();
			return $l10n;	
		}
		
		
		public function filter_settings_field_storage_location( $field )
		{	
			if ( count($this->storage_locations) > 1 ):
				//we have multiple possible locations
				foreach($this->storage_locations as $key => $location):
					$location_options[$key] = $location->get_display_name();
				endforeach;

				$field['args']['options'] = $location_options;
				return $field;
			else:
				//remove the field entirely
				return null;
			endif;
		}
		
		/**
		 * Change the Flowplayer CSS based on plugin settings
		 *
		 * @package WP Models\Controllers
		 * @param object $style The style object.
		 * @since 0.1
		 */
		public function filter_flowplayer_css( $style )
		{
			switch( $this->settings_model->get_settings( 'wp_models_general', 'flowplayer_style' ) )
			{
				case 2:
					$style['src'] = trailingslashit( $this->css_uri ) . 'flowplayer/functional.css';
					break;
				case 3:
					$style['src'] = trailingslashit( $this->css_uri ) . 'flowplayer/playful.css';
					break;
				default:
					$style['src'] = trailingslashit( $this->css_uri ) . 'flowplayer/minimalist.css';
			}
			
			return $style;
		}
		
		/**
		 * Render the single cpt page view.
		 *
		 * This view is rendered using the WP filter the_content. This is done to ensure compatibility with all themes and membership plugins.
		 *
		 * @package WP Models\Controllers
		 * @param string $content The WP post content.
		 * @since 0.1
		 */
		public function render_single_view( $content )
		{
			global $post;
			
			if( is_single() && isset ( $this->cpts[$post->post_type] ) ):				
				//this allows the user to add a content-$post_type_slug.php in their theme directory and use that.
				if( file_exists( get_stylesheet_directory() . '/content-' . $post->post_type . '.php' ) ) :
					$view = get_stylesheet_directory() . '/content-' . $post->post_type . '.php';
				elseif ( file_exists( trailingslashit( $this->app_views_path ) . 'content-' . $post->post_type . '.php' ) ):
					$view = trailingslashit( $this->app_views_path ) . 'content-' . $post->post_type . '.php';
				else:
					$view = null;
				endif;
				
				
				if( isset( $view ) ):
					$txtdomain = $this->txtdomain;
					
					//include the view
					ob_start();
					include_once( $view );
					$content = ob_get_clean();
				endif;
			endif;
			
			return $content;
		}
		
		/**
		 * The plugin activation routine.
		 *
		 * @package WP Models\Controllers
		 * @since 0.1
		 */
		public function activate()
		{
		}
		
		/**
		 * The plugin deletion callback
		 *
		 * @package WP Models\Controllers
		 *
		 * @since 0.1
		 * @todo implement this function
		 */
	 	public static function delete()
	 	{
	 		//delete the shoot models table
			//delete the wp-models uploads directory
			
			//delete the plugin options
			delete_option( 'wp_models_general' );
	 	}
	 	
	 	
		
		public function activated_plugins()
		{
			$plugins = get_option('activated_plugins');
			sort($plugins);
			update_option('activated_plugins', $plugins);
		}
		
		/**
		 * Initialize the storage locations
		 *
		 * @package WP Models\Controllers
		 *
		 * @since 1.1
		 */
		public function init_storage()
		{
			$uploads_dir = wp_upload_dir();
			
			require_once( $this->app_models_path . 'model_storage_location.php' );
			$this->storage_locations = array(
				'local' => new WP_Models_Model_Storage_Location(
					__( 'Local Filesystem', $this->txtdomain ),
					null,
					null, 
					trailingslashit( $uploads_dir['basedir'] ) . 'wp-models',
					$this->media_upload_uri = trailingslashit( content_url() ) . 'uploads/wp-models',
					array( &$this, 'get_media_local' ),
					array( &$this, 'save_media_local' ),
					array( &$this, 'delete_media_local' )
				)
			);
			
			/**
			 * @todo change this to a plugin setting.
			 */
			//$storage_location = $this->settings_model->get_storage_location();
			
			if ( isset( $storage_location ) ):
				$this->_current_storage_location = $this->storage_locations[$storage_location];
			else:
				$this->_current_storage_location = $this->storage_locations['local'];
			endif;
		}
		
		/**
		 * Get all media of a certain type attached to the post.
		 *
		 * This function will call the appropriate content getter function based upon the $location property. 
		 * It will return an array containing information regarding the files present. Each item in the array will itself be an array with the following elements:
		 * 		uri- the media item uri
		 * 		filename- the media item filename
		 * 		filetype- the file extension (jpg, png, etc)
		 * 		mimetype- the file mime type (image/jpg, video/webm, etc)
		 
		 * @package WP Models\Controllers
		 * @param string $post_id The WP post ID.
		 * @param string $post_type The post type
		 * @param string $type The media type (pics, vids). This is used to determine storage location directories.
		 * @return array $contents 
		 * @since 0.1
		 */
		public function get_media( $post_id, $type, $location )
		{
			//set the target directory to pass to the callback
			$target = sprintf( '%1$s/%2$s',
	 			$post_id,
	 			$type
	 		);
	 		
	 		$get_callback = $location->get_get_callback();
			
			//get the media from the storage location using the registered callback
	 		if( isset( $get_callback ) ):
	 			if ( is_array( $get_callback ) ):
	 				$media = call_user_func_array( $get_callback, array( $location->get_storage_bucket(), $post_id, $type ) );
	 			elseif ( function_exists( $get_callback ) ):
	 				$media = call_user_func( $get_callback, $location->get_storage_bucket(), $post_id, $type );
	 			endif;
	 		endif;
			
	 		$contents = null;
	 		
	 		//step through the contents to only include the filetypes we wish to see in this view
			if( is_array( $media ) ):
				//set the valid types
				if ( 'pics' == $type ):
					$valid_types = array( 'png', 'jpg', 'gif' );
				else:
					$valid_types = array( 'mp4', 'ogv', 'webm' );
				endif;
				
				$storage_uri = untrailingslashit( $location->get_storage_bucket_uri() );
				
				foreach( $media as $key => $entry ):
					if( in_array( strtolower( $entry['filetype'] ), $valid_types ) ):
						if( ! isset( $entry['uri'] ) )
							$entry['uri'] = sprintf( '%1$s/%2$s/%3$s/%4$s',
								$storage_uri,
								$post_id,
								$type,
								$entry['filename']
							);
						$contents[] = $entry;
					endif;
				endforeach;
			endif;
			
			return $contents;
		}
				
		/**
		 * Get the configured storage locations.
		 *
		 * @package WP Models\Controllers
		 *
		 * @return array $_storage_locations
		 * @since 1.0
		 */
		public function get_storage_locations()
		{
			return $this->_storage_locations;
		}
		
		/**
		 * Get the current storage location.
		 *
		 * @package WP Models\Controllers
		 * @since 0.1
		 */
		public function get_storage_location()
		{
			return $this->current_storage_location;
		}
		
		/**
		 * Add a storage location.
		 *
		 * @package WP Models\Controllers
		 *
		 * @param $location An array of key/value pairs with the first element being the location name and
		 * the second being the storage location object, e.g.:
		 * <code>
		 * $this->add_storage_location( 'cloudspace', $storage_object );
		 * </code>
		 * 
		 * @since 1.0
		 */
		public function add_storage_location( $location )
		{
			if( is_array( $location ) )
				$this->storage_locations[$location[0]] = $location[1];
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
		public function delete_media( $post_id, $media, $media_type, $location )
		{
			$target = trailingslashit( $post_id ) . trailingslashit( $media_type ) . $media;
			$callback = $location->get_delete_callback();

			if ( isset( $callback ) ):
				if ( is_array( $callback )  && method_exists( $callback[0], $callback[1] ) ):
					$result = call_user_func_array( $callback, array( $location->get_storage_bucket(), $post_id, $media_type, $media ) );
				elseif ( function_exists( $callback ) ):
					$result = call_user_func( $callback, $location->get_storage_bucket(), $post_id, $media_type, $media );
				endif;
			endif;
			
			return $result;
		}
		
		/**
		 * Get the current storage location
		 *
		 * @package WP Models\Controllers
		 * @return object $_current_storage_location
		 * @since 0.1
		 */
		public function get_current_storage_location()
		{
			return $this->storage_locations[$this->settings_model->get_storage_location()];
		}
		
		public function get_nonce()
		{
			return wp_create_nonce( $this->nonce_name );
		}
		
		public function get_media_local( $storage_bucket, $post_id, $media_type )
		{
			$target = sprintf( '%1$s/%2$s/%3$s',
				untrailingslashit( $storage_bucket ),
				$post_id,
				$media_type
			);
			return Helper_Functions::get_local_directory_contents( $target );
		}
		
		public function delete_media_local( $storage_bucket, $post_id, $media_type, $media )
		{
			$target = sprintf( '%1$s/%2$s/%3$s/%4$s',
				untrailingslashit( $storage_bucket ),
				$post_id,
				$media_type,
				$media
			);
			return Helper_Functions::delete_local_file( $target );
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
		public function save_media_local( $post, $files, $storage_bucket )
		{
			/**
			 * @todo fix this
			 */
			//verify the directory/subdirectories exist and have an index.php
			Helper_Functions::create_directory( $storage_bucket );
			Helper_Functions::create_directory(trailingslashit( $storage_bucket ) . $post['post_id'] );
			Helper_Functions::create_directory(trailingslashit( $storage_bucket ) . trailingslashit( $post['post_id'] ) . $post['type'] );
			
			$target = sprintf( '%1$s/%2$s/%3$s',
	 			untrailingslashit( $storage_bucket ),
	 			$post['post_id'],
	 			$post['type']
	 		);
	 		return Helper_Functions::plupload( $post, $files, $target, true );
		}
		
		/**
		 * Description
		 *
		 * @package pkgtoken
		 * @since 
		 */
		private function _plupload_i18n()
		{
			return array(
				'select_files' 			=> __( 'Select Files', $this->txtdomain ),
				'queue_files' 			=> __( 'Add files to the upload queue and click the start button.', $this->txtdomain ),
				'filename'				=> __( 'Filename', $this->txtdomain ),
				'status' 				=> __( 'Status', $this->txtdomain ),
				'size'					=> __( 'Size', $this->txtdomain ),
				'add_files'				=> __( 'Add files', $this->txtdomain ),
				'stop_current_upload'	=> __( 'Stop current upload', $this->txtdomain ),
				'start_uploading_queue'	=> __( 'Start uploading queue', $this->txtdomain ),
				'uploaded_x_files'		=> __( 'Uploaded %d/%d files', $this->txtdomain ),
				'n_a'					=> __( 'N/A', $this->txtdomain ),
				'drag_files_here'		=> __( 'Drag files here.', $this->txtdomain ),
				'file_extension_error'	=> __( 'File extension error.', $this->txtdomain ),
				'file_size_error'		=> __( 'File size error.', $this->txtdomain ),
				'init_error'			=> __( 'Init error.', $this->txtdomain ),
				'http_error'			=> __( 'HTTP Error.', $this->txtdomain ),
				'security_error'		=> __( 'Security error.', $this->txtdomain ),
				'generic_error.'		=> __( 'Generic error', $this->txtdomain ),
				'io_error'				=> __( 'IO error.', $this->txtdomain ),
				'stop_upload'			=> __( 'Stop Upload', $this->txtdomain ),
				'start_upload'			=> __( 'Start Upload', $this->txtdomain ),
				'x_files_queued'		=> __( '%d files queued', $this->txtdomain ),
			);
		}
	}
endif;
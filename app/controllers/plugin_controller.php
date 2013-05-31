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
	 		require_once( $this->app_models_path . '/model_cpt_shoots.php' );
	 		require_once( $this->app_models_path . '/model_settings.php' );
	 		require_once( $this->path . 'lib/edd/edd_updater.php' );
	 		
	 		//get the plugin settings
	 		$this->settings_model = new WP_Models_Settings_Model( $this->uri, $this->app_views_path, $this->txtdomain );
	 		
	 		//intialize the storage locations
	 		$this->init_storage();
	 		
	 		//initialize the updater
	 		$args = array(
	 			'license' 	=> $this->settings_model->get_license_key(),
				'item_name'	=> 'WP Models Pro',
				'author'	=> 'ActionHook.com',
				'version' 	=> $this->version
			);
	 		$this->updater = new EDD_Interface( 'http://actionhook.com', $this->main_plugin_file, $args );
	 		
	 		//setup our nonce name and action
	 		$this->nonce_name = '_wp_models_nonce';
	 		$this->nonce_action = '5tyhjDR%6%$%^&*IuhbnmknbGTRFGHJN';
	 		
	 		//set up the plugin custom post types
	 		define( '_WP_MODELS_CPT_MODELS_SLUG', WP_Models_CPT_Models_Model::get_slug() );
	 		define( '_WP_MODELS_CPT_SHOOTS_SLUG', WP_Models_CPT_Shoots_Model::get_slug() );
	 		$this->cpts = array(
	 			_WP_MODELS_CPT_MODELS_SLUG => new WP_Models_CPT_Models_Model( $this->uri, $this->txtdomain ),
	 			_WP_MODELS_CPT_SHOOTS_SLUG => new WP_Models_CPT_Shoots_Model( $this->uri, $this->txtdomain )
	 		);
	 		
	 		$this->add_actions_and_filters();
//print_r($this);
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
	 		add_action( 'wp_ajax_wp_models_activate_license_key',	array( &$this, 'ajax_activate_license' ) );
	 		add_action( 'wp_ajax_wp_models_deactivate_license_key',	array( &$this, 'ajax_deactivate_license' ) );
	 		
	 		//add other callbacks
	 		add_action( 'update_option_wp_models_general', 			array( &$this, 'update_option_wp_models_general' ),10,2 );
	 		
	 		//filter metabox callback args as necessary
	 		add_filter( 'filter_metabox_callback_args', array( &$this, 'setup_metabox_args' ), 10, 2 );
	 		

	 		//filter css as necessary
	 		add_filter( 'ah_base_filter_styles-flowplayer', 		array( &$this, 'filter_flowplayer_css' ) );
	 		
	 		//Add additional mimetypes for video uploads
			add_filter( 'upload_mimes', 							array( &$this, 'custom_mimes' ) );
			
			//filter the wp-models-admin-cpt js localization args
	 		add_filter( 'ah_base_filter_script_localization_args-wp-models-admin-cpt',		array( &$this, 'filter_admin_cpt_js' ) );
	 		add_filter( 'ah_base_filter_script_localization_args-wp-models-admin-settings',	array( &$this, 'filter_admin_cpt_js' ) );
	 		
			//add content filters if so desired
			$this->settings_model->get_settings( 'wp_models_general', 'use_filter' );
			
			if ( $this->settings_model->get_settings( 'wp_models_general', 'use_filter' ) )
				add_filter( 'the_content',	array( &$this, 'render_single_view' ), 100 );
				
			
			register_activation_hook( $this->main_plugin_file, array( &$this, 'activate' ) );
				
	 	}
	 	
	 	/**
	 	 * Add additional metabox callback args as necessary for views.
	 	 *
	 	 * @package WP Models\Controllers
		 * @param object $post The WP post object.
	 	 * @param array $metabox The WP metabox array.
	 	 * @return array $metabox The modified WP metabox array.
	 	 * @todo remove for codex version
	 	 * @since 0.1
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
	 		
	 		$result = $this->delete_media( $_POST['post_id'], $_POST['media'], $type, $this->_current_storage_location );
	 		die( $result );
	 	}
	 	
	 	/**
	 	 * Activate the plugin license
	 	 *
	 	 * @package WP Models\Controllers
	 	 * @since 0.1
	 	 */
	 	public function ajax_activate_license()
	 	{
	 		//check for security
	 		if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( $this->nonce_name, 'nonce' ) )
	 			die( 'Security check failed' );
	 			
	 		if( ! class_exists( 'EDD_Interface' ) )
	 			require_once( $this->path . '/lib/edd/edd_interface.php' );
	 		
	 		$args = array( 'version' => $this->version );
	 		//$edd = new EDD_Interface( 'http://actionhook.com', $this->main_plugin_file, $args );
 			
	 		$status = $this->updater->activate_license( $_POST['key'], 'WP Models Pro' );

 			$txtdomain = $this->txtdomain;
 			
 			if ( $status == 'valid' ):
 				$message = __( 'License Key activated.', $this->txtdomain );
 				$file = 'admin_ajax_license_key_active.php';
 				$this->settings_model->update_license_status( $status );
 			elseif ( $status == false ):
 				$message = __('There was an error contacting the license server. Please try again later.', $this->txtdomain );
 				$file = 'admin_ajax_license_key_inactive.php';
 			else:
 				$message = __( 'License key invalid.', $this->txtdomain );
 				$file = 'admin_ajax_license_key_inactive.php';
 				$this->settings_model->update_license_status( $status );
	 		endif;
	 		
	 		die( require_once( $this->app_views_path . $file ) );
	 	}
	 	
	 	/**
	 	 * Activate the plugin license
	 	 *
	 	 * @package WP Models\Controllers
	 	 * @since 0.1
	 	 */
	 	public function ajax_deactivate_license()
	 	{
	 		//check for security
	 		if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( $this->nonce_name, 'nonce' ) )
	 			die( 'Security check failed' );
	 			
	 		if( ! class_exists( 'EDD_Interface' ) )
	 			require_once( $this->path . '/lib/edd/edd_interface.php' );
	 		
	 		$args = array( 'version' => $this->version );
	 		//$edd = new EDD_Interface( 'http://actionhook.com', $this->main_plugin_file, $args );
 			
	 		$status = $this->updater->deactivate_license( $_POST['key'], 'WP Models Pro' );
	 		
 			$txtdomain = $this->txtdomain;
 			
 			if ( $status == 'deactivated' ):
 				$message = __( 'License Key deactivated.', $this->txtdomain );
 				$file = 'admin_ajax_license_key_inactive.php';
 				$this->settings_model->update_license_status( $status );
 			elseif ( $status == false ):
 				$message = __('There was an error contacting the license server. Please try again later.', $this->txtdomain );
 				$file = 'admin_ajax_license_key_active.php';
 			else:
 				$message = __( 'License key invalid.', $this->txtdomain );
 				$file = 'admin_ajax_license_key_active.php';
 				$this->settings_model->update_license_status( $status );
	 		endif;
	 		
	 		die( require_once( $this->app_views_path . $file ) );
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
			$post_media = $this->_get_media( $post_id, $media_type, $this->storage_locations[$this->settings_model->get_storage_location()] );
			
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
		 * @todo Modfiy this function to allow for end user views in their theme directory
		 */
		public function render_single_view( $content )
		{
			global $post;
			
			if( is_single() && isset ( $this->cpts[$post->post_type] ) ):				
				/*
//get the post media
				$post_pics = $this->_get_media( 
					$post->ID,
					'pics',
					$this->storage_locations[$this->settings_model->get_storage_location()]
				);
				
				$post_vids = $this->_get_media( 
					$post->ID,
					'vids',
					$this->storage_locations[$this->settings_model->get_storage_location()]
				);
				
				//add additional view variables
				$info = sprintf( '<p>Age: %1$s | Height: %2$s | Weight: %3$s | %4$s-%5$s-%6$s</p>',
					$post->model_age,
					$post->model_height,
					$post->model_weight,
					$post->model_bust,
					$post->model_waist,
					$post->model_hips
				);
*/
				
				//this allows the user to add a content-$post_type_slug.php in their theme directory and use that.
				if( file_exists( get_stylesheet_directory() . '/content-' . $post->post_type . '.php' ) ) :
					$view = get_stylesheet_directory() . '/content-' . $post->post_type . '.php';
				else :
					$view = trailingslashit( $this->app_views_path ) . 'content-' . $post->post_type . '.php';
				endif;
				
				$txtdomain = $this->txtdomain;
				
				//include the view
				ob_start();
				require_once( $view );
				$content = ob_get_clean();
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
			$status = $this->updater->check_license();
			$this->settings_model->update_license_status( $status );
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
			delete_option( 'wp_models_license_status' );
			delete_option( 'wp_models_general' );
	 	}
	 	
	 	/**
		 * The update_option_wp_models_general action callback.
		 *
		 * This performs a license check every time the WP Models options are saved and stores the results.
		 *
		 * @package WP Models\Models
		 * @param array $old_value The values currently stored.
		 * @param array $new_value The POSTed values.
		 * @since 0.1
		 */
		public function update_option_wp_models_general( $old_value, $new_value )
		{	
			if( isset( $new_value['license_key'] ) ):
	 			$license_status = $this->updater->check_license( $new_value['license_key'], 'WP Models Pro' );
				$this->settings_model->update_license_status( $license_status );
			endif;
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
		private function _get_media( $post_id, $type, $location )
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
					if( in_array( $entry['filetype'], $valid_types ) ):
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
			
//print_r($this->storage_locations);
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
			return $this->_current_storage_location;
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
print_r($target);
	 		return Helper_Functions::plupload( $post, $files, $target, true );
		}
	}
endif;
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
	 		
	 		//setup our nonce name and action
	 		$this->nonce_name = '_wp_models_nonce';
	 		$this->nonce_action = '5tyhjDR%6%$%^&*IuhbnmknbGTRFGHJN';
	 		
	 		define( '_WP_MODELS_CPT_MODELS_SLUG', WP_Models_CPT_Models_Model::get_slug() );
	 		define( '_WP_MODELS_CPT_SHOOTS_SLUG', WP_Models_CPT_Shoots_Model::get_slug() );
	 			
	 		//get the plugin settings
	 		$this->settings_model = new WP_Models_Settings_Model( $this->uri, $this->app_views_path, $this->txtdomain );
			
	 		//set up the plugin custom post types
	 		$this->cpts = array(
	 			_WP_MODELS_CPT_MODELS_SLUG => new WP_Models_CPT_Models_Model( $this->uri, $this->txtdomain ),
	 			_WP_MODELS_CPT_SHOOTS_SLUG => new WP_Models_CPT_Shoots_Model( $this->uri, $this->txtdomain )
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
	 		//filter metabox callback args as necessary
	 		add_filter( 'filter_metabox_callback_args', array( &$this, 'setup_metabox_args' ), 10, 2 );
	 		
	 		//add our ajax callbacks
	 		add_action( 'wp_ajax_wp_models_media_upload', 			array( &$this, 'ajax_media_upload' ) );
	 		add_action( 'wp_ajax_wp_models_get_media', 				array( &$this, 'ajax_get_media_admin' ) );
	 		add_action( 'wp_ajax_nopriv_wp_models_get_media', 		array( &$this, 'ajax_get_media' ) );
	 		add_action( 'wp_ajax_wp_models_delete_shoot_pic', 		array( &$this, 'ajax_delete_media' ) );
	 		add_action( 'wp_ajax_wp_models_delete_shoot_vid', 		array( &$this, 'ajax_delete_media' ) );
	 		add_action( 'wp_ajax_wp_models_activate_license_key',	array( &$this, 'ajax_activate_license' ) );
	 		add_action( 'wp_ajax_wp_models_deactivate_license_key',	array( &$this, 'ajax_deactivate_license' ) );
	 		
	 		//filter the wp-models-admin-cpt js localization args
	 		add_filter( 'ah_base_filter_script_localization_args-wp-models-admin-cpt',	array( &$this, 'filter_admin_cpt_js' ) );
	 		add_filter( 'ah_base_filter_script_localization_args-wp-models-admin-settings',	array( &$this, 'filter_admin_cpt_js' ) );
	 		
	 		//filter css as necessary
	 		add_filter( 'ah_base_filter_styles-flowplayer', array( &$this, 'filter_flowplayer_css' ) );
	 		
	 		//Add additional mimetypes for video uploads
			add_filter( 'upload_mimes', array( &$this, 'custom_mimes' ) );
			
			//add content filters if so desired
			$this->settings_model->get_settings( 'wp_models_general', 'use_filter' );
			
			if ( $this->settings_model->get_settings( 'wp_models_general', 'use_filter' ) )
				add_filter( 'the_content',	array( &$this, 'render_single_view' ), 100 );
				
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
		
	 		$result = $this->cpts[$_POST['post_type']]->save_media( $_POST, $_FILES, true );
	 		die( $result );
	 	}
	 	
	 	/**
	 	 * The admin ajax media render callback
	 	 *
	 	 * @package WP Models\Controllers
		 * @since 0.1
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
	 	 * Activate the plugin license
	 	 *
	 	 * @package pkgtoken
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
	 		$edd = new EDD_Interface( 'http://actionhook.com', $this->main_plugin_file, $args );
 			
	 		$status = $edd->activate_license( $_POST['key'], 'WP Models Pro' );

 			$txtdomain = $this->txtdomain;
 			
 			if ( $status == 'valid' ):
 				$message = __( 'License Key activated.', $this->txtdomain );
 				$file = 'admin_ajax_license_key_active.php';
 				update_option( 'wp_models_license_status', $status );
 			elseif ( $status == false ):
 				$message = __('There was an error contacting the license server. Please try again later.', $this->txtdomain );
 				$file = 'admin_ajax_license_key_inactive.php';
 			else:
 				$message = __( 'License key invalid.', $this->txtdomain );
 				$file = 'admin_ajax_license_key_inactive.php';
	 		endif;
	 		
	 		die( require_once( $this->app_views_path . $file ) );
	 	}
	 	
	 	/**
	 	 * Activate the plugin license
	 	 *
	 	 * @package pkgtoken
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
	 		$edd = new EDD_Interface( 'http://actionhook.com', $this->main_plugin_file, $args );
 			
	 		$status = $edd->deactivate_license( $_POST['key'], 'WP Models Pro' );
	 		
 			$txtdomain = $this->txtdomain;
 			
 			if ( $status == 'deactivated' ):
 				$message = __( 'License Key deactivated.', $this->txtdomain );
 				$file = 'admin_ajax_license_key_inactive.php';
 				update_option( 'wp_models_license_status', $status );
 			elseif ( $status == false ):
 				$message = __('There was an error contacting the license server. Please try again later.', $this->txtdomain );
 				$file = 'admin_ajax_license_key_active.php';
 			else:
 				$message = __( 'License key invalid.', $this->txtdomain );
 				$file = 'admin_ajax_license_key_active.php';
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
			$post_media = $this->cpts[$post_type]->get_media( $post_id, $media_type );
			
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
	 		
	 		$result = $this->cpts[$_POST['post_type']]->delete_media( $_POST['post_id'], $_POST['media'], $type );
	 		die( $result );
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
				//get the post media
				$post_pics = $this->cpts[$post->post_type]->get_media( 
					$post->ID,
					'pics'
				);
				
				$post_vids = $this->cpts[$post->post_type]->get_media( 
					$post->ID,
					'vids'
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
				
				//this allows the user to add a content-$post_type_slug.php in their theme directory and use that.
				if( file_exists( get_stylesheet_directory() . '/content-' . $post->post_type . '.php' ) ) :
					$view = get_stylesheet_directory() . '/content-' . $post->post_type . '.php';
				else :
					$view = trailingslashit( $this->app_views_path ) . 'content-' . $post->post_type . '.php';
				endif;
				
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
		 * @todo add routine to set the default settings
		 */
		public static function activate()
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
			//delete the uploads directory
	 	}
	 }
endif;
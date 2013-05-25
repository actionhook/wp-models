<?php
/**
 * The Shoots Custom Post Type
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
 
if ( ! class_exists( 'WP_Models_CPT_Shoots_Model' ) ):
	/**
	 * The WP Models Shoots CPT Model
	 *
	 * @package WP Models\Models
	 * @version 0.1
	 * @since WP Models 0.1
	 * @todo Add Rackspace CloudFiles support
	 * @todo Add Dropbox support
	 */
	class WP_Models_CPT_Shoots_Model extends Base_Model_CPT
	{
		/**
		 * The plugin slug.
		 *
		 * @package WP Models\Models
		 * @var string
		 * @static
		 * @since 0.1
		 */
		protected static $slug = 'wp-models-shoot';
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
	 	 * @package WP Models
	 	 * @param string $uri The plugin uri (e.g. http://example.com/wp-content/plugins/myplugin ).
	 	 * @param string $txtdomain The plugin text domain. Used to localize the arguments.
	 	 * @since 0.1
	 	 */
	 	public function __construct( $uri, $txtdomain )
	 	{
	 		$uploads_dir = wp_upload_dir();
	 		
	 		//$this->slug = 'wp-models-shoot';
	 		$this->metakey = 'wp-models-shoot';
	 		$this->shoot_model_table = 'shoot_models';
	 		$this->init_args( $uri, $txtdomain );
	 		$this->media_upload_dir = trailingslashit( $uploads_dir['basedir'] ) . self::$slug;
	 		$this->media_upload_uri = trailingslashit( content_url() ) . 'uploads/' . self::$slug;
	 		
	 	}
	 	
	 	/**
		 * initialize the CPT arguments for register_post_type
		 *
		 * @package WP Models\Models
		 * @param string $uri The plugin uri (e.g. http://example.com/wp-content/plugins/myplugin )
		 * @param string $txtdomain The plugin text domain. Used to localize the arguments.
		 * @see http://codex.wordpress.org/Function_Reference/register_post_type
		 * @since 0.1
		 */
		protected function init_args( $uri, $txtdomain = '' )
		{		
			$labels = array(
				'name'                => _x( 'Shoots', 'Post Type General Name', $txtdomain ),
				'singular_name'       => _x( 'Shoot', 'Post Type Singular Name', $txtdomain ),
				'menu_name'           => __( 'Shoots', $txtdomain ),
				'parent_item_colon'   => __( 'Parent Shoot', $txtdomain ),
				'all_items'           => __( 'All Shoots', $txtdomain ),
				'view_item'           => __( 'View Shoot', $txtdomain ),
				'add_new_item'        => __( 'Add New Shoot', $txtdomain ),
				'add_new'             => __( 'New Shoot', $txtdomain ),
				'edit_item'           => __( 'Edit Shoot', $txtdomain ),
				'update_item'         => __( 'Update Shoot', $txtdomain ),
				'search_items'        => __( 'Search shoots', $txtdomain ),
				'not_found'           => __( 'No shoots found', $txtdomain ),
				'not_found_in_trash'  => __( 'No shoots found in Trash', $txtdomain ),
			);

			$this->args = array(
				'description'         	=> __( 'Shoots', $txtdomain ),
				'labels'              	=> $labels,
				'supports'            	=> array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
				'taxonomies'          	=> null,
				'hierarchical'        	=> false,
				'public'              	=> true,
				'show_ui'             	=> true,
				'show_in_menu'        	=> true,
				'show_in_nav_menus'   	=> true,
				'show_in_admin_bar'   	=> true,
				'menu_icon'           	=> trailingslashit( $uri ) . 'images/shoot.png',
				'can_export'          	=> true,
				'has_archive'         	=> true,
				'exclude_from_search' 	=> false,
				'publicly_queryable'  	=> true,
				'rewrite' 			  	=> array( 'slug' => 'shoots' )
			);
		}
		
		/**
		 * initialize the CPT meta boxes
		 *
		 * @package WP Models\Models
		 *
		 * @param string $post_id
		 * @param string $txtdomain The text domain to use for the label translations.
		 * @see http://codex.wordpress.org/Function_Reference/add_meta_boxes
		 * @since 0.1
		 */
		protected function init_metaboxes( $post_id, $txtdomain = '' )
		{	
			if ( $txtdomain = '' and isset( $this->txtdomain ) )
				$txtdomain = $this->txtdomain;
				
			$meta = get_post_meta( $post_id, $this->metakey, true );
			
			$this->metaboxes = array(
				new Base_Model_Metabox(
					self::$slug . '-models',
					__( 'Shoot Models', $txtdomain ),
					null,
					self::$slug,
					'side',
					'default',
					array (
						'view' => 'metabox_shoot_models_html.php',
						'shoot_models' => $this->get_shoot_models( $post_id )
					) 
				),
				new Base_Model_Metabox(
					self::$slug . '-pics',
					__( 'Shoot Pictures', $txtdomain ),
					null,
					self::$slug,
					'normal',
					'high',
					array (
						'view' => 'metabox_pics_html.php'
					) 
				),
				new Base_Model_Metabox(
					self::$slug . '-shoot-pics-uploader',
					__( 'Shoot Pictures Uploader', $txtdomain ),
					null,
					self::$slug,
					'normal',
					'high',
					array (
						'view' => 'metabox_pics_uploader_html.php'
					) 
				),
				new Base_Model_Metabox(
					self::$slug . '-vids',
					__( 'Shoot Videos', $txtdomain ),
					null,
					self::$slug,
					'normal',
					'high',
					array (
						'view' => 'metabox_vids_html.php'
					) 
				),
				new Base_Model_Metabox(
					self::$slug . '-shoot-vids-uploader',
					__( 'Shoot Videos Uploader', $txtdomain ),
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
				1 => sprintf( __('Shoot updated. <a href="%s">View shoot</a>', $txtdomain), esc_url( get_permalink($post->ID) ) ),
				2 => __('Custom field updated.', $txtdomain),
				3 => __('Custom field deleted.', $txtdomain),
				4 => __('Shoot updated.', $txtdomain),
				/* translators: %s: date and time of the revision */
				5 => isset($_GET['revision']) ? sprintf( __('Shoot restored to revision from %s', $txtdomain), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6 => sprintf( __('Shoot published. <a href="%s">View shoot</a>', $txtdomain), esc_url( get_permalink($post->ID) ) ),
				7 => __('Shoot saved.', $txtdomain),
				8 => sprintf( __('Shoot submitted. <a target="_blank" href="%s">Preview shoot</a>', $txtdomain), esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ) ),
				9 => sprintf( __('Shoot scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview shoot</a>', $txtdomain),
				  // translators: Publish box date format, see http://php.net/date
				  date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post->ID) ) ),
				10 => sprintf( __('Shoot draft updated. <a target="_blank" href="%s">Preview shoot</a>', $txtdomain), esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ) )
			);
		
			return $messages;
		}
		
		/**
		 * Initialize the admin_scripts property.
		 *
		 * @package WP Models\Models
		 * @param object $post The WP post object.
		 * @param string $txtdomain The plugin text domain.
		 * @param string $uri The plugin js uri.
		 * @since 0.1
		 */
		protected function init_admin_scripts( $post, $txtdomain, $uri )
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
					'colorbox',
					$uri . 'colorbox/jquery.colorbox-min.js',
					array( 'jquery' ),
					'1.4.15',
					false
				),
	 			new Base_Model_JS_Object(
		 			'wp-models-admin-cpt',
		 			$uri . 'wp-models-admin-cpt.js',
		 			array( 'jquery-plupload-queue', 'colorbox' ),
		 			false,
		 			true,
		 			'wpModelsL10n',
		 			array(
	 					'storage' => 'local',	//deafult to local. Set later by plugin controller
	 					'url' => admin_url( 'admin-ajax.php' ),
	 					'post_id' => $post->ID,
	 					'post_type' => self::$slug
	 				)
	 			),
	 			new Base_Model_JS_Object(
	 				'flowplayer',
	 				$uri . 'flowplayer/flowplayer.js',
	 				array( 'jquery' ),
	 				'5.4.17',
	 				false
	 			)
	 		);
		}
		
		/**
		 * initialize the admin_css property
		 *
		 * @package WP Models\Models
		 * @param string $uri The uri to the plugin css directory
		 * @since 
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
	 				'src' => $uri .  'colorbox/colorbox.css',
	 				'deps' => false,
	 				'ver' => false,
	 				'media' => 'all'
	 			)
	 		);
		}
		
		/**
		 * Initialize the frontend js.
		 *
		 * @package WP Models\Models
		 * @param string $uri The plugin uri (e.g. http://example.com/wp-content/plugins/myplugin/ ).
		 * @since 
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
		 * Initialize the frontend css
		 *
		 * @package WP Models\Models
		 * @param string $uri The plugin css uri
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
		 * Save the shoot cpt meta.
		 *
		 * @package WP Models\Models
		 * @param string $post_data The $_POST data.
		 * @since 0.1
		 */
		public function save( $post_data )
		{
			global $wpdb;
		
			//clear the existing model shoots
			$db_table_name = $wpdb->prefix . $this->shoot_model_table;
			$wpdb->query( 
				$wpdb->prepare( 
					"
			         DELETE FROM $db_table_name
					 WHERE shoot_id = %d
					",
				        $post_data['post_ID']
			        )
			);
			
			//add the new associations
			if( is_array( $_POST['wp-models-shoot-model'] ) ):		
				foreach( $_POST['wp-models-shoot-model'] as $model ):
					$wpdb->insert( 
						$wpdb->prefix . $this->shoot_model_table, 
						array( 
							'model_id' => $model, 
							'shoot_id' => $post_data['post_ID'] 
						), 
						array( 
							'%d', 
							'%d' 
						) 
					);
				endforeach;
			endif;
		}
		
		/**
		 * delete all media attached to this shoot
		 *
		 * @package WP Models\Models
		 * @param string $post_id Thw WP post id.
		 * @since 0.1
		 */
		public function delete( $post_id )
		{
			//delete all media uploaded for this shoot
			Helper_Functions::remove_local_directory( trailingslashit( $this->media_upload_dir ) . $post_id, true );
		}
		
		/**
		 * Get the models in a shoot.
		 *
		 * @package WP Models\Models
		 * @param string $shoot_id the post ID of the shoot
		 * @return array|false $models An array containing the post id's of the models in the shoot. FALSE on no models.
		 * @since 0.1
		 */
		public function get_shoot_models( $shoot_id )
		{
			global $wpdb;
			
			$shoot_models = $wpdb->get_results(
				sprintf( "SELECT model_id FROM %s WHERE shoot_id=%s", $wpdb->prefix . $this->shoot_model_table, $shoot_id ),
				ARRAY_A
			);
			
			if( $wpdb->num_rows != 0 ):
				//create a single dimension array
				foreach( $shoot_models as $model ):
					$model_id[] = $model['model_id'];
				endforeach;
				
				return $model_id;
			else:
				return false;
			endif;
		}
		
		/**
		 * Get a model's shoots.
		 *
		 * @package WP Models\Models
		 * @param string $model_id the post id of the model
		 * @return array $shoots contains the post id's of the shoots for this model
		 * @since 0.1
		 */
		public function get_model_shoots( $model_id )
		{
			global $wpdb;
			
			$sql = sprintf( "SELECT shoot_id FROM %s WHERE model_id=%s", $wpdb->prefix . $this->shoot_model_table, $model_id );
			
			//get the model/shoot associations
			$model_shoots = $wpdb->get_results(
				sprintf( "SELECT shoot_id FROM %s WHERE model_id=%s", $wpdb->prefix . $this->shoot_model_table, $model_id ),
				ARRAY_A
			);
			
			//create a single dimensional array
			if( $wpdb->num_rows != 0 ):
				foreach( $model_shoots as $shoot ):
					$shoots[] = $shoot['shoot_id'];
				endforeach;
			endif;
	
			return $shoots;
		}
		
		/**
		 * Get the shoot meta info line
		 *
		 * @package WP Models\Models
		 * @param string $post_id The WP post id
		 * @return string $info The shoot meta info
		 * @since 0.1
		 */
		public function get_info( $post_id )
		{
			global $post;
			
			$shoot_models = $this->get_shoot_models( $post_id );
			
			//loop through shoot_models array to retrieve model name
			foreach( $shoot_models as $model_id ):
				$model_post = get_post( $model_id );
				$model_names[] = $model_post->post_title;
			endforeach;
			
			//sort the array alphabetically
			sort( $model_names );
			
			//generate the text string ( Model 1, Model 2, ...)
			foreach( $model_names as $key => $model ):
				$models .= $key > 0 ? ', ' : '';
				$models .= $model;
			endforeach;
			
			$info = sprintf( __( 'Shot on %s with %s', $txtdomain ),
				date_i18n( get_option( 'date_format'), strtotime( $post->post_date ) ),
				$models
			);
			
			//create meta info array for the filter
			$meta = array(
				'date' => $post->post_date,
				'models' =>  $model_names
			);
			
			$info = apply_filters( 'wp_models_filter_shoot_info', $info, $post_id, $meta );
			
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
		 * @package WP Models\Models
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
								'filetype' => $filetype['ext'],
								'mimetype' => $filetype['type']
							);
					endforeach;
				endif;
			endif;
			
			if( isset( $contents ) ):
				return $contents;
			else:
				return false;
			endif;
		}
		
		/**
		 * Get shoot media stored in Amazon S3
		 *
		 * @package WP Models\Models
		 * @param string $post_id The shoot post id.
		 * @param string $type The media type ( pics, vids ).
		 * @param string $access_key The remote storage service public access key.
		 * @param string $secret_key The remote storage service secret key.
		 * @param string $bucket The remote storage service content location.
		 * @return array $contents An array containing the following elements:
		 * 		uri- the media item uri
		 * 		filename- the media item filename 
		 * @since 0.1
		 */
		private function get_shoot_media_amazonS3( $post_id, $type, $access_key, $secret_key, $bucket )
		{
			if( ! class_exists( S3 ) )
				return;
			
			//instantiate the class
			$s3 = new S3($access_key, $secret_key);
			
			//get a listing of all bucket contents
			//this will be paged.
			/**
			 * todo: modify to allow for truncated results
			 */
			$bucket_contents = $s3->getBucket( $bucket );
			//print_r( $bucket_contents);
			
			foreach( $bucket_contents as $file ):
				//print_r( $file );
				//split the name into elements e.g. 76/pics/foo.jpg => array( [0] => '76', [1] => 'pics', [2] => 'foo' )
				$filename = explode( '/', $file['name'] );
				//print_r( $filename );
				if( $filename[0] == $post_id && $filename[1] == $type ):
					//move to this last element of the array, this will be the file name
					end( $filename );
					$contents[] = array(
						'uri' => $s3->getAuthenticatedURL( $bucket, $file['name'], 3600, false, is_SSL() ),
						'filename' => $filename[2]
						/**
						 * @todo Are the following elements necessary?
						 */
						/*
'filetype' => $filetype['ext'],
						'mimetype' => $filetype['type']
*/
					);
				endif;
			endforeach;
			
			return $contents;
		
		}
		
		/**
		 * Save the media attached to this shoot
		 *
		 * @package WP Models\Models
		 * @param object $post The $_POST object.
		 * @param object $files The $_FILES object.
		 * @param bool $log Log the file upload. Default is false.
		 * @since 0.1
		 */
		public function save_media( $post, $files, $log = false )
		{
			//verify the target directory/subdirectories exist and have an index.php
			Helper_Functions::create_directory( $this->media_upload_dir );
			Helper_Functions::create_directory(trailingslashit( $this->media_upload_dir ) . $post['post_id'] );
			Helper_Functions::create_directory(trailingslashit( $this->media_upload_dir ) . $post['post_id'] . '/' . $post['type'] );
			
			$target = sprintf( '%1$s/%2$s/%3$s',
	 			untrailingslashit( $this->media_upload_dir ),
	 			$_POST['post_id'],
	 			$_POST['type']
	 		);
	 		
	 		return( Helper_Functions::plupload( $_POST, $_FILES, $target, $log ) );
		}
		
		/**
		 * Delete a specific media item from this shoot.
		 *
		 * @package WP Models\Models
		 * @param string $post_id The WP post id.
		 * @param string $media The media filename.
		 * @param string $media_type The media type (pics or vids)
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
		 * Actions required to happen at plugin activation for this CPT
		 *
		 * @package WP Models\Models
		 * @since 0.1
		 */
		public function activate()
		{
			global $wpdb;
			
			$db_table_name = $wpdb->prefix . $this->shoot_model_table;
			$wpdb->query( 
				$wpdb->prepare( 
					"
			         CREATE TABLE IF NOT EXISTS `$db_table_name`  (
						`shoot_id` int(11) NOT NULL DEFAULT '0',
						`model_id` int(11) NOT NULL DEFAULT '0',
						PRIMARY KEY (`model_id`,`shoot_id`),
						KEY `model_id` (`model_id`),
						KEY `shoot_id` (`shoot_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					"
			        )
			);
		}
		
		/**
		 * Actions reqired to happen at plugin deletion
		 *
		 * @package WP Models\Models
		 * @since 0.1
		 */
		 public function delete_plugin()
		 {
		 	global $wpdb;
			
			$db_table_name = $wpdb->prefix . $this->shoot_model_table;
		 	$wpdb->query( 
				$wpdb->prepare( 
					"
			         DROP TABLE IF EXISTS `$db_table_name`;
					"
			        )
			);
		 }
	 }
endif;
?>
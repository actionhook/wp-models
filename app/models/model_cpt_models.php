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
	 	/**
	 	 * The class constructor.
	 	 *
	 	 * @package WP Models
	 	 * @subpackage Custom Post Types
	 	 * @param string $txtdomain The plugin textdomain. used to localize the arguments.
	 	 * @since 0.1
	 	 */
	 	public function __construct( $txtdomain )
	 	{
	 		$this->slug = 'wp-models-model';
	 		$this->noncename = 'wp-models-model';
	 		$this->metakey = '_wp-models-model';
	 		$this->init_args( $txtdomain );
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
		protected function init_args( $txtdomain = '' )
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
				'menu_icon'           	=> null,
				'can_export'          	=> true,
				'has_archive'         	=> true,
				'exclude_from_search' 	=> false,
				'publicly_queryable'  	=> true,
				'rewrite' 			  	=> array( 'slug' => 'models' )
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
				
			$meta = get_post_meta( $post_id, $this->metakey, true );
			
			$this->metaboxes = array(
				new WP_Metabox(
					'wp_models-model-details',
					__( 'Model Details', $txtdomain ),
					null,
					$this->slug,
					'side',
					'default',
					array (
						'view' => 'metabox_model_details.php',
						'model_age' => isset( $meta['model_age'] ) ? $meta['model_age'] : '' ,
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
		 * @param string $post_id The WP post ID.
		 * @since 0.1
		 */
		public function save( $post_id )
		{
			if ( isset( $_POST['wp-models-model-age'] ) )
				$meta['model_age'] = $_POST['wp-models-model-age'];
				
			if( isset( $_POST['wp-models-model-sign' ] ) )
				$meta['model_sign'] = $_POST['wp-models-model-sign'];
			
			update_post_meta( $post_id, $this->metakey, $meta );
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
				'post_type' 	=> $this->slug,
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
		public function get_model_age( $model_id )
		{
			$meta =  get_post_meta( $model_id, $this->metakey, true );
			return $meta['model_age'];
		}
	 }
endif;
?>
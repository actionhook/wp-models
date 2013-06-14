<?php
//namespace WPM_Unit_Tests;

class WPM_UnitTest_Factory extends \WP_UnitTest_Factory
{
	/**
	 * The model post type
	 *
	 * @package pkgtoken
	 * @var object WPM_UnitTest_Factory_For_Model
	 * @since WP Models 1.0.2
	 */
	public $model;
	
	public function __construct() {
		parent::__construct();

		$this->model = new WPM_UnitTest_Factory_For_Model( $this );
	}
}

class WPM_UnitTest_Factory_For_Model extends \WP_UnitTest_Factory_For_Post
{
	public function __construct( $factory = null ) {
		parent::__construct( $factory );
		$this->default_generation_definitions = array(
			'post_status' => 'publish',
			'post_title' => new \WP_UnitTest_Generator_Sequence( 'Model title %s' ),
			'post_content' => new \WP_UnitTest_Generator_Sequence( 'Model content %s' ),
			'post_excerpt' => new \WP_UnitTest_Generator_Sequence( 'Model excerpt %s' ),
			'post_type' => 'wp-models-model'
		);
	}
}
?>
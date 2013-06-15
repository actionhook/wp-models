<?php

class TestCptModels extends WP_UnitTestCase
{
	protected $_post;
	private $_cpt;
	
	public function setUp()
	{
		parent::setUp();
		
		$this->factory = new WPM_UnitTest_Factory();
		
		$WP_Models = $this->getMockBuilder('WP_Models')
						->setConstructorArgs( array( 'wp-models', '1.0.2', $plugin_path, __FILE__, plugin_dir_url( __FILE__ ), 'wp-models') )
						->getMock();
		
		$GLOBALS['WP_Models'] = $WP_Models;
						
		//instantiate the cpt
		$this->_cpt = new WP_Models_CPT_Models_Model( plugin_dir_url( __FILE__ ), 'mytxtdomain' );
		
		//create a new post and get the id
		$post_id = $this->factory->post->create( array( 'post_title' => 'Test Model', 'post_type' => 'wp-models-model', 'post_status' => 'publish' ) );
		
		$meta = array(
			'model_age' => 24,
			'model_height' => "5'7''",
			'model_weight' => '120 lbs',
			'model_bust' => '34C',
			'model_waist' => 25,
			'model_hips' => 36 
		);
		
		update_post_meta( $post_id, ' _wp-models-model', $meta );
		
		$this->_post = get_post( $post_id );
		
		//call this action to intialize the post object fully
		do_action( 'the_post', $this->_post );
	}
	
	public function testGetModels()
	{
		$args = array(
				'post_type' 	=> 'wp-models-model',
				'orderby' 		=> 'title',
				'order' 		=> 'ASC'
		);
		
		$models = get_posts( $args );
		
		$this->assertEquals( $models, $this->_cpt->get_models() );
	}
	
	public function testGetModelAge()
	{
		$this->assertEquals( 24, $this->_cpt->get_model_age( $this->_post->ID ) );
	}
	
	public function testGetModelHeight()
	{
		$this->assertEquals( "5'7''", $this->_cpt->get_model_height( $this->_post->ID ) );
	}
	
	public function testGetModelWeight()
	{
		$this->assertEquals( '120 lbs', $this->_cpt->get_model_weight( $this->_post->ID ) );
	}
	
	public function testGetModelBust()
	{
		$this->assertEquals( '34C', $this->_cpt->get_model_bust( $this->_post->ID ) );
	}
	
	public function testGetModelWaist()
	{
		$this->assertEquals( 25, $this->_cpt->get_model_waist( $this->_post->ID ) );
	}
	
	public function testGetModelHips()
	{
		$this->assertEquals( 36, $this->_cpt->get_model_hips( $this->_post->ID ) );
	}
	
	public function testPostModelAge()
	{
		$this->assertEquals( 24, $this->_post->model_age );
	}
	
	public function testPostModelHeight()
	{
		$this->assertEquals( "5'7''", $this->_post->model_height );
	}
	
	public function testPostModelWeight()
	{
		$this->assertEquals( '120 lbs', $this->_post->model_weight );
	}
	
	public function testPostModelBust()
	{
		$this->assertEquals( '34C', $this->_post->model_bust );
	}
	
	public function testPostModelWaist()
	{
		$this->assertEquals( 25, $this->_post->model_waist );
	}
	
	public function testPostModelHips()
	{
		$this->assertEquals( 36, $this->_post->model_hips );
	}
}
?>
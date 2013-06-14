<?php
//use \WP_Unit_Tests;

class wpModelsTests extends WP_UnitTestCase
{
	private $_plugin;
	
	/**
	 * intitalize the testing environment
	 *
	 * @package WP Models\Tests
	 * @since 
	 */
	public function setUp() {
		parent::setUp();
		
		global $WP_Models;
		$this->_plugin = $WP_Models;
		
		$this->factory = new WPM_UnitTest_Factory();
		
		//create a new post and get the id
		$post_id = $this->factory->post->create( array( 'post_title' => 'Test Model', 'post_type' => 'wp-models-model', 'post_status' => 'draft' ) );
		
		$meta = array(
			'model_age' => 24,
			'model_height' => "5' 7''",
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
	
	/**
	 * verify plugin initialization
	 *
	 * @package WP Models\Tests
	 * @since 
	 */
	function testPluginInitialization() {  
	    $this->assertFalse( null == $this->_plugin );  
	}
	
	/**
	 * Verify the webm mime type is registered
	 *
	 * @package WP Models\Tests
	 * @since 
	 */
	public function testMimeTypeWebm()
	{
		$this->assertArrayHasKey( 'webm', get_allowed_mime_types() );
	}
	
	/**
	 * Verify the ogv mime type is registered
	 *
	 * @package WP Models\Tests
	 * @since 
	 */
	public function testMimeTypeOgv()
	{
		$this->assertArrayHasKey( 'ogv', get_allowed_mime_types() );
	}
	
	/**
	 * Verify plugin storage locations initialized
	 *
	 * @package WP Models\Tests
	 * @since 
	 */
	public function testPluginStorageLocationsInitialization()
	{
		$this->assertTrue( is_array ( $this->_plugin->get_storage_locations() ) );
	}
	
	public function testLocalFilesystemStorageLocation()
	{
		$this->assertArrayHasKey( 'local', $this->_plugin->get_storage_locations() );
	}
	
	public function testAjaxMediaUploadExists()
	{
		$this->assertTrue( has_action( 'wp_ajax_wp_models_media_upload' ) );
	}
	
	public function testAjaxGetMediaExists()
	{
		$this->assertTrue( has_action( 'wp_ajax_wp_models_get_media' ) );
	}
	
	public function testAjaxNoPrivGetMediaExists()
	{
		$this->assertTrue( has_action( 'wp_ajax_nopriv_wp_models_get_media' ) );
	}
	
	public function testAhBaseFilterScriptLocalizationArgsWpModelsPlupload()
	{
		$this->assertTrue( has_filter( 'ah_base_filter_script_localization_args-wp-models-plupload' ) );
	}
	
	public function testAhBaseFilterScriptLocalizationArgsWpModelsAdminCpt()
	{
		$this->assertTrue( has_filter( 'ah_base_filter_script_localization_args-wp-models-admin-cpt' ) );
	}
	
	public function testAhBaseFilterScriptLocalizationArgsWpModelsAdminSettings()
	{
		$this->assertTrue( has_filter( 'ah_base_filter_script_localization_args-wp-models-admin-settings' ) );
	}
}
?>
    
<?php

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
		
		$this->_plugin = new WP_Models( 'wp-models', '1.0.2', WPM_PLUGIN_PATH , WPM_MAIN_PLUGIN_FILE, WPM_PLUGIN_URL, 'wp-models' );
		$this->factory = new WPM_UnitTest_Factory();
		
		//create a new post and get the id
		$post_id = $this->factory->post->create( array( 'post_title' => 'Test Model', 'post_type' => 'wp-models-model', 'post_status' => 'draft' ) );
		
		//generate some post meta
		$meta = array(
			'model_age' => 24,
			'model_height' => "5' 7''",
			'model_weight' => '120 lbs',
			'model_bust' => '34C',
			'model_waist' => 25,
			'model_hips' => 36 
		);
		
		update_post_meta( $post_id, ' _wp-models-model', $meta );
		
		//store the post as a class property
		$this->_post = get_post( $post_id );
	}
	
	/**
	 * verify base controller plugin exists
	 *
	 * @package pkgtoken
	 * @since
	 */
	public function testBaseControllerPluginExists()
	{
		$this->assertFileExists( WPM_PLUGIN_PATH . '/base/controllers/base_controller_plugin.php' );
	}
	
	/**
	 * verify base controller plugin exists
	 *
	 * @package pkgtoken
	 * @since
	 */
	public function testBaseModelExists()
	{
		$this->assertFileExists( WPM_PLUGIN_PATH . '/base/models/base_model.php' );
	}
	
	public function testBaseModelCptExists()
	{
		$this->assertFileExists( WPM_PLUGIN_PATH . '/base/models/base_model_cpt.php' );
	}
	
	public function testBaseModelHelpTabExists()
	{
		$this->assertFileExists( WPM_PLUGIN_PATH . '/base/models/base_model_help_tab.php' );
	}
	
	public function testBaseModelJsObjectExists()
	{
		$this->assertFileExists( WPM_PLUGIN_PATH . '/base/models/base_model_js_object.php' );
	}
	
	public function testBaseModelMetaboxExists()
	{
		$this->assertFileExists( WPM_PLUGIN_PATH . '/base/models/base_model_metabox.php' );
	}
	
	public function testBaseModelSettingsExists()
	{
		$this->assertFileExists( WPM_PLUGIN_PATH . '/base/models/base_model_settings.php' );
	}
	
	public function testBaseViewBaseOptionsPageExists()
	{
		$this->assertFileExists( WPM_PLUGIN_PATH . '/base/views/base_options_page.php' );
	}
	
	public function testBaseViewBaseSettingsSectionExists()
	{
		$this->assertFileExists( WPM_PLUGIN_PATH . '/base/views/base_settings_section.php' );
	}
	
	public function testPluginControllerExists()
	{
		$this->assertFileExists( WPM_PLUGIN_PATH . '/app/controllers/plugin_controller.php' );
	}
	
	public function testHelperPluploadExists()
	{
		$this->assertFileExists( WPM_PLUGIN_PATH . '/app/helpers/plupload.php' );
	}
	
	public function testModelCptModelsExists()
	{
		$this->assertFileExists( WPM_PLUGIN_PATH . '/app/models/model_cpt_models.php' );
	}
	
	public function testModelSettingsExists()
	{
		$this->assertFileExists( WPM_PLUGIN_PATH . '/app/models/model_settings.php' );
	}
	
	public function testModelStorageLocationExists()
	{
		$this->assertFileExists( WPM_PLUGIN_PATH . '/app/models/model_storage_location.php' );
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
    
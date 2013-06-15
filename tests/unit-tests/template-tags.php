<?php
class WPM_Test_Template_Tags extends WP_UnitTestCase
{
	public function setUp()
	{
		parent::setUp();
		
		//setup the post object
		$this->factory = new WPM_UnitTest_Factory();
		$post_id = $this->factory->post->create( array( 'post_title' => 'Test Model', 'post_type' => 'wp-models-model', 'post_status' => 'publish' ) );
		
		$this->_post = get_post( $post_id );
		$this->_post->model_content = 'Here is some sample content.';
		$this->_post->model_age = 24;
		$this->_post->model_height = "5'7''";
		$this->_post->model_weight = '120 lbs';
		$this->_post->model_bust = '34C';
		$this->_post->model_waist = 25;
		$this->_post->model_hips = 36;
		$this->_post->model_pics = array( 
			array( 
				'uri' => "http://example.com/wp-content/uploads/{$post_id}/foo.jpg",
				'mimetype' => 'image/jpg'
			), 
			array(
				'uri' => "http://example.com/wp-content/uploads/{$post_id}/bar.png",
				'mimetype' => 'image/png'
			)
		);
		$this->_post->model_vids = array( 
			array(
				'uri' => "http://example.com/wp-content/uploads/{$post_id}/baz.mp4",
				'mimetype' => 'video/mp4'
			),
			array(
				'uri' => "http://example.com/wp-content/uploads/{$post_id}/bingo.ogv",
				'mimetype' => 'video/ogv'
			)
		);
		
		$GLOBALS['post'] = $this->_post;
	}
	
	public function test_wp_models_model_content()
	{
		$this->assertEquals( 'Here is some sample content.', wp_models_model_content( false ) );
	}
	
	public function test_wp_models_model_info()
	{
		$info = "Age: 24 | Height: 5'7'' | Weight: 120 lbs | 34C-25-36";
		$this->assertEquals( $info, wp_models_model_info( false ) );
	}
	
	public function test_wp_models_model_age()
	{
		$this->assertEquals( 24, wp_models_model_age( false ) );
	}
	
	public function test_wp_models_model_height()
	{
		$this->assertEquals( "5'7''", wp_models_model_height( false ) );
	}
	
	public function test_wp_models_model_weight()
	{
		$this->assertEquals( '120 lbs', wp_models_model_weight( false ) );
	}
	
	public function test_wp_models_model_bust()
	{
		$this->assertEquals( "34C", wp_models_model_bust( false ) );
	}
	
	public function test_wp_models_model_waist()
	{
		$this->assertEquals( 25, wp_models_model_waist( false ) );
	}
	
	public function test_wp_models_model_hips()
	{
		$this->assertEquals( 36, wp_models_model_hips( false ) );
	}
	
	public function test_wp_models_have_pics_true()
	{
		$this->_post->model_current_pic = 0;
		$this->_post->model_pic_count = 2;
		
		$this->assertTrue( wp_models_have_pics() );
	}
	
	public function test_wp_models_have_pics_false()
	{
		$this->_post->model_current_pic = 0;
		$this->_post->model_pic_count = 0;
		
		$this->assertFalse( wp_models_have_pics() );
	}
	
	public function test_wp_models_the_pic_increment()
	{
		$this->_post->model_current_pic = 0;
		$this->_post->model_pic_count = 2;
		
		wp_models_the_pic();
		
		$this->assertEquals( 1, $this->_post->model_current_pic );
	}
	
	public function test_wp_models_the_pic_current_media()
	{
		$this->_post->model_current_pic = 0;
		$this->_post->model_pic_count = 2;
		
		wp_models_the_pic();
		
		$this->assertEquals( $this->_post->model_pics[1], $this->_post->model_current_media );
	}
	
	public function test_wp_models_the_pic()
	{
		$this->_post->model_current_pic = 0;
		$this->_post->model_pic_count = 2;
		
		$pic = wp_models_the_pic();
		$this->assertEquals( $this->_post->model_current_media,  $pic );
	}
	
	public function test_wp_models_have_vids_true()
	{
		$this->_post->model_current_vid = 0;
		$this->_post->model_vid_count = 2;
		
		$this->assertTrue( wp_models_have_vids() );
	}
	
	public function test_wp_models_have_vids_false()
	{
		$this->_post->model_current_vid = 0;
		$this->_post->model_vid_count = 0;
		
		$this->assertFalse( wp_models_have_vids() );
	}
	
	public function test_wp_models_the_vid_increment()
	{
		$this->_post->model_current_vid = 0;
		$this->_post->model_pic_count = 2;
		
		wp_models_the_vid();
		
		$this->assertEquals( 1, $this->_post->model_current_vid );
	}
	
	public function test_wp_models_the_vid_current_media()
	{
		$this->_post->model_current_vid = 0;
		$this->_post->model_vid_count = 2;
		
		wp_models_the_vid();
		
		$this->assertEquals( $this->_post->model_vids[1], $this->_post->model_current_media );
	}
	
	public function test_wp_models_the_vid()
	{
		$this->_post->model_current_vid = 0;
		$this->_post->model_vid_count = 2;
		
		$vid = wp_models_the_vid();
		$this->assertEquals( $this->_post->model_current_media, $vid );
	}
	
	public function test_wp_models_media_permalink()
	{
		$this->_post->model_current_media = $this->_post->model_pics[1];
		$this->assertEquals( "http://example.com/wp-content/uploads/{$this->_post->ID}/bar.png", wp_models_media_permalink() );
	}
	
	public function test_wp_models_media_mimetype()
	{
		$this->_post->model_current_media = $this->_post->model_pics[1];
		$this->assertEquals( 'image/png', wp_models_media_mimetype( false ) );
	}
}
?>
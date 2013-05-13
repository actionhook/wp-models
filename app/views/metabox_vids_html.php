<?php
/**
 * The generic CPT video metabox view.
 *
 * This view renders an ajax-driven file manager for adding videos to the post. In addition, it will contain a display of all currently attached videos.
 * It is used by both the models and shoots cpt.
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @author authtoken
 * @version 0.1
 * @since 0.1
 */
?>
<div id="<?php echo $metabox['id']; ?>-uploader-container">
	<h4><?php _e( 'Upload Vids:', $txtdomain );?></h4>
	<p><?php _e( 'The maximum file upload size is 600MB. Valid file formats include', $txtdomain);?>: mp4, webm, ogv</p>
	<span class="wp-models-toggle">Toggle</span>
	<div id='<?php echo $metabox['id']; ?>-uploader' class='wp-models-plupload wp-models-vids-uploader'><?php _e( 'Your browser does not support HTML5, Silverlight, or Flash.', $txtdomain ); ?></div>
</div>
<!--
<div id="basic-playlist" class="wp-models-player is-splash is-closeable" data-ratio="0.56">
	<video controls>
		<source type="video/webm"  src="http://stream.flowplayer.org/night3/640x360.webm">
		<source type="video/mp4"   src="http://stream.flowplayer.org/night3/640x360.mp4">
		<source type="video/ogg"   src="http://stream.flowplayer.org/night3/640x360.ogv">
	</video>
	
	<a class="fp-prev"></a>
	<a class="fp-next"></a>
	<div class="fp-playlist">
		<a class="item1" href="http://stream.flowplayer.org/night3/640x360.mp4" data-cuepoints="[0.5, 1]"></a>
		<a class="item2" href="http://stream.flowplayer.org/night1/640x360.mp4" data-cuepoints="[0.9, 1.5]"></a>
		<a class="item3" href="http://stream.flowplayer.org/night5/640x360.mp4"></a>
		<a class="item4" href="http://stream.flowplayer.org/night6/640x360.mp4"></a>
	</div>
</div>
-->
<div id='wp-models-vids-container' class="wp-models-media-container"></div>
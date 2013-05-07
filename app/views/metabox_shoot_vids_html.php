<?php
/**
 * The Shoots CPT video metabox view.
 *
 * This view renders an ajax-driven file manager for adding videos to the shoot. In addition, it will contain a display of all currently attached videos.
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @author authtoken
 * @version 0.1
 * @since 0.1
 */
?>
<div id="wp-models-shoot-pics-uploader-container">
	<h4><?php _e( 'Upload Vids:', $txtdomain );?></h4>
	<span class="wp-models-toggle">Toggle</span>
	<div id='wp-models-shoot-vids-uploader' class='wp-models-plupload'><?php _e( 'Your browser does not support HTML 5, Flash ,Silverlight, or HTML 4.', $txtdomain ); ?></div>
</div>
<div id="basic-playlist" class="wp-models-player is-splash is-closeable" data-ratio="0.56">
	<video>
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
<div id='wp-models-shoot-vids-container' class="wp-models-media-container"></div>
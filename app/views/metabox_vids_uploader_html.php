<?php
/**
 * The generic CPT video metabox view.
 *
 * This view renders an ajax-driven file manager for adding videos to the post. In addition, it will contain a display of all currently attached videos.
 * It is used by both the models and shoots cpt.
 *
 * @package WP Models\Views
 * @version 0.1
 * @author ActionHopk.com <plugins@actionhook.com>
 * @since WP-Models 0.1
 */
?>
<div id="<?php echo $metabox['id']; ?>-uploader-container">
	<h4><?php _e( 'Upload Vids:', $txtdomain );?></h4>
	<p><?php _e( 'The maximum file upload size is 600MB. Valid file formats include', $txtdomain);?>: mp4, webm, ogv</p>
	<div id='<?php echo $metabox['id']; ?>-uploader' class='wp-models-plupload wp-models-vids-uploader'><?php _e( 'Your browser does not support HTML5, Silverlight, or Flash.', $txtdomain ); ?></div>
</div>

<?php
/**
 * The generic CPT pics metabox view.
 *
 * This view is used by both the models and shoots cpt.
 *
 * @package WP Models\Views
 * @version 0.1
 * @author ActionHopk.com <plugins@actionhook.com>
 * @since WP-Models 0.1
 */
?>
<h4><?php _e( 'Upload Pics:', $txtdomain );?></h4>
<div id="<?php echo $metabox['id']; ?>-uploader-container">
	<p><?php _e( 'Valid file formats include', $txtdomain);?>: jpg, png, gif.</p>
	<div id='<?php echo $metabox['id']; ?>-uploader' class='wp-models-plupload wp-models-pics-uploader'><?php _e( 'Your browser does not support HTML 5, Flash , Silverlight, or HTML 4.', $txtdomain ); ?></div>
</div>


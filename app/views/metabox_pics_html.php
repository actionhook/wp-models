<?php
/**
 * The generic CPT pics metabox view.
 *
 * This view is used by both the models and shoots cpt.
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @author authtoken
 * @version 0.1
 * @since 0.1
 */
?>
<div id="<?php echo $metabox['id']; ?>-uploader-container">
	<h4><?php _e( 'Upload Pics:', $txtdomain );?></h4>
	<p><?php _e( 'Valid file formats include', $txtdomain);?>: jpg, png, gif</p>
	<span class="wp-models-toggle">Toggle</span>
	<div id='<?php echo $metabox['id']; ?>-uploader' class='wp-models-plupload wp-models-pics-uploader'><?php _e( 'Your browser does not support HTML 5, Flash , Silverlight, or HTML 4.', $txtdomain ); ?></div>
</div>
<div id='wp-models-pics-container' class="wp-models-media-container"></div>

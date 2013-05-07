<?php
/**
 * The shoot pics view
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @author authtoken
 * @version
 * @since
 */
?>
<h4><?php _e( 'Manage Pics:', $txtdomain );?></h4>
<?php foreach( $shoot_media as $pic ):?>
<div class="wp-models-shoot-pic">
	<button type="button" class="wp-models-shoot-pic-delete" value="<?php echo $pic['filename'];?>">Delete</button>
	<img src="<?php echo $pic['uri']; ?>" />
	<p><?php echo $pic['filename']; ?></p>
</div>
<?php endforeach; ?>
<div style="clear: both;"></div>

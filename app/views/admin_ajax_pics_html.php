<?php
/**
 * The post pics admin view
 *
 * @package WP Models\Views
 * @author ActionHook <plugins@actionhook.com>
 * @version 0.1
 * @since WP Models 0.1
 */
?>
<h4><?php _e( 'Manage Pics:', $txtdomain );?></h4>
<?php foreach( $post_media as $pic ):?>
<div class="wp-models-shoot-pic">
	<button type="button" class="wp-models-pic-delete" value="<?php echo $pic['filename'];?>">Delete</button>
	<a href="<?php echo $pic['uri']; ?>" class="wp-models-model-gallery" title="<?php echo $pic['filename']; ?>"><img src="<?php echo $pic['uri']; ?>" title="<?php echo $pic['filename']; ?>" alt="<?php echo $pic['filename']; ?>" /></a>
</div>
<?php endforeach; ?>
<div style="clear: both;"></div>

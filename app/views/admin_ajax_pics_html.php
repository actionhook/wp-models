<?php
/**
 * The post pics view
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @author authtoken
 * @version
 * @since
 */
?>
<h4><?php _e( 'Manage Pics:', $txtdomain );?></h4>
<?php foreach( $post_media as $pic ):?>
<div class="wp-models-shoot-pic">
	<button type="button" class="wp-models-pic-delete" value="<?php echo $pic['filename'];?>">Delete</button>
	<!-- <a href="<?php echo $pic['uri']; ?>" class="wp-models-model-gallery"><img src="<?php echo $uri . 'lib/timthumb/timthumb.php?src=' . $pic['uri'] . '&w=210&h=300'; ?>" /></a> -->
	<a href="<?php echo $pic['uri']; ?>" class="wp-models-model-gallery" title="<?php echo $pic['filename']; ?>"><img src="<?php echo $pic['uri']; ?>" title="<?php echo $pic['filename']; ?>" alt="<?php echo $pic['filename']; ?>" /></a>
	<!-- <p><?php echo $pic['filename']; ?></p> -->
</div>
<?php endforeach; ?>
<div style="clear: both;"></div>

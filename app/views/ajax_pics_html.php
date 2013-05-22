<?php
/**
 * File Description
 *
 * @package pkgtoken
 * @author authtoken
 * @copyright 2013
 * @version
 * @since
 */
?>
<div class="<?php echo $post_type; ?>-<?php echo $media_type; ?>">
	<h3><?php echo $title; ?></h3>
	<?php foreach( $post_media as $pic ):?>
		<a href="<?php echo $pic['uri']; ?>" class="wp-models-gallery" title="<?php the_title(); ?>"><img src="<?php echo $pic['uri']; ?>" class="<?php echo $post_type; ?>-pic" /></a>
	<?php endforeach; ?>
	<div style="clear: both;"></div>
</div>
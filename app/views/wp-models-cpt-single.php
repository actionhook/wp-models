<?php
/**
 * the single model view
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @author ActionHook.com
 * @version 0.1
 * @since 0.1
 */
?>
<p class="<?php echo $post->post_type; ?>-info"><?php echo $info; ?></p>
<div class="wp-models-toggle-container">
	<div class="<?php echo $post->post_type; ?>-content"><?php echo $content; ?></div>
</div>

<?php if( is_array( $post_pics ) ): ?>
	<div class="<?php echo $post->post_type; ?>-pics">
		<h3><?php the_title() ?> Pictures</h3>
		<?php foreach( $post_pics as $pic ):?>
			<a href="<?php echo $pic['uri']; ?>" class="wp-models-gallery" title="<?php the_title(); ?>"><img src="<?php echo $pic['uri']; ?>" class="<?php echo $post->post_type; ?>-pic" /></a>
		<?php endforeach; ?>
		<div style="clear: both;"></div>
	</div>
<?php endif; ?>
<?php if( is_array( $post_vids ) ):?>
	<div class="<?php echo $post->post_type; ?>-vids">
		<h3><?php the_title() ?> Videos</h3>
		<div class="<?php echo $post->post_type; ?>-vid is-splash color-light">
		<?php foreach( $post_vids as $vid ):?>
			<video>
				<source type="<?php echo $vid['mimetype']; ?>" src="<?php echo $vid['uri']; ?>" />
			</video>
		<?php endforeach; ?>
		</div>
		<div style="clear: both;"></div>
	</div>
<?php endif; ?>
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
<p class="wp-models-model-info"><?php echo $model_info; ?></p>
<div class="wp-models-toggle-container">
	<div class="wp-models-model-content"><?php echo $content; ?></div>
</div>

<?php if( is_array( $post_pics ) ): ?>
	<div class="wp-models-model-pics">
		<h3><?php the_title() ?> Pictures</h3>
		<?php foreach( $post_pics as $pic ):?>
			<a href="<?php echo $pic['uri']; ?>" class="wp-models-model-gallery" title="<?php the_title(); ?>"><img src="<?php echo $pic['uri']; ?>" class="wp-models-model-pic" /></a>
		<?php endforeach; ?>
		<div style="clear: both;"></div>
	</div>
<?php endif; ?>
<?php if( is_array( $post_vids ) ):?>
	<div class="wp-models-model-vids">
		<h3><?php the_title() ?> Videos</h3>
		<div class="wp-models-model-vid is-splash color-light">
		<?php foreach( $post_vids as $vid ):?>
			<video>
				<source type="<?php echo $vid['mimetype']; ?>" src="<?php echo $vid['uri']; ?>" />
			</video>
		<?php endforeach; ?>
		</div>
		<div style="clear: both;"></div>
	</div>
<?php endif; ?>
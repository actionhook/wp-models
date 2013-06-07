<?php
/**
 * The WP Models Model single view.
 *
 * @package WP Models\Views
 * @version 0.1
 * @author ActionHook.com <plugins@actionhook.com>
 * @since WP-Models 0.1
 */
?>
		<div class="wp-models-model-info">
			<?php wp_models_model_info() ?>
		</div>
		<div class="wp-models-toggle-container">
			<div class="wp-models-content">
				<?php wp_models_model_content(); ?>	
			</div>
		</div>
		
	<?php if( wp_models_have_pics() ): ?>
		<div class="wp-models-model-pics">
			<h3><?php the_title(); ?> Pictures</h3>
			<?php while ( wp_models_have_pics() ): wp_models_the_pic(); ?>
				<a href="<?php echo wp_models_media_permalink(); ?>" class="wp-models-gallery" title="<?php the_title(); ?>"><img src="<?php echo wp_models_media_permalink(); ?>" class="wp-models-model-pic" /></a>
			<?php endwhile; ?>
			<div style="clear: both;"></div>
		</div>
	<?php endif; ?>
	
	<?php if( wp_models_have_vids() ):?>
		<div class="wp-models-model-vids">
			<h3><?php the_title(); ?> Videos</h3>
			<?php while ( wp_models_have_vids() ): wp_models_the_vid(); ?>
			<div class="wp-models-vid is-splash color-light">
				<video controls>
					<source type="<?php wp_models_media_mimetype(); ?>" src="<?php echo wp_models_media_permalink(); ?>" />
				</video>
			</div>
			<?php endwhile; ?>
			<div style="clear: both;"></div>
		</div>
	<?php endif; ?>

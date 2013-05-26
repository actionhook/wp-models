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
		<div class="wp-models-shoot-info">
			<p>Age: <?php wp_models_model_age(); ?> | Height: <?php wp_models_model_height(); ?>  | Weight: <?php wp_models_model_weight(); ?>  | <?php wp_models_model_bust(); ?>-<?php wp_models_model_waist(); ?>-<?php wp_models_model_hips(); ?></p>
		</div>
		<div class="wp-models-toggle-container">
			<div class="wp-models-shoot-content">
				<?php wp_models_model_content(); ?>	
			</div>
		</div>
		
	<?php if( wp_models_have_pics() ): ?>
		<div class="wp-models-shoot-pics">
			<h3><?php the_title(); ?> Pictures</h3>
			<?php while ( wp_models_have_pics() ): wp_models_the_pic(); ?>
				<a href="<?php echo wp_models_media_permalink(); ?>" class="wp-models-gallery" title="<?php the_title(); ?>"><img src="<?php echo wp_models_media_permalink(); ?>" class="wp-models-shoot-pic" /></a>
			<?php endwhile; ?>
			<div style="clear: both;"></div>
		</div>
	<?php endif; ?>
	
	<?php if( wp_models_have_vids() ):?>
		<div class="wp-models-shoot-vids">
			<h3><?php the_title(); ?> Videos</h3>
			<div class="wp-models-shoot-vid is-splash color-light">
			<?php while ( wp_models_have_vids() ): wp_models_the_vid(); ?>
				<video controls>
					<source type="<?php wp_models_media_mimetype(); ?>" src="<?php echo wp_models_media_permalink(); ?>" />
				</video>
			<?php endwhile; ?>
			</div>
			<div style="clear: both;"></div>
		</div>
	<?php endif; ?>

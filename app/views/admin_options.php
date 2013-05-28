<?php
/**
 * The main options page view.
 *
 * @package WP Models\Views
 * @author Daryl Lozupone <daryl@actionhook.com>
 * @version 0.1
 * @since WP Base 0.1
 */
?>

<div class="wrap">
	<h2><?php echo $page['page_title'] ?></h2>
	<div id="wp-models-info">
		<p>We can put propaganda here. Perhaps the rss feed from our site.</p>
	</div>
	<div id="wp-models-settings-form">
		<form action="options.php" method="post">
				<?php
				foreach( $options as $key => $option):
					settings_fields( $option['option_group'] );
				endforeach;
				?>
			<fieldset>
				<?php do_settings_sections( $page['menu_slug'] ); ?>
				<input name='Submit' type='submit' value='<?php echo _x( 'Save Changes', 'text for the options page submit button', $this->txtdomain ); ?>' />
			</fieldset>
		</form>
	</div>
</div>
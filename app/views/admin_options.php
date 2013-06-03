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
	<div id="wp-models-info" clas="postbox">
		<h3 class="hndle"><?php _e( 'WP Models', $this->txtdomain ); ?></h3>
		<p><img src="http://actionhook.com/wp-content/uploads/2013/05/WP-Models-300x141.jpg" /></p>
		<div id="wp-models-free" class="wp-models-version">
			<p>You are currently running the Standard Version for free</p>
			<ul>
				<li>Add Models to your site</li>
				<li>Set featured image of your model</li>
				<li>Each Model can have multiple images</li>
				<li>Each Model can have multiple videos</li>
				<li>Add Model details like age, height and weight</li>
				<li>Integrate with your choice of membership plugin to allow protected content of your models.</li>
			</ul>
		</div>
		<div id="wp-models-pro" class="wp-models-version">
			<p><?php _e( 'Upgrade to the Pro Version for only $1,500,000 USD', $this->txtdomain ); ?></p>
			<ul>
				<li>All the features of our free plugin</li>
				<li>Add Model Shoots to your site</li>
				<li>Associate one or more models with a shoot</li>
				<li>Set featured image of your shoots</li>
				<li>Each shoot can have multiple images</li>
				<li>Each Shoot can have multiple videos</li>
				<li>Integrate with your choice of membership plugin to allow protected content of your shoots</li>
				<li>One year of support and updates.</li>
			</ul>
		</div>
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

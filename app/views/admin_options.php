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

<div class="wrap wp-models-options">
	<h2><?php echo $page['page_title'] ?></h2>
	<div id="wp-models-info" clas="postbox">
		<h3 class="hndle"><i class="icon-camera-retro icon-2x"></i>  <?php _e( 'WP Models', $this->txtdomain ); ?></h3>
		<div class="wp-models-logo"><img src="<?php echo trailingslashit( $this->uri ); ?>images/wp-models.jpg" alt="wp-models" width="300" height="141" /></div>
		<div id="wp-models-free" class="wp-models-version">
			<div class="free-title">You are currently running the Standard Version for free</div>
			<ul>
				<li><i class="icon-check-sign"></i> Add Models to your site</li></i>
				<li><i class="icon-check-sign"></i> Set featured image of your model</li>
				<li><i class="icon-check-sign"></i> Each Model can have multiple images</li>
				<li><i class="icon-check-sign"></i> Each Model can have multiple videos</li>
				<li><i class="icon-check-sign"></i> Add Model details like age, height and weight</li>
				<li><i class="icon-check-sign"></i> Integrate with your choice of membership plugin to allow protected content of your models.</li>
				<li><i class="icon-download-alt"> Upgrade Today!</i></li>
			</ul>
		</div>
		<div id="wp-models-pro" class="wp-models-version">
			<div class="pro-title"><a href="http://actionhook.com/downloads/wordpress-models-plugin-pro" target="_blank">Upgrade to the Pro Version for only $45 USD</a></div>
			<ul>
				<li><i class="icon-camera"></i> All the features of our free plugin</li>
				<li><i class="icon-camera"></i> Add Model Shoots to your site</li>
				<li><i class="icon-camera"></i> Associate one or more models with a shoot</li>
				<li><i class="icon-camera"></i> Set featured image of your shoots</li>
				<li><i class="icon-camera"></i> Each shoot can have multiple images</li>
				<li><i class="icon-camera"></i> Each Shoot can have multiple videos</li>
				<li><i class="icon-camera"></i> Integrate with your choice of membership plugin to allow protected content of your shoots</li>
				<li><i class="icon-camera"></i> One year of support and updates.</li>
			</ul>
		</div>
		<div class="wp-model-news"><h3>ActionHook News</h3><p><a href="http://actionhook.com"> - view all news <i class="icon-rss-sign"></i></a></p></div>
		<a href="https://twitter.com/actionhook" class="twitter-follow-button" data-show-count="false" data-lang="en">Follow @ActionHook</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
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

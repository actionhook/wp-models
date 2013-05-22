<?php
/**
 * The post vids admin view
 *
 * @package WP Models\Views
 * @author ActionHook.com <plugins@actionhook.com>
 * @version 0.1
 * @since WP Models 0.1
 */
?>

<h4><?php _e( 'Manage Vids:', $txtdomain ); ?></h4>
<?php foreach( $post_media as $key => $vid ):?>
<p><?php echo $vid['filename'];?></p>
<button type="button" class="wp-models-vid-delete" value="<?php echo $vid['filename'];?>">Delete</button>
<div class="wp-models-player is-splash custom1 functional">
	<video preload="none" controls>
		<source type="<?php echo $vid['mimetype']; ?>" src="<?php echo $vid['uri']; ?>"/>
	</video>
</div>
<?php endforeach; ?>
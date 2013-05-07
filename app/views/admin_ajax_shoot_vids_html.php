<?php
/**
 * The shoot vids view
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @author authtoken
 * @version
 * @since
 */
?>

<h4><?php _e( 'Manage Vids:', $txtdomain ); ?></h4>
<?php foreach( $shoot_media as $key => $vid ):?>
<p><?php echo $vid['filename'];?></p>
<button type="button" class="wp-models-shoot-vid-delete" value="<?php echo $vid['filename'];?>">Delete</button>
<div class="wp-models-player is-splash custom1 functional">
	<video preload="none">
		<source type="<?php echo $vid['mimetype']; ?>" src="<?php echo $vid['uri']; ?>"/>
	</video>
</div>
<?php endforeach; ?>
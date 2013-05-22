<?php
/**
 * The Shoot Models metabox view.
 *
 * This view renders a list of checkboxs with the model names. User can select the names of the models attached to this shoot.
 *
 * @package WP Models\Views
 * @version 0.1
 * @author ActionHopk.com <plugins@actionhook.com>
 * @since WP-Models 0.1
 */
echo $nonce;
foreach( $metabox['args']['models'] as $key => $model ):
	if( is_array( $metabox['args']['shoot_models'] ) )
		$checked = in_array( $key, $metabox['args']['shoot_models'] ) ? ' checked' : '';
?>
<p><input type="checkbox" name="wp-models-shoot-model[]" value="<?php echo $key; ?>"<?php echo $checked;?>> <?php echo $model; ?></p>
<?php endforeach; ?>
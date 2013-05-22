<?php

/**
 * The model details metabox view.
 *
 * @package WP Models\Views
 * @version 0.1
 * @author ActionHopk.com <plugins@actionhook.com>
 * @since WP-Models 0.1
 *
 */

echo $nonce;
?>

<p><label for="wp-models-model-age"><?php echo _x( 'Age', 'Model Age', $txtdomain ); ?></label>
<input type="text" name="wp-models-model-age" id="wp-models-model-age" value="<?php echo $metabox['args']['model_age']; ?>" /></p>
<p><label for="wp-models-model-height"><?php echo _x( 'Height', 'Model Height', $txtdomain ); ?></label>
<input type="text" name="wp-models-model-height" id="wp-models-model-height" value="<?php echo $metabox['args']['model_height']; ?>" /></p>
<p><label for="wp-models-model-weight"><?php echo _x( 'Weight', 'Model Weight', $txtdomain ); ?></label>
<input type="text" name="wp-models-model-weight" id="wp-models-model-weight" value="<?php echo $metabox['args']['model_weight']; ?>" /></p>
<p><label for="wp-models-model-bust"><?php echo _x( 'Bust', 'Model Bust', $txtdomain ); ?></label>
<input type="text" name="wp-models-model-bust" id="wp-models-model-bust" value="<?php echo $metabox['args']['model_bust']; ?>" /></p>
<p><label for="wp-models-model-waist"><?php echo _x( 'Waist', 'Model Waist', $txtdomain ); ?></label>
<input type="text" name="wp-models-model-waist" id="wp-models-model-waist" value="<?php echo $metabox['args']['model_waist']; ?>" /></p>
<p><label for="wp-models-model-hips"><?php echo _x( 'Hips', 'Model Hips', $txtdomain ); ?></label>
<input type="text" name="wp-models-model-hips" id="wp-models-model-hips" value="<?php echo $metabox['args']['model_hips']; ?>" /></p>

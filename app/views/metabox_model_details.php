<?php

/**
 * The model details metabox view.
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @version 0.1
 * @since WP-Models 0.1
 * @author Daryl Lozupone <dlozupone@renegadetechconsulting.com>
 *
 */

$astrological_signs = array( 
	array( 'value' => 'Aries', 'display' => _x( 'Aries', 'Astrological Sign', $txtdomain ) ),
	array( 'value' => 'Taurus', 'display' => _x( 'Taurus', 'Astrological Sign', $txtdomain ) ),
	array( 'value' => 'Gemini', 'display' => _x( 'Gemini', 'Astrological Sign', $txtdomain ) ),
	array( 'value' => 'Cancer', 'display' => _x( 'Cancer', 'Astrological Sign', $txtdomain ) ),
	array( 'value' => 'Leo', 'display' => _x( 'Leo', 'Astrological Sign', $txtdomain ) ),
	array( 'value' => 'Virgo', 'display' => _x( 'Virgo', 'Astrological Sign', $txtdomain ) ),
	array( 'value' => 'Libra', 'display' => _x( 'Libra', 'Astrological Sign', $txtdomain ) ),
	array( 'value' => 'Scorpio', 'display' => _x( 'Scorpio', 'Astrological Sign', $txtdomain ) ),
	array( 'value' => 'Sagittarius', 'display' => _x( 'Sagittarius', 'Astrological Sign', $txtdomain ) ),
	array( 'value' => 'Capricorn', 'display' => _x( 'Capricorn', 'Astrological Sign', $txtdomain ) ),
	array( 'value' => 'Aquarius', 'display' => _x( 'Aquarius', 'Astrological Sign', $txtdomain ) ),
	array( 'value' => 'Pisces', 'display' => _x( 'Pisces', 'Astrological Sign', $txtdomain ) )
);

echo $nonce;
?>

<p><label for="wp-models-model-age"><?php echo _x( 'Age', 'Model Age', $txtdomain ); ?></label>
<input type="text" name="wp-models-model-age" id="wp-models-model-age" value="<?php echo $metabox['args']['meta']['model_age']; ?>" /></p>
<p><label for="wp-models-model-height"><?php echo _x( 'Height', 'Model Height', $txtdomain ); ?></label>
<input type="text" name="wp-models-model-height" id="wp-models-model-height" value="<?php echo $metabox['args']['meta']['model_height']; ?>" /></p>
<p><label for="wp-models-model-weight"><?php echo _x( 'Weight', 'Model Weight', $txtdomain ); ?></label>
<input type="text" name="wp-models-model-weight" id="wp-models-model-weight" value="<?php echo $metabox['args']['meta']['model_weight']; ?>" /></p>
<p><label for="wp-models-model-bust"><?php echo _x( 'Bust', 'Model Bust', $txtdomain ); ?></label>
<input type="text" name="wp-models-model-bust" id="wp-models-model-bust" value="<?php echo $metabox['args']['meta']['model_bust']; ?>" /></p>
<p><label for="wp-models-model-waist"><?php echo _x( 'Waist', 'Model Waist', $txtdomain ); ?></label>
<input type="text" name="wp-models-model-waist" id="wp-models-model-waist" value="<?php echo $metabox['args']['meta']['model_waist']; ?>" /></p>
<p><label for="wp-models-model-hips"><?php echo _x( 'Hips', 'Model Hips', $txtdomain ); ?></label>
<input type="text" name="wp-models-model-hips" id="wp-models-model-hips" value="<?php echo $metabox['args']['meta']['model_hips']; ?>" /></p>
<!--
<p><label for="wp-models-model-sign"><?php _e( 'Model Sign', $txtdomain ); ?></label>
	<select name="wp-models-model-sign" id="wp-models-model-sign">
		<option value=''></option>
		<?php foreach( $astrological_signs as $sign ): ?>
			<option value="<?php echo $sign['value']; ?>" <?php echo $sign['value'] == $metabox['args']['model_sign'] ? 'selected' : '';?>><?php echo $sign['display']; ?></option>
		<?php endforeach; ?>
	</select>
</p>
-->
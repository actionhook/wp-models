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

/*
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
*/

echo $nonce;
?>

<p><label for="wp-models-model-age"><?php _e( 'Model Age', $txtdomain ); ?></label>
<input type="text" name="wp-models-model-age" id="wp-models-model-age" value="<?php echo $metabox['args']['model_age']; ?>" /></p>
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
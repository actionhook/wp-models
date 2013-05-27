<div id="wp-models-license-status">
	<button type="button" id="wp-models-activate-license-key"><?php _e( 'Activate License Key', $txtdomain ); ?></button>
	<img src="<?php echo admin_url( '/images/wpspin_light.gif' );?>" id='wp-models-spinner' />
	<span id='wp-models-license-status-message'><?php echo isset ( $message ) ? $message : '' ?></span>
</div>
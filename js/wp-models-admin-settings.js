jQuery(document).ready( function($){
	$('#wp-models-spinner').hide();
	$('#wp-models-activate-license-key').on( 'click', function() {
		wp_models_activate_license_key();
	});
	$('#wp-models-deactivate-license-key').on( 'click', function() {
		wp_models_deactivate_license_key();
	});
});

function wp_models_activate_license_key()
{
	if(jQuery('#wp-models-license-key').val() === '' ) {
		alert ( 'Please enter your license key.' );
	} else {
		
		jQuery('#wp-models-spinner').show();
		jQuery('#wp-models-license-status-message').html('');
		
		var data = {
			action: 'wp_models_activate_license_key',
			key: jQuery('#wp-models-license-key').val(),
			nonce: wpModelsL10n.nonce
		}
		
		jQuery.post( ajaxurl, data, function( response ){
			console.log(response);
			jQuery('#wp-models-license-status').html(response);
			jQuery('#wp-models-spinner').hide();
			
			jQuery('#wp-models-activate-license-key').on( 'click', function() {
				wp_models_activate_license_key();
			});
			jQuery('#wp-models-deactivate-license-key').on( 'click', function() {
				wp_models_deactivate_license_key();
			});
		});
	}
}

function wp_models_deactivate_license_key()
{
	jQuery('#wp-models-spinner').show();
	jQuery('#wp-models-license-status-message').html('');
	
	var data = {
		action: 'wp_models_deactivate_license_key',
		key: jQuery('#wp-models-license-key').val(),
		nonce: wpModelsL10n.nonce
	}
	
	jQuery.post( ajaxurl, data, function( response ){
		console.log(response);
		jQuery('#wp-models-license-status').html(response);
		jQuery('#wp-models-spinner').hide();
		
		jQuery('#wp-models-activate-license-key').on( 'click', function() {
			wp_models_activate_license_key();
		});
	});
}
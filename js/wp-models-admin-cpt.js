/**
 * The javascript for the cpt amdin pages with local upload storage.
 *
 * @package WP Models
 * @subpackage Custom Post Types
 * @author Actionhook.com <plugins@actionhook.com>
 * @version 0.1
 * @since 0.1
 * @todo internationalize the uploaders
 */

jQuery(document).ready(function()
{	
	//initialize all uploaders on the page
	//wp_models_init_uploaders();
	
	//init the specific uploader settings
	wp_models_init_uploader_pics();
	wp_models_init_uploader_vids();	
	
	//initialize the video players
	//wp_models_init_video_players();
	
	//load the ajax content
	wp_models_reload_pics();
	wp_models_reload_vids();
});

function wp_models_init_colorbox()
{
	jQuery('a.wp-models-model-gallery').colorbox({
		scalePhoto: 'true',
		maxWidth: '90%',
		maxHeight: '90%'
	});
}

function wp_models_init_video_players()
{
	// install flowplayer
	jQuery(".wp-models-player").flowplayer({
		swf: "http://releases.flowplayer.org/5.4.1/swf/flowplayer.swf"
	});
}

function wp_models_reload_pics() {
	var wpm_data = {
		action: 'wp_models_get_media',
		post: wpModelsL10n.post_id,
		post_type: wpModelsL10n.post_type,
		nonce: wpModelsL10n.nonce,
		media_type: 'pics'
	};
	
	jQuery.post( ajaxurl, wpm_data, function( response ) {
		jQuery('#wp-models-pics-container').html( response );
		//initialize the colorbox
		wp_models_init_colorbox();
		
		//bind the delete media buttons
		jQuery( '.wp-models-pic-delete' ).on( 'click', function(){
			jQuery(this).next().toggle();
			
			var wpm_data = {
				action: 'wp_models_delete_shoot_pic',
				nonce: wpModelsL10n.nonce,
				post_id: wpModelsL10n.post_id,
				post_type: wpModelsL10n.post_type,
				media_type: 'pics',
				media: jQuery( this ).val()
			};
			
			jQuery.post( ajaxurl, wpm_data, function( response ){
				wp_models_reload_pics();
			});
		});
	});
}

function wp_models_reload_vids() {
	var wpm_data = {
		action: 'wp_models_get_media',
		post: wpModelsL10n.post_id,
		post_type: wpModelsL10n.post_type,
		nonce: wpModelsL10n.nonce,
		media_type: 'vids'
	};
	
	jQuery.post( ajaxurl, wpm_data, function( response ) {
		jQuery('#wp-models-vids-container').html( response );
		
		jQuery( '.wp-models-vid-delete' ).on( 'click', function() {
			jQuery(this).next().toggle();
			
			var wpm_data = {
				action: 'wp_models_delete_shoot_vid',
				nonce: wpModelsL10n.nonce,
				post_id: wpModelsL10n.post_id,
				post_type: wpModelsL10n.post_type,
				media_type: 'vids',
				media: jQuery( this ).val()
			};
			
			jQuery.post( ajaxurl, wpm_data, function( response ){
				wp_models_reload_vids();
			});
		});
	});
}
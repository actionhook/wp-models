/**
 * The javascript for the cpt amdin pages with local upload storage.
 *
 * @package WP Models
 * @subpackage Custom Post Types
 * @author Actionhook.com <plugins@actoinhook.com>
 * @version 0.1
 * @since 0.1
 */

jQuery(document).ready(function()
{	
	//initialize all uploaders on the page
	wp_models_init_uploaders();
	
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
	jQuery('a.wp-models-model-gallery').colorbox();
}

function wp_models_init_uploaders()
{
	jQuery('.wp-models-plupload').pluploadQueue({
		// General settings
		url: wpModelsL10n.url,
		runtimes : 'html5,gears,silverlight,flash',
		chunk_size: '1mb',
		max_file_size : '600mb',
		multiple_queues: true,
		multipart_params: {
			post_id: wpModelsL10n.post_id,
			post_type: wpModelsL10n.post_type,
        	action: 'wp_models_media_upload',
        	nonce: wpModelsL10n.nonce
		},
		// Flash settings
		flash_swf_url : _wpPluploadSettings.defaults.flash_swf_url,	
		// Silverlight settings
		silverlight_xap_url : _wpPluploadSettings.defaults.silverlight_xap_url,
	});	
}

function wp_models_init_uploader_pics()
{
	var pics_uploader = jQuery(".wp-models-pics-uploader").pluploadQueue();
	
	pics_uploader.bind('BeforeUpload', function(up, file)
	{
        
        //extend the existing multipart_params
        jQuery.extend(up.settings.multipart_params, 
	        {
				'type': 'pics'
	    	}
    	); 
	});
		
	pics_uploader.bind('UploadComplete', function(up, file, response )
	{
		//reload the div containing the elements
		wp_models_reload_pics();
	});
}

function wp_models_init_uploader_vids()
{
	var vids_uploader = jQuery(".wp-models-vids-uploader").pluploadQueue();
	
	vids_uploader.bind('BeforeUpload', function(up, file) 
    {
		jQuery.extend(up.settings.multipart_params,
			{
	        	'type': 'vids'
	        }
        );
    });

	vids_uploader.bind('UploadComplete', function(up, file, response)
	{
		//reload the div containing the elements
		wp_models_reload_vids();
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
			var wpm_data = {
				action: 'wp_models_delete_shoot_pic',
				nonce: wpModelsL10n.nonce,
				post_id: wpModelsL10n.post_id,
				post_type: wpModelsL10n.post_type,
				media_type: 'pics',
				media: jQuery( this ).val()
			};
			jQuery(this).html( "Deleting..." );
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
			var wpm_data = {
				action: 'wp_models_delete_shoot_vid',
				nonce: wpModelsL10n.nonce,
				post_id: wpModelsL10n.post_id,
				post_type: wpModelsL10n.post_type,
				media_type: 'vids',
				media: jQuery( this ).val()
			};
			jQuery(this).html( "Deleting..." );
			jQuery.post( ajaxurl, wpm_data, function( response ){
				wp_models_reload_vids();
			});
		});
	});
}
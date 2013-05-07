jQuery(document).ready(function()
{	
	//initialize all uploaders on the page
	wp_models_init_uploaders();
	
	//init the pics uploader settings
	wp_models_init_uploader_pics();
	
	//init the vids uploader settings	
	wp_models_init_uploader_vids();	
	
	//initialize the video players
	//wp_models_init_video_players();
	
	//load the ajax content
	wp_models_reload_shoot_pics();
	wp_models_reload_shoot_vids();
});

function wp_models_init_uploaders()
{
	jQuery('.wp-models-plupload').pluploadQueue({
		// General settings
		url: wpModelsL10n.url,
		runtimes : 'html5,flash,silverlight',
		max_file_size : '200mb',
		multiple_queues: true,
		multipart_params: wp_models_get_multipart_params(),
		// Flash settings
		flash_swf_url : _wpPluploadSettings.defaults.flash_swf_url,	
		// Silverlight settings
		silverlight_xap_url : _wpPluploadSettings.defaults.silverlight_xap_url,
		
		//extend the uploader settings based on plugin settings
		preinit: {
			Init: function(up, info) {
				//console.log('[wpModelsL10n.storage == local]', wpModelsL10n.storage == 'local');
				if( wpModelsL10n.storage === 'local' ) 
				{
					var additional_settings = {
						//add chunking for local uploads
						chunk_size: '1mb'
					};
				}
				else if ( wpModelsL10n.storage === 'S3' )
				{
					//add global Amazon S3 parameters
					var additional_settings = {
						headers: {
							//set to use either Standard or Reduced Redundancy Storage
							'x-amz-storage-class': wpModelsL10n.storage_class
						}
					};
				}
				
				jQuery.extend(up.settings, additional_settings );
				
                console.log('[Init]', 'Info:', info, 'settings:', up.settings);
            }
		}
	});	
}

function wp_models_get_multipart_params()
{	
	if( wpModelsL10n.storage == 'S3' ) {
		var wpm_multipart = 
		{
			'key': '${filename}',
			'AWSAccessKeyId' : wpModelsL10n['accessKeyId'],
			'acl': 'public-read',
			'success_action_status': '201',
			'Filename': '${filename}', // adding this to keep consistency across the runtimes
			'policy': wpModelsL10n.policy,
			'signature': wpModelsL10n.signature,
			'success_action_status': '201'
		};
	} 
	else if ( wpModelsL10n.storage == 'local' )
	{
		var wpm_multipart = {
			'post_id': wpModelsL10n.post_id,
        	'action': 'wp_models_media_upload',
        	'nonce': wpModelsL10n['nonce']
		};
	}
	
	return wpm_multipart;
}

function wp_models_init_uploader_pics()
{
	var pics_uploader = jQuery("#wp-models-shoot-pics-uploader").pluploadQueue();
	
	pics_uploader.bind('BeforeUpload', function(up, file)
	{
		if( wpModelsL10n.storage == 'local' )
		{
			var additional_params = {
				'type': 'pics'
        	};
        }
        else if ( wpModelsL10n.storage == 'S3' )
        {        	
        	
        	var additional_params = {
        		//set the path for the uploaded file once in the bucket
        		//this works because jQuery.extend overwrites existing elements having the same name
        		'key': wpModelsL10n.post_id + '/pics/${filename}',
        		
        		//set the content type based upon the file extension
        		//this can be spoofed
    			'Content-Type': 'image/' + file.name.split('.').pop().toLowerCase()
    		};
        }
        
        //extend the existing multipart_params
        jQuery.extend(up.settings.multipart_params, additional_params );
        
        console.log('[UploadFile]', 'settings:', up.settings);   
	});
		
	pics_uploader.bind('UploadComplete', function(up, file, response )
	{	
		//reload the div containing the elements
		wp_models_reload_shoot_pics();
	});
}

function wp_models_init_uploader_vids()
{
	//var vids_uploader = jQuery("#wp-models-shoot-vids-uploader").pluploadQueue();
	var vids_uploader = jQuery("#wp-models-shoot-vids-uploader").pluploadQueue({
		filters: [
			{
				title: "Movie Files",
				extensions : "mp4,ogv,webm"
			}
		]
	});
	
	vids_uploader.bind('BeforeUpload', function(up, file) 
    {
        if( wpModelsL10n.storage == 'local' )
		{
			jQuery.extend(up.settings.multipart_params, {
	        	'type': 'vids'
	        });
        }
        else if ( wpModelsL10n.storage == 'S3' )
        {
        	jQuery.extend(up.settings.multipart_params, {
	        	'key': wpModelsL10n.post_id + '/vids/${filename}'
	        });
        }
        console.log('[UploadFile]', 'settings:', up.settings);
    });

	vids_uploader.bind('UploadComplete', function(up, file, response)
	{
		//reload the div containing the elements
		wp_models_reload_shoot_vids();
	});
	
}

function wp_models_init_video_players()
{
	// install flowplayer
	jQuery(".wp-models-player").flowplayer({
		swf: "http://releases.flowplayer.org/5.4.1/swf/flowplayer.swf"
	});
}

function wp_models_reload_shoot_pics() {
	var wpm_data = {
		action: 'wp_models_shoot_media',
		post: wpModelsL10n['post_id'],
		nonce: wpModelsL10n['nonce'],
		type: 'pics'
	};
	
	jQuery.post( ajaxurl, wpm_data, function( response ) {
		jQuery('#wp-models-shoot-pics-container').html( response );
	});
	
	jQuery( '.wp-models-shoot-pic-delete' ).live( 'click', function(){
		var wpm_data = {
			action: 'wp_models_delete_shoot_pic',
			nonce: wpModelsL10n['nonce'],
			post_id: wpModelsL10n['post_id'],
			media: jQuery( this ).val()
		};
		jQuery(this).html( "Deleting..." );
		jQuery.post( ajaxurl, wpm_data, function( response ){
			console.log( response );
			wp_models_reload_shoot_pics();
		});
	});
}

function wp_models_reload_shoot_vids() {
	var wpm_data = {
		action: 'wp_models_shoot_media',
		post: wpModelsL10n['post_id'],
		nonce: wpModelsL10n['nonce'],
		type: 'vids'
	};
	
	jQuery.post( ajaxurl, wpm_data, function( response ) {
		jQuery('#wp-models-shoot-vids-container').html( response );
	});
	
	jQuery( '.wp-models-shoot-vid-delete' ).live( 'click', function(){
		var wpm_data = {
			action: 'wp_models_delete_shoot_vid',
			nonce: wpModelsL10n['nonce'],
			post_id: wpModelsL10n['post_id'],
			media: jQuery( this ).val()
		};
		jQuery(this).html( "Deleting..." );
		jQuery.post( ajaxurl, wpm_data, function( response ){
			console.log( response );
			wp_models_reload_shoot_vids();
		});
	});
}
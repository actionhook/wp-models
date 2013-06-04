jQuery(document).ready(function()
{	
	//initialize all uploaders on the page
	wp_models_init_uploaders();
	
	//init the specific uploader settings
	wp_models_init_uploader_pics();
	wp_models_init_uploader_vids();	
});

function wp_models_init_uploaders()
{
	plupload.addI18n({
		'Select files' : wpModelsPluploadL10n.select_files,
		'Add files to the upload queue and click the start button.' : wpModelsPluploadL10n.queue_files,
		'Filename' : wpModelsPluploadL10n.filename,
		'Status' : wpModelsPluploadL10n.status,
		'Size' : wpModelsPluploadL10n.size,
		'Add files' : wpModelsPluploadL10n.add_files,
		'Stop current upload' : wpModelsPluploadL10n.stop_current_upload,
		'Start uploading queue' : wpModelsPluploadL10n.start_uploading_queue,
		'Uploaded %d/%d files': wpModelsPluploadL10n.uploaded_x_files,
		'N/A' : wpModelsPluploadL10n.n_a,
		'Drag files here.' : wpModelsPluploadL10n.drag_files_here,
		'File extension error.': wpModelsPluploadL10n.file_extension_error,
		'File size error.': wpModelsPluploadL10n.file_size_error,
		'Init error.': wpModelsPluploadL10n.init_error,
		'HTTP Error.': wpModelsPluploadL10n.http_error,
		'Security error.': wpModelsPluploadL10n.security_error,
		'Generic error.': wpModelsPluploadL10n.generic_error,
		'IO error.': wpModelsPluploadL10n.io_error,
		'Stop Upload': wpModelsPluploadL10n.stop_upload,
		'Start Upload': wpModelsPluploadL10n.start_upload,
		'%d files queued': wpModelsPluploadL10n.x_files_queued
	});
	
	jQuery('.wp-models-plupload').pluploadQueue({
		// General settings
		url: ajaxurl,
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
	
	jQuery.extend(pics_uploader.settings.filters, 
		[{
			title : "Image files", extensions : "jpg,gif,png"
		}]
	);
	
	pics_uploader.bind('Error', function(up, error){
		console.log(error);
	});
	
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
		console.log(response);
		//reload the div containing the elements
		wp_models_reload_pics();
	});
}

function wp_models_init_uploader_vids()
{
	var vids_uploader = jQuery(".wp-models-vids-uploader").pluploadQueue();
	
	jQuery.extend(vids_uploader.settings.filters, 
		[{
			title : "Movie files", extensions : "mp4,webm,ogv"
		}]
	);
	
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
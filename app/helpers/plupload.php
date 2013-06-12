<?php
/**
 * WP Plupload Helper functions
 *
 * @package Plupload
 * @author Daryl Lozupone <daryl@actionhook.com>
 * @copyright 2013
 * @version 0.1
 */
 
if( ! class_exists( 'Lib_Plupload' ) ):
	/**
	 * The Plupload Helper Function Library
	 *
	 * @package Plupload 
	 * @version 0.1
	 * @since 0.1
	 */
	class Lib_Plupload
	{
		public static function wp_internationalize( $txtdomain = '' )
		{
			return array(
				'select_files' 			=> __( 'Select Files', $txtdomain ),
				'queue_files' 			=> __( 'Add files to the upload queue and click the start button.', $txtdomain ),
				'filename'				=> __( 'Filename', $txtdomain ),
				'status' 				=> __( 'Status', $txtdomain ),
				'size'					=> __( 'Size', $txtdomain ),
				'add_files'				=> __( 'Add files', $txtdomain ),
				'stop_current_upload'	=> __( 'Stop current upload', $txtdomain ),
				'start_uploading_queue'	=> __( 'Start uploading queue', $txtdomain ),
				'uploaded_x_files'		=> __( 'Uploaded %d/%d files', $txtdomain ),
				'n_a'					=> __( 'N/A', $txtdomain ),
				'drag_files_here'		=> __( 'Drag files here.', $txtdomain ),
				'file_extension_error'	=> __( 'File extension error.', $txtdomain ),
				'file_size_error'		=> __( 'File size error.', $txtdomain ),
				'init_error'			=> __( 'Init error.', $txtdomain ),
				'http_error'			=> __( 'HTTP Error.', $txtdomain ),
				'security_error'		=> __( 'Security error.', $txtdomain ),
				'generic_error.'		=> __( 'Generic error', $txtdomain ),
				'io_error'				=> __( 'IO error.', $txtdomain ),
				'stop_upload'			=> __( 'Stop Upload', $txtdomain ),
				'start_upload'			=> __( 'Start Upload', $txtdomain ),
				'x_files_queued'		=> __( '%d files queued', $txtdomain ),
			);
		}
	}
endif;
?>
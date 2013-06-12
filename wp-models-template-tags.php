<?php

/**
 * The plugin template tags.
 *
 * @package WP Models\Template Tags
 * @version 0.1
 * @since WP Models 0.1
 * @author ActionHook.com
 * @todo implement echo/return option for all
 */


/**
 * Display or retrieve the unfiltered model content.
 *
 * This function may only be used within The Loop.
 *
 * @package WP Models\Template Tags
 * @param bool $echo Echo the string (TRUE) or return it (FALSE).
 * @return string $content The unfiltered post content.
 * @since WP Models 0.1
 */
function wp_models_model_content( $echo = true )
{
	global $post;
	
	if( $echo ) :
		echo do_shortcode( $post->model_content );
	else :
		return do_shortcode( $post->model_content );
	endif;
}

/**
 * Display or retrieve the model info line
 *
 * @package WP Models\Template Tags
 * @param bool $echo Echo the string (TRUE) or return it (FALSE).
 * @return string $info_line The model info.
 * @since 1.0.1
 */
function wp_models_model_info( $echo = true )
{
	global $post, $WP_Models;
	
	$txtdomain = $WP_Models->get_textdomain();
	$info_line = '';
	
	if( $post->model_age != '' ):
		$info_line .= sprintf( '%s: %s', __( 'Age', $txtdomain ), $post->model_age );
		$model_info['age'] = $post->model_age;
	endif;
	
	if( $post->model_height != '' ):
		if( $info_line != '' )
			$info_line .= ' | ';
		$info_line .= sprintf( '%s: %s', __( 'Height', $txtdomain ), $post->model_height );
		$model_info['height'] = $post->model_height;
	endif;
	
	if( $post->model_weight != '' ):
		if( $info_line != '' )
			$info_line .= ' | ';
		$info_line .= sprintf( '%s: %s', __( 'Weight', $txtdomain ), $post->model_height );
		$model_info['weight'] = $post->model_weight;
	endif;
	
	if( $post->model_bust != '' && $post->model_waist != '' && $post->model_hips != '' ):
		if( $info_line != '' )
			$info_line .= ' | ';
		$info_line .= sprintf( '%s-%s-%s', $post->model_bust, $post->model_waist, $post->model_hips );
		$model_info['bust'] = $post->model_bust;
		$model_info['waist'] = $post->model_waist;
		$model_info['hips'] = $post->model_hips;
	endif;
	
	$info_line = apply_filters( 'wpm_filter_model_info', $info_line, $model_info );
	
	if( $echo ):
		echo $info_line;
	else:
		return $info_line;
	endif;
}

/**
 * Display or retrieve the model's age
 *
 * This function may only be used within The Loop.
 *
 * @package WP Models\Template Tags
 * @param bool $echo Echo the string (TRUE) or return it (FALSE).
 * @return string $age
 * @since WP Models 0.1
 */
function wp_models_model_age( $echo = true )
{
	global $post;
	
	if( $echo ) :
		echo $post->model_age;
	else :
		return $post->model_age;
	endif;
}

/**
 * Display or retrieve the model's height
 *
 * This function may only be used within The Loop.
 *
 * @package WP Models\Template Tags
 * @param bool $echo Echo the string (TRUE) or return it (FALSE).
 * @return string $height
 * @since WP Models 0.1
 */
function wp_models_model_height( $echo = true )
{
	global $post;
	
	if( $echo ):
		echo $post->model_height;
	else:
		return $post->model_height;
	endif;
}

/**
 * Display or retrieve the model's weight
 *
 * This function may only be used within The Loop.
 *
 * @package WP Models\Template Tags
 * @param bool $echo Echo the string (TRUE) or return it (FALSE).
 * @return string $weight
 * @since WP Models 0.1
 */
function wp_models_model_weight( $echo = true )
{
	global $post;
	
	if( $echo ):
		echo $post->model_weight;
	else:
		return $post->model_weight;
	endif;
}

/**
 * Display or retrieve the model's bust measurement
 *
 * This function may only be used within The Loop.
 *
 * @package WP Models\Template Tags
 * @param bool $echo Echo the string (TRUE) or return it (FALSE).
 * @return string $bust
 * @since WP Models 0.1
 */
function wp_models_model_bust( $echo = true )
{
	global $post;
	
	if( $echo ):
		echo $post->model_bust;
	else:
		return $post->model_bust;
	endif;
}

/**
 * Display or retrieve the model's waist measurement
 *
 * This function may only be used within The Loop.
 *
 * @package WP Models\Template Tags
 * @param bool $echo Echo the string (TRUE) or return it (FALSE).
 * @return string $waist
 * @since WP Models 0.1
 */
function wp_models_model_waist( $echo = true )
{
	global $post;
	
	if( $echo ):
		echo $post->model_waist;
	else:
		return $post->model_waist;
	endif;
}

/**
 * Display or retrieve the model's hips measurement
 *
 * This function may only be used within The Loop.
 *
 * @package WP Models\Template Tags
 * @param bool $echo Echo the string (TRUE) or return it (FALSE).
 * @return string $hips
 * @since WP Models 0.1
 */
function wp_models_model_hips( $echo = true )
{
	global $post;
	
	if( $echo ):
		echo $post->model_hips;
	else:
		return $post->model_hips;
	endif;
}

/**
 * Determine whether the current post has any wp-model pictures attached to it.
 *
 * This function must be used within The Loop.
 * @package WP Models\Template Tags
 * @return bool TRUE for existence of pictures and not at the end of the array, otherwise FALSE
 * @since 0.1
 */
function wp_models_have_pics()
{
	global $post;
	
	if ( $post->model_current_pic + 1 < $post->model_pic_count ):
		return true;
	else:
		return false;
	endif;
}

/**
 * Used to iterate through the pics loop.
 *
 * When used in conjunction with a model post type, this will move to the next available attached picture.
 *
 * @package WP Models\Template Tags
 * @return string
 * @since 0.1
 */
function wp_models_the_pic()
{
	global $post;
	
	$post->model_current_pic++;
	$post->model_current_media = $post->model_pics[$post->model_current_pic];
			
	return $post->model_current_media;
}

/**
 * Determine whether the post has any wp-model videos attached to it.
 *
 * This function must be used within The Loop.
 * @package WP Models\Template Tags
 * @return bool TRUE for existence of videos and not at the end of the array, otherwise FALSE.
 * @since 0.1
 */
function wp_models_have_vids()
{
	global $post;

	if ( $post->model_current_vid + 1 < $post->model_vid_count ):
		return true;
	else:
		return false;
	endif;
}

/**
 * Used to iterate through the vids loop.
 *
 * When used in conjunction with a model post type, this will move to the next available attached video.
 *
 * @package WP Models\Template Tags
 * @return string
 * @since 0.1
 */
function wp_models_the_vid()
{
	global $post;
	
	$post->model_current_vid++;
	$post->model_current_media = $post->model_vids[$post->model_current_vid];
	
	return $post->model_current_media;
}

/**
 * Returns the link to the current media item.
 *
 * @package WP Models\Template Tags
 * @return string
 * @since 0.1
 */

function wp_models_media_permalink()
{
	global	$post;
	return $post->model_current_media['uri'];
}

/**
 * Display or retrieve the mimetype of the post's current wp-model media item.
 *
 * @package WP Models\Template Tags
 * @param bool $echo Echo the string (TRUE) or return it (FALSE)
 * @return string
 * @since 0.1
 */

function wp_models_media_mimetype( $echo = true )
{
	global	$post;
	
	if( $echo ):
		echo $post->model_current_media['mimetype'];
	else:
		return $post->model_current_media['mimetype'];
	endif;
}

/**
 * Display or retrieve the unfiltered shoot content.
 *
 * This function may only be used within The Loop.
 *
 * @package WP Models\Template Tags
 * @param bool $echo Echo the string (TRUE) or return it (FALSE).
 * @return string $content The unfiltered post content.
 * @since WP Models 0.1
 */
function wp_models_shoot_content( $echo = true )
{
	global $post;
	
	if( $echo ) :
		echo do_shortcode( $post->shoot_content );
	else :
		return do_shortcode( $post->shoot_content );
	endif;
}
?>
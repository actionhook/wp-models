<?php

/**
 * The plugin template tags.
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @version 0.1
 * @since WP Models 0.1
 * @author Daryl Lozupone <dlozupone@renegadetechconsulting.com>
 *
 */
 
/**
 * Echo the model's info line
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @return string
 * @since WP Models 0.1
 */
function wp_models_model_info()
{
	global $post, $WP_Models;
	echo WP_Models_CPT_Models_Model::get_model_info( $post->ID );
}

/**
 * Echo the model's age
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @return string
 * @since WP Models 0.1
 */
function wp_models_model_age()
{
	global $post, $WP_Models;
	echo WP_Models_CPT_Models_Model::get_model_age( $post->ID );
}

/**
 * Echo the model's height
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @return string
 * @since WP Models 0.1
 */
function wp_models_model_height()
{
	global $post;
	echo WP_Models_CPT_Models_Model::get_model_height( $post->ID );
}

/**
 * Echo the model's weight
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @return string
 * @since WP Models 0.1
 */
function wp_models_model_weight()
{
	global $post;
	echo WP_Models_CPT_Models_Model::get_model_weight( $post->ID );
}

/**
 * Echo the model's bust measurement
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @return string
 * @since WP Models 0.1
 */
function wp_models_model_bust()
{
	global $post;
	echo WP_Models_CPT_Models_Model::get_model_bust( $post->ID );
}

/**
 * Echo the model's waist measurement
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @return string
 * @since WP Models 0.1
 */
function wp_models_model_waist()
{
	global $post;
	echo WP_Models_CPT_Models_Model::get_model_waist( $post->ID );
}

/**
 * Echo the model's hips measurement
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @return string
 * @since WP Models 0.1
 */
function wp_models_model_hips()
{
	global $post;
	echo WP_Models_CPT_Models_Model::get_model_hips( $post->ID );
}

/**
 * Echo the shoot models
 *
 * @package pkgtoken
 * @subpackage subtoken
 * @return string A comma separated list of models in the shoot.
 * @since 0.1
 */
function wp_models_shoot_models()
{
	global $post;
	$shoot_models = $WP_Models->cpts['models']->get_shoot_models( $post->ID );
	
	foreach( $shoot_models as $key => $model ):
		$model_posts[] = get_post( $model );
		$html .= $model_post->post_title;
		if ( $key > 0 )
			$html .= ', ';
	endforeach;
	
	//allow the text to be filtered
	$html = apply_filters( 'wp_models_shoot_models', $model_posts, $separator );
	
	echo $html;
}
?>
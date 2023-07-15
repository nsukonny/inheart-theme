<?php

/**
 * Create new memory AJAX functions.
 *
 * @package WordPress
 * @subpackage inheart
 */

add_action( 'wp_ajax_ih_ajax_upload_main_photo', 'ih_ajax_upload_main_photo' );
add_action( 'wp_ajax_nopriv_ih_ajax_upload_main_photo', 'ih_ajax_upload_main_photo' );
/**
 * Upload main photo.
 *
 * @return void
 */
function ih_ajax_upload_main_photo(): void
{
	$cropped_image = $_FILES['cropped'];

	// If data is not set - send error.
	if( ! $cropped_image) wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	$attach_id = media_handle_upload( 'cropped', 0 );

	wp_send_json_success( [
		'msg' => esc_html__( 'Зображення успішно завантажено!', 'inheart' ),
		'img' => print_r( $attach_id, 1 )
	] );
}


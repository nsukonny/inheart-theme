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
	if( ! $cropped_image ) wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	$attach_id = media_handle_upload( 'cropped', 0 );

	wp_send_json_success( ['msg' => esc_html__( 'Зображення успішно завантажено!', 'inheart' )] );
}

add_action( 'wp_ajax_ih_ajax_upload_memory_photo', 'ih_ajax_upload_memory_photo' );
add_action( 'wp_ajax_nopriv_ih_ajax_upload_memory_photo', 'ih_ajax_upload_memory_photo' );
/**
 * Upload other photos.
 *
 * @return void
 */
function ih_ajax_upload_memory_photo(): void
{
	$image = $_FILES['file'];

	// If data is not set - send error.
	if( ! $image ) wp_send_json_error( ['success' => 0, 'msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	$allowed_image_types    = ['image/jpeg', 'image/png'];
	$max_image_size         = 50_000_000;

	// Check conditions for the image.
	if( ! in_array( $image['type'], $allowed_image_types ) || ( int ) $image['size'] > $max_image_size )
		wp_send_json_error( [
			'success'   => 0,
			'msg'       => esc_html__( 'Тільки ( png | jpg | jpeg ) меньше 50 мб', 'inheart' )
		] );

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	$attach_id = media_handle_upload( 'file', 0 );

	if( is_wp_error( $attach_id ) )
		wp_send_json_error( [
			'success'   => 0,
			'msg'       => esc_html__( 'Помилка під час завантаження зображення', 'inheart' )
		] );

	wp_send_json_success( [
		'success'   => 1,
		'msg'       => esc_html__( 'Зображення заваетажено успішно', 'inheart' ),
		'attach_id' => $attach_id,
		'url'       => wp_get_attachment_image_url( $attach_id, 'ih-profile-media' )
	] );
}

add_action( 'wp_ajax_ih_ajax_delete_memory_photo', 'ih_ajax_delete_memory_photo' );
add_action( 'wp_ajax_nopriv_ih_ajax_delete_memory_photo', 'ih_ajax_delete_memory_photo' );
/**
 * Delete specific photo by ID.
 *
 * @return void
 */
function ih_ajax_delete_memory_photo(): void
{
	$attach_id = ih_clean( $_POST['id'] );

	// If data is not set - send error.
	if( ! $attach_id ) wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	wp_delete_attachment( $attach_id, true );

	wp_send_json_success( ['msg' => esc_html__( 'Зображення видалено успішно', 'inheart' )] );
}


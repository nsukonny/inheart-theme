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
		'success'	=> 1,
		'msg'		=> esc_html__( 'Зображення завантажено успішно', 'inheart' ),
		'attachId'	=> $attach_id,
		'url'		=> wp_get_attachment_image_url( $attach_id, 'ih-profile-media' )
	] );
}

add_action( 'wp_ajax_ih_ajax_upload_memory_video', 'ih_ajax_upload_memory_video' );
add_action( 'wp_ajax_nopriv_ih_ajax_upload_memory_video', 'ih_ajax_upload_memory_video' );
/**
 * Upload video.
 *
 * @return void
 */
function ih_ajax_upload_memory_video(): void
{
	$file = $_FILES['file'];

	// If data is not set - send error.
	if( ! $file ) wp_send_json_error( ['success' => 0, 'msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	$allowed_video_types	= ['video/mp4', 'video/mpeg', 'video/x-msvideo'];
	$max_file_size			= 1_073_741_824;

	// Check conditions for the image.
	if( ! in_array( $file['type'], $allowed_video_types ) || ( int ) $file['size'] > $max_file_size )
		wp_send_json_error( [
			'success'   => 0,
			'msg'       => esc_html__( 'Тільки ( avi | mp4 | mpeg ) меньше 1 гб', 'inheart' )
		] );

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	$attach_id = media_handle_upload( 'file', 0 );

	if( is_wp_error( $attach_id ) )
		wp_send_json_error( [
			'success'   => 0,
			'msg'       => esc_html__( 'Помилка під час завантаження файлу', 'inheart' )
		] );

	$attach_url						= wp_get_attachment_url( $attach_id );
	$attach_path					= get_attached_file( $attach_id );
	$tmp_dir_name					= "{$file['name']}_{$file['size']}_" . time();
	$uploads_dir					= wp_get_upload_dir()['basedir'] . '/tmp_uploads/' . $tmp_dir_name;
	$uploads_url					= wp_get_upload_dir()['baseurl'] . '/tmp_uploads/' . $tmp_dir_name;
	$_SESSION['step4']['tmp_url']	= $uploads_url;
	$_SESSION['step4']['tmp_dir']	= $uploads_dir;
	$_SESSION['step4']['file_id']	= $attach_id;
	$binaries_arr					= [
		'ffmpeg.binaries'  => THEME_DIR . '/lib-php/ffmpeg.exe',
		'ffprobe.binaries' => THEME_DIR . '/lib-php/ffprobe.exe'
	];
	$ffprobe			= FFMpeg\FFProbe::create( $binaries_arr );
	$duration			= ( int ) $ffprobe->format( $attach_path )->get( 'duration' );
	$duration_percent	= $duration / 100;
	$ffmpeg				= FFMpeg\FFMpeg::create( $binaries_arr );
	$video				= $ffmpeg->open( $attach_path );
	$shots_arr			= [];

	// Create temp dir for the screenshots.
	if( ! file_exists( $uploads_dir ) ){
		if( ! wp_mkdir_p( $uploads_dir ) )
			wp_send_json_error( [
				'success'   => 0,
				'msg'       => esc_html__( 'Помилка під час створення директорії', 'inheart' )
			] );
	}

	// Get 3 screenshots between 15% and 85% of the duration.
	for( $i = $duration_percent * 15; $i <= $duration - $duration_percent * 15; $i += $duration_percent * 30 ){
		$sec = ( int ) $i;
		$video
			->frame( FFMpeg\Coordinate\TimeCode::fromSeconds( $sec ) )
			->save( $uploads_dir . "/shot-{$sec}.jpg" );
		$shots_arr[] = $uploads_url . "/shot-{$sec}.jpg";
	}

	$_SESSION['step4']['shots'] = $shots_arr;
	wp_send_json_success( [
		'success'	=> 1,
		'msg'		=> esc_html__( 'Файл завантажено успішно', 'inheart' ),
		'attachId'	=> $attach_id,
		'url'		=> $attach_url,
		'shots'		=> json_encode( $shots_arr )
	] );
}

add_action( 'wp_ajax_ih_ajax_delete_memory_photo', 'ih_ajax_delete_memory_photo' );
add_action( 'wp_ajax_nopriv_ih_ajax_delete_memory_photo', 'ih_ajax_delete_memory_photo' );
/**
 * Delete specific file by ID.
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

add_action( 'wp_ajax_ih_ajax_set_poster', 'ih_ajax_set_poster' );
add_action( 'wp_ajax_nopriv_ih_ajax_set_poster', 'ih_ajax_set_poster' );
/**
 * Set video poster.
 *
 * @return void
 */
function ih_ajax_set_poster(): void
{
	$src = ih_clean( $_POST['src'] );

	// If data is not set - send error.
	if( ! $src ) wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	$step_data		= $_SESSION['step4'] ?? null;
	$poster_name	= basename( $src );
	$poster_src		= null;

	// No file/screenshots data in current session.
	if( empty( $step_data ) )
		wp_send_json_error( ['msg' => esc_html__( 'Невірні дані файлу', 'inheart' )] );

	// Search for selected poster in tmp dir.
	foreach( $step_data['shots'] as $shot ){
		if( basename( $shot ) === $poster_name ){
			$poster_src = "{$step_data['tmp_url']}/" . $poster_name;
			break;
		}
	}

	if( ! $poster_src ) wp_send_json_error( ['msg' => esc_html__( 'Обраний скрін не існує', 'inheart' )] );

	$thumb_desc	= sprintf( esc_html__( 'Постер для файлу з ID: %d', 'inheart' ), $step_data['file_id'] );
	$poster_id	= media_sideload_image( $poster_src, $step_data['file_id'], $thumb_desc, 'id' );

	if( is_wp_error( $poster_id ) )
		wp_send_json_error( ['msg' => esc_html__( 'Не вдалося встановити обкладинку', 'inheart' )] );

	// Set new poster for the video.
	set_post_thumbnail( ( int ) $step_data['file_id'], $poster_id );
	// Delete tmp dir with the screenshots.
	ih_delete_folder( $step_data['tmp_dir'] );

	// Delete data from the session.
	unset( $_SESSION['step4']['file_id'] );
	unset( $_SESSION['step4']['tmp_dir'] );
	unset( $_SESSION['step4']['tmp_url'] );
	unset( $_SESSION['step4']['shots'] );

	wp_send_json_success( ['msg' => esc_html__( 'Обкладинку встановлено успішно', 'inheart' )] );
}


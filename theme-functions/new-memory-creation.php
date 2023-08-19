<?php

/**
 * Create new memory_page draft post on process start.
 *
 * @return void
 */
function ih_create_new_memory_page(): void
{
	// Memory page is already in creation process - exit.
	if( isset( $_SESSION['memory_page_id'] ) ) return;

	$author_id		= get_current_user_id();
	$memory_pages	= get_posts( [
		'post_type'		=> 'memory_page',
		'author'		=> get_current_user_id(),
		'post_status'	=> 'draft'
	] );

	// If current Author already has draft Memory page - set it in session and exit.
	if( ! empty( $memory_pages ) ){
		$_SESSION['memory_page_id'] = $memory_pages[0]->ID;
		return;
	}

	// Create new draft Memory page.
	$post_data	= [
		'post_title'	=> "Новий пост від Користувача з ID $author_id",
		'post_status'	=> 'draft',
		'post_type'		=> 'memory_page',
		'post_author'	=> $author_id
	];
	$post_id = wp_insert_post( wp_slash( $post_data ) );

	if( is_wp_error( $post_id ) ){
		esc_html_e( "Не вдалося створити нову сторінку пам'яті", 'inheart' );
		return;
	}

	$_SESSION['memory_page_id'] = $post_id;	// Remember page ID in session.
}

/**
 * Create new memory AJAX functions.
 *
 * @package WordPress
 * @subpackage inheart
 */

add_action( 'wp_ajax_ih_ajax_save_data_step_0', 'ih_ajax_save_data_step_0' );
/**
 * Step 0 - save data.
 *
 * @return void
 */
function ih_ajax_save_data_step_0(): void
{
	$step_data		= isset( $_POST['stepData'] ) ? json_decode( stripslashes( $_POST['stepData'] ), true ) : null;
	$memory_page_id	= $_SESSION['memory_page_id'] ?? null;

	if( ! $step_data || ! isset( $step_data['theme'] ) || ! $memory_page_id )
		wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	update_field( 'theme', $step_data['theme'], $_SESSION['memory_page_id'] );

	wp_send_json_success( ['msg' => esc_html__( 'Тему обрано успішно!', 'inheart' )] );
}

add_action( 'wp_ajax_ih_ajax_upload_main_photo', 'ih_ajax_upload_main_photo' );
/**
 * Upload main photo.
 *
 * @return void
 */
function ih_ajax_upload_main_photo(): void
{
	$cropped_image	= $_FILES['cropped'];
	$memory_page_id	= $_SESSION['memory_page_id'] ?? null;

	// If data is not set - send error.
	if( ! $cropped_image || ! $memory_page_id )
		wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	$attach_id							= media_handle_upload( 'cropped', $memory_page_id );
	$_SESSION['step1']['cropped_id']	= $attach_id;
	$_SESSION['step1']['cropped_url']	= wp_get_attachment_image_url( $attach_id, 'full' );

	wp_send_json_success( [
		'msg'			=> esc_html__( 'Зображення успішно завантажено!', 'inheart' ),
		'url'			=> $_SESSION['step1']['cropped_url'],
		'short_filename'=> ih_get_shorter_filename( basename( $_SESSION['step1']['cropped_url'] ) )
	] );
}

add_action( 'wp_ajax_ih_ajax_save_data_step_1', 'ih_ajax_save_data_step_1' );
/**
 * Step 1 - save data.
 *
 * @return void
 */
function ih_ajax_save_data_step_1(): void
{
	$step_data		= isset( $_POST['stepData'] ) ? json_decode( stripslashes( $_POST['stepData'] ), true ) : null;
	$memory_page_id	= $_SESSION['memory_page_id'] ?? null;

	if(
		! $step_data || ! $memory_page_id || ! $step_data['lang'] || ! $step_data['firstname'] ||
		! $step_data['lastname'] || ! $step_data['fathername'] || ! $step_data['date-of-birth'] ||
		! $step_data['date-of-death'] || ( ! $step_data['cropped'] && ! has_post_thumbnail( $memory_page_id ) )
	) wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	update_field( 'language', $step_data['lang'], $memory_page_id );
	update_field( 'first_name', $step_data['firstname'], $memory_page_id );
	update_field( 'last_name', $step_data['lastname'], $memory_page_id );
	update_field( 'middle_name', $step_data['fathername'], $memory_page_id );
	update_field( 'born_at', $step_data['date-of-birth'], $memory_page_id );
	update_field( 'died_at', $step_data['date-of-death'], $memory_page_id );

	// Set Memory page thumbnail.
	if(
		isset( $step_data['cropped'] ) && isset( $_SESSION['step1']['cropped_id'] ) &&
		$_SESSION['step1']['cropped_url'] === $step_data['cropped']
	) set_post_thumbnail( $memory_page_id, $_SESSION['step1']['cropped_id'] );

	wp_send_json_success( ['msg' => esc_html__( 'Дані Кроку 1 збережено успішно!', 'inheart' )] );
}

add_action( 'wp_ajax_ih_ajax_save_data_step_2', 'ih_ajax_save_data_step_2' );
/**
 * Step 2 - save data.
 *
 * @return void
 */
function ih_ajax_save_data_step_2(): void
{
	$step_data		= isset( $_POST['stepData'] ) ? json_decode( stripslashes( $_POST['stepData'] ), true ) : null;
	$memory_page_id	= $_SESSION['memory_page_id'] ?? null;
	$sections		= [];

	if( ! $memory_page_id || empty( $step_data ) )
		wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	foreach( $step_data as $key => $section )
		$sections[] = [
			'category'	=> $section['title'],
			'text'		=> $section['text'],
			'position'	=> $section['position'],
			'own_title'	=> ( bool ) $section['custom'],
			'index'		=> $key
		];

	update_field( 'biography_sections', $sections, $memory_page_id );

	wp_send_json_success( ['msg' => esc_html__( 'Дані Кроку 2 збережено успішно!', 'inheart' )] );
}

add_action( 'wp_ajax_ih_ajax_save_data_step_3', 'ih_ajax_save_data_step_3' );
/**
 * Step 3 - save data.
 *
 * @return void
 */
function ih_ajax_save_data_step_3(): void
{
	$step_data		= isset( $_POST['stepData'] ) ? json_decode( stripslashes( $_POST['stepData'] ), true ) : null;
	$memory_page_id	= $_SESSION['memory_page_id'] ?? null;

	if( ! $memory_page_id || ! isset( $step_data['epitaph'] ) )
		wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	update_field( 'epitaphy', $step_data['epitaph'], $memory_page_id );

	wp_send_json_success( ['msg' => esc_html__( 'Дані Кроку 3 збережено успішно!', 'inheart' )] );
}

add_action( 'wp_ajax_ih_ajax_upload_memory_photo', 'ih_ajax_upload_memory_photo' );
/**
 * Upload other photos.
 *
 * @return void
 */
function ih_ajax_upload_memory_photo(): void
{
	$image			= $_FILES['file'];
	$memory_page_id	= $_SESSION['memory_page_id'] ?? null;

	// If data is not set - send error.
	if( ! $image || ! $memory_page_id )
		wp_send_json_error( ['success' => 0, 'msg' => esc_html__( 'Невірні дані', 'inheart' )] );

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

	$attach_id = media_handle_upload( 'file', $memory_page_id );

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

add_action( 'wp_ajax_ih_ajax_upload_custom_poster', 'ih_ajax_upload_custom_poster' );
/**
 * Upload custom poster from device.
 *
 * @return void
 */
function ih_ajax_upload_custom_poster(): void
{
	$image = $_FILES['file'];

	// If data is not set - send error.
	if( ! $image ) wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	$allowed_image_types    = ['image/jpeg', 'image/png'];
	$max_image_size         = 5_000_000;

	// Check conditions for the image.
	if( ! in_array( $image['type'], $allowed_image_types ) || ( int ) $image['size'] > $max_image_size )
		wp_send_json_error( ['msg' => esc_html__( 'Тільки ( png | jpg | jpeg ) меньше 5 мб', 'inheart' )] );

	$filename	= ih_modify_filename( $image['name'] );
	$moved		= move_uploaded_file( $image['tmp_name'], "{$_SESSION['step4']['video']['tmp_dir']}/{$filename}" );

	if( ! $moved )
		wp_send_json_error( ['msg' => esc_html__( 'Помилка під час завантаження зображення', 'inheart' )] );

	$_SESSION['step4']['video']['shots'][] = "{$_SESSION['step4']['video']['tmp_url']}/{$filename}";
	wp_send_json_success( [
		'msg'	=> esc_html__( 'Зображення завантажено успішно', 'inheart' ),
		'url'	=> "{$_SESSION['step4']['video']['tmp_url']}/{$filename}"
	] );
}

add_action( 'wp_ajax_ih_ajax_upload_memory_video', 'ih_ajax_upload_memory_video' );
/**
 * Upload video.
 *
 * @return void
 */
function ih_ajax_upload_memory_video(): void
{
	$file			= $_FILES['file'];
	$memory_page_id	= $_SESSION['memory_page_id'] ?? null;

	// If data is not set - send error.
	if( ! $file || ! $memory_page_id )
		wp_send_json_error( ['success' => 0, 'msg' => esc_html__( 'Невірні дані', 'inheart' )] );

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

	$attach_id = media_handle_upload( 'file', $memory_page_id );

	if( is_wp_error( $attach_id ) )
		wp_send_json_error( [
			'success'   => 0,
			'msg'       => esc_html__( 'Помилка під час завантаження файлу', 'inheart' )
		] );

	$attach_path							= get_attached_file( $attach_id );
	$tmp_dir_name							= "{$file['size']}_" . time();
	$uploads_dir							= wp_get_upload_dir()['basedir'] . '/tmp_uploads/' . $tmp_dir_name;
	$uploads_url							= wp_get_upload_dir()['baseurl'] . '/tmp_uploads/' . $tmp_dir_name;
	$_SESSION['step4']['video']['tmp_url']	= $uploads_url;
	$_SESSION['step4']['video']['tmp_dir']	= $uploads_dir;
	$_SESSION['step4']['video']['file_id']	= $attach_id;
//	$binaries_arr							= [
//		'ffmpeg.binaries'  => THEME_DIR . '/lib-php/ffmpeg.exe',
//		'ffprobe.binaries' => THEME_DIR . '/lib-php/ffprobe.exe'
//	];
//	$ffprobe			= FFMpeg\FFProbe::create( $binaries_arr );
	$ffprobe			= FFMpeg\FFProbe::create();
	$duration			= ( int ) $ffprobe->format( $attach_path )->get( 'duration' );
	$duration_percent	= $duration / 100;
//	$ffmpeg				= FFMpeg\FFMpeg::create( $binaries_arr );
	$ffmpeg				= FFMpeg\FFMpeg::create();
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

	$_SESSION['step4']['video']['shots'] = $shots_arr;
	wp_send_json_success( [
		'success'	=> 1,
		'msg'		=> esc_html__( 'Файл завантажено успішно', 'inheart' ),
		'attachId'	=> $attach_id,
		'shots'		=> json_encode( $shots_arr )
	] );
}

add_action( 'wp_ajax_ih_ajax_delete_memory_photo', 'ih_ajax_delete_memory_photo' );
/**
 * Delete specific file by ID.
 *
 * @return void
 */
function ih_ajax_delete_memory_photo(): void
{
	$attach_id		= ih_clean( $_POST['id'] );
	$is_video		= ih_clean( $_POST['video'] );
	$memory_page_id	= $_SESSION['memory_page_id'] ?? null;

	// If data is not set - send error.
	if( ! $attach_id || ! $memory_page_id )
		wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	// If we are going to remove a video file - let's remove its attachments, like poster.
	if( $is_video ){
		$post = get_post( $attach_id );

		if( $post->post_type === 'attachment' ){
			$attachments = get_children( ['post_type' => 'attachment', 'post_parent' => $attach_id] );

			if( $attachments ){
				foreach( $attachments as $attachment ) wp_delete_attachment( $attachment->ID, true );
			}
		}
	}

	// Check if file is attached to current Memory page to avoid deletion of any file by any passed ID.
	$attachment_post	= get_post( $attach_id );
	$attachment_parent	= $attachment_post->post_parent;

	if( $attachment_parent != $memory_page_id )
		wp_send_json_error( ['msg' => __( "Файл не належить до цієї сторінки пам'яті", 'inheart' )] );

	wp_delete_attachment( $attach_id, true );
	wp_send_json_success( ['msg' => esc_html__( 'Файл видалено успішно', 'inheart' )] );
}

add_action( 'wp_ajax_ih_ajax_set_poster', 'ih_ajax_set_poster' );
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

	$step_data		= $_SESSION['step4']['video'] ?? null;
	$poster_name	= ih_modify_filename( basename( $src ) );
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
	set_post_thumbnail( $step_data['file_id'], $poster_id );
	$attach_id	= $step_data['file_id'];
	$attach_url	= wp_get_attachment_url( $attach_id );
	// Delete tmp dir with the screenshots.
	ih_delete_folder( $step_data['tmp_dir'] );

	// Delete data from the session.
	unset( $_SESSION['step4']['video'] );

	wp_send_json_success( [
		'msg'		=> esc_html__( 'Обкладинку встановлено успішно', 'inheart' ),
		'url'		=> $attach_url,
		'attachId'	=> $attach_id,
		'filename'	=> basename( get_attached_file( $attach_id ) ),
		'posterId'	=> $poster_id
	] );
}

add_action( 'wp_ajax_ih_ajax_save_data_step_4', 'ih_ajax_save_data_step_4' );
/**
 * Step 4 - save data.
 *
 * @return void
 */
function ih_ajax_save_data_step_4(): void
{
	$step_data		= isset( $_POST['stepData'] ) ? json_decode( stripslashes( $_POST['stepData'] ), true ) : null;
	$memory_page_id	= $_SESSION['memory_page_id'] ?? null;

	if( ! $memory_page_id || empty( $step_data['photos'] ) )
		wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	if( count( $step_data['photos'] ) < 4 )
		wp_send_json_error( ['msg' => esc_html__( 'Не меньше 4 фото', 'inheart' )] );

	update_field( 'photo', $step_data['photos'], $memory_page_id );

	// Videos.
	$videos = null;

	if( ! empty( $step_data['videos'] ) ){
		$videos = [];

		foreach( $step_data['videos'] as $video ){
			$file	= $video['id'];
			$poster	= $video['poster'];

			if( ! $file && ! $poster ) continue;

			$videos[] = ['file' => $file, 'poster' => $poster];
		}

		$videos = empty( $videos ) ? null : $videos;
	}

	update_field( 'video', $videos, $memory_page_id );

	// External links.
	$links = null;

	if( ! empty( $step_data['links'] ) ){
		$links = [];

		foreach( $step_data['links'] as $link ){
			$url		= $link['url'];
			$title		= $link['title'];
			$position	= $link['position'];

			// Save only if both fields are set.
			if( ! $url || ! $title ) continue;

			$links[] = [
				'url'		=> $link['url'],
				'title'		=> $link['title'],
				'position'	=> $position
			];
		}

		$links = empty( $links ) ? null : $links;
	}

	update_field( 'links', $links, $memory_page_id );

	wp_send_json_success( ['msg' => esc_html__( 'Дані Кроку 4 збережено успішно!', 'inheart' )] );
}

add_action( 'wp_ajax_ih_ajax_upload_video_link', 'ih_ajax_upload_video_link' );
/**
 * Step 4 - save video link.
 *
 * @return void
 */
function ih_ajax_upload_video_link(): void
{
	$link			= isset( $_POST['link'] ) ? esc_url( $_POST['link'] ) : '';
	$memory_page_id	= $_SESSION['memory_page_id'] ?? null;

	if( ! $link ) wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	$video_links	= get_field( 'video_links', $memory_page_id ) ?: [];
	$video_links[]	= ['url' => $link];
	update_field( 'video_links', $video_links, $memory_page_id );

	wp_send_json_success( ['msg' => esc_html__( 'Посилання на відео збережено успішно!', 'inheart' )] );
}

add_action( 'wp_ajax_ih_ajax_save_data_step_5', 'ih_ajax_save_data_step_5' );
/**
 * Step 5 - save data.
 *
 * @return void
 */
function ih_ajax_save_data_step_5(): void
{
	$step_data		= isset( $_POST['stepData'] ) ? json_decode( stripslashes( $_POST['stepData'] ), true ) : null;
	$memory_page_id	= $_SESSION['memory_page_id'] ?? null;

	if(
		! $step_data || ! $memory_page_id || ! $step_data['address'] || ! $step_data['detail_address'] ||
		! $step_data['longitude'] || ! $step_data['latitude'] || ! $step_data['how_to_find']
	) wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	update_field( 'address', $step_data['address'], $memory_page_id );
	update_field( 'detail_address', $step_data['detail_address'], $memory_page_id );
	update_field( 'longitude', $step_data['longitude'], $memory_page_id );
	update_field( 'latitude', $step_data['latitude'], $memory_page_id );
	update_field( 'how_to_find', $step_data['how_to_find'], $memory_page_id );

	wp_send_json_success( ['msg' => esc_html__( 'Дані Кроку 5 збережено успішно!', 'inheart' )] );
}


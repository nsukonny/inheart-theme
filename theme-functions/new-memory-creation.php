<?php

/**
 * Create new memory_page draft post on process start.
 *
 * @return void
 */
function ih_create_new_memory_page(): void
{
	// Memory page is already in creation process.
	if( isset( $_SESSION['memory_page_id'] ) ){
		$edit = ( isset( $_GET['edit'] ) && $_GET['edit'] == 1 ) ? 1 : null;

		$user_id	= get_current_user_id();
		$author_id	= ( int ) get_post_field ( 'post_author', $_SESSION['memory_page_id'] );

		// Current User is not an author of this memory page.
		if( $user_id !== $author_id ){
			unset( $_SESSION['edit_mode'] );
			unset( $_SESSION['memory_page_id'] );
		}else{
			// If this is an edit mode - do nothing.
			if( isset( $_SESSION['edit_mode'] ) && $edit ) return;
		}
	}

	// Otherwise - this is not an edit mode
	unset( $_SESSION['edit_mode'] );
	unset( $_SESSION['memory_page_id'] );

	$author_id		= get_current_user_id();
	$memory_pages	= get_posts( [
		'post_type'		=> 'memory_page',
		'author'		=> $author_id,
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
		_e( "Не вдалося створити нову сторінку пам'яті", 'inheart' );
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
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	update_field( 'theme', $step_data['theme'], $_SESSION['memory_page_id'] );
	$_SESSION['step0']			= [];
	$_SESSION['step0']['theme']	= $step_data['theme'];
	wp_send_json_success( ['msg' => __( 'Тему обрано успішно!', 'inheart' )] );
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
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	$attach_id							= media_handle_upload( 'cropped', $memory_page_id );
	$_SESSION['step1']['cropped_id']	= $attach_id;
	$_SESSION['step1']['cropped_url']	= wp_get_attachment_image_url( $attach_id, 'full' );

	wp_send_json_success( [
		'msg'			=> __( 'Зображення успішно завантажено!', 'inheart' ),
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

	if( ! $step_data || ! $memory_page_id )
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	if( $step_data['lang'] ) update_field( 'language', $step_data['lang'], $memory_page_id );
	if( $step_data['firstname'] ) update_field( 'first_name', $step_data['firstname'], $memory_page_id );
	if( $step_data['lastname'] ) update_field( 'last_name', $step_data['lastname'], $memory_page_id );
	if( $step_data['fathername'] ) update_field( 'middle_name', $step_data['fathername'], $memory_page_id );
	if( $step_data['date-of-birth'] ) update_field( 'born_at', $step_data['date-of-birth'], $memory_page_id );
	if( $step_data['date-of-death'] ) update_field( 'died_at', $step_data['date-of-death'], $memory_page_id );

	// Set Memory page thumbnail.
	if(
		isset( $step_data['cropped'] ) && isset( $_SESSION['step1']['cropped_id'] ) &&
		$_SESSION['step1']['cropped_url'] === $step_data['cropped']
	) set_post_thumbnail( $memory_page_id, $_SESSION['step1']['cropped_id'] );

	wp_send_json_success( ['msg' => __( 'Дані Кроку 1 збережено успішно!', 'inheart' )] );
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
	$sections		= $cto = $war = $last_fight = [];

	if( ! $memory_page_id || empty( $step_data ) )
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	foreach( $step_data as $key => $section ){
		if( isset( $section['isCto'] ) && $section['isCto'] == 1 ){
			$cto = [
				'text'		=> $section['text'],
				'photos'	=> $section['photos'] ?? []
			];
			continue;
		}

		if( isset( $section['isWar'] ) && $section['isWar'] == 1 ){
			$war = [
				'text'		=> $section['text'],
				'photos'	=> $section['photos'] ?? []
			];
			continue;
		}

		if( isset( $section['isLastFight'] ) && $section['isLastFight'] == 1 ){
			$last_fight = [
				'location'	=> $section['city'],
				'text'		=> $section['text']
			];
			continue;
		}

		$sections[] = [
			'category'	=> $section['title'],
			'text'		=> $section['text'],
			'position'	=> $section['position'],
			'own_title'	=> ( bool ) $section['custom'],
			'index'		=> $key,
			'photos'	=> $section['photos'] ?? []
		];
	}

	update_field( 'biography_sections', $sections, $memory_page_id );
	update_field( 'cto', $cto, $memory_page_id );
	update_field( 'war', $war, $memory_page_id );
	update_field( 'last_fight', $last_fight, $memory_page_id );

	wp_send_json_success( ['msg' => __( 'Дані Кроку 2 збережено успішно!', 'inheart' )] );
}

add_action( 'wp_ajax_ih_ajax_save_data_step_2-military', 'wp_ajax_ih_ajax_save_data_step_2_military' );
/**
 * Step 2 Military - save data.
 *
 * @return void
 */
function wp_ajax_ih_ajax_save_data_step_2_military(): void
{
	$step_data		= isset( $_POST['stepData'] ) ? json_decode( stripslashes( $_POST['stepData'] ), true ) : null;
	$memory_page_id	= $_SESSION['memory_page_id'] ?? null;

	if( ! $step_data || ! $memory_page_id )
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	if( $step_data['army'] ) update_field( 'army_type', $step_data['army'], $memory_page_id );
	if( $step_data['brigade'] ) update_field( 'brigade_type', $step_data['brigade'], $memory_page_id );
	if( $step_data['title'] ) update_field( 'military_title', $step_data['title'], $memory_page_id );

	if( isset( $step_data['position'] ) )
		update_field( 'military_position', $step_data['position'], $memory_page_id );

	if( isset( $step_data['call-sign'] ) )
		update_field( 'call_sign', $step_data['call-sign'], $memory_page_id );

	wp_send_json_success( ['msg' => __( 'Дані Кроку 1-1 (Військовий) збережено успішно!', 'inheart' )] );
}

add_action( 'wp_ajax_ih_ajax_load_cities_from_local_json', 'ih_ajax_load_cities_from_local_json' );
/**
 * Return cities list from local JSON file.
 *
 * @return void
 */
function ih_ajax_load_cities_from_local_json(): void
{
	if( ! $city = ih_clean( $_POST['city'] ) ?? null )
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	$city = mb_strtolower( $city );

	if( empty( $_SESSION['cities_json'] ) ){
		$res      = file_get_contents( get_template_directory() . '/src/api/ua_locations.json' );
		$res_body = json_decode( $res, true );

		if( empty( $res_body ) ) wp_send_json_error( ['msg' => __( 'Помилка', 'inheart' )] );

		$_SESSION['cities_json'] = $res_body;
	}else{
		$res_body = $_SESSION['cities_json'];
	}

	$cities = [];

	foreach( $res_body as $c ){
		if(
			! str_contains( mb_strtolower( $c['public_name']['uk'] ), mb_strtolower( $city ) ) ||
			( $c['type'] === 'DISTRICT' || $c['type'] === 'COMMUNITY' || $c['type'] === 'STATE' )
		) continue;

		$city_full_name = isset( $c['parent_id'] )
			? ih_get_city_parent( $c['parent_id'], $res_body, $c['public_name']['uk'] )
			: $c['public_name']['uk'];

		$cities[] = ['name' => $city_full_name];
	}

	wp_send_json_success( ['cities' => $cities] );
}

/**
 * Recursive get full name of the city with its district and so on.
 *
 * @param int    $parent_id
 * @param array  $cities
 * @param string $parent_full_name
 * @return string
 */
function ih_get_city_parent( int $parent_id, array $cities, string $parent_full_name = '' ): string
{
	foreach( $cities as $parent ){
		if( $parent['id'] !== $parent_id ) continue;

		$parent_full_name .= ', ' . $parent['public_name']['uk'];

		// ID 1 means Ukraine, don't need it in the name string.
		if( isset( $parent['parent_id'] ) && $parent['parent_id'] !== 1 )
			$parent_full_name = ih_get_city_parent( $parent['parent_id'], $cities, $parent_full_name );

		break;
	}

	return $parent_full_name;
}

add_action( 'wp_ajax_ih_ajax_save_data_step_3-military', 'wp_ajax_ih_ajax_save_data_step_3_military' );
/**
 * Step 3 Military - save data (it's empty for now).
 *
 * @return void
 */
function wp_ajax_ih_ajax_save_data_step_3_military(): void
{
	wp_send_json_success( ['msg' => __( 'Дані Кроку 1-2 (Військовий) збережено успішно!', 'inheart' )] );
}

add_action( 'wp_ajax_ih_ajax_filter_rewards', 'ih_ajax_filter_rewards' );
/**
 * Step 3 Military - filter rewards.
 *
 * @return void
 */
function ih_ajax_filter_rewards(): void
{
	$slug	= ih_clean( $_POST['slug'] );
	$s		= ih_clean( $_POST['s'] );

	$args = [
		'post_type'		=> 'reward',
		'numberposts'	=> -1,
		'post_status'	=> 'publish',
		's'				=> $s
	];
	$res = '';

	if( $slug ){
		$args['rewards']	= $slug;
		$rewards			= get_posts( $args );

		if( empty( $rewards ) ) wp_send_json_success( ['structure' => __( 'Нагороди за цими критеріями не знайдені', 'inheart' )] );

		$term	= get_term_by( 'slug', $slug, 'rewards' );
		$res	.= '<h4>' . esc_html( $term->name ) . '</h4><div class="rewards-list flex flex-wrap align-start">';

		foreach( $rewards as $reward )
			$res .= ih_load_template_part( 'components/cards/reward/preview', null, ['id' => $reward->ID] );

		$res .= '</div>';
	}else{
		$rewards_types = get_terms( ['taxonomy' => 'rewards', 'hide_empty' => true] );

		if( $rewards_types && ! is_wp_error( $rewards_types ) ){
			foreach( $rewards_types as $type ){
				$args['rewards']	= $type->slug;
				$rewards			= get_posts( $args );

				if( empty( $rewards ) ) continue;

				$res .= '<h4>' . esc_html( $type->name ) . '</h4><div class="rewards-list flex flex-wrap align-start">';

				foreach( $rewards as $reward )
					$res .= ih_load_template_part( 'components/cards/reward/preview', null, ['id' => $reward->ID] );

				$res .= '</div>';
			}
		}
	}

	wp_send_json_success( ['structure' => $res] );
}

add_action( 'wp_ajax_ih_ajax_add_reward', 'ih_ajax_add_reward' );
/**
 * Step 3 Military - add reward.
 *
 * @return void
 */
function ih_ajax_add_reward(): void
{
	$memory_page_id	= $_SESSION['memory_page_id'] ?? null;
	$reward_id		= ih_clean( $_POST['id'] );
	$custom_name	= ih_clean( $_POST['reward-custom'] );
	$edict			= ih_clean( $_POST['edict'] );
	$number			= ih_clean( $_POST['reward-number'] );
	$date			= ih_clean( $_POST['reward-date'] );
	$for			= ih_clean( $_POST['reward-for-what'] );
	$posthumously	= ih_clean( $_POST['posthumously'] );

	if( ! $memory_page_id || ( ! $reward_id && ! $custom_name ) || ( ! $edict && ! $number && ! $date && ! $for ) )
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	$rewards	= get_field( 'rewards', $memory_page_id );
	$updated	= false;

	if( empty( $rewards ) ){
		$rewards = [];
	}else{
		foreach( $rewards as $key => $r ){
			$r_id = $r['reward_id'];

			if( $r_id == $reward_id ){
				$rewards[$key] = [
					'reward_custom'			=> !! $custom_name,
					'reward_custom_name'	=> $custom_name,
					'reward_id'				=> $reward_id,
					'edict'					=> $edict,
					'reward_number'			=> $number,
					'reward_date'			=> $date,
					'for_what'				=> $for,
					'posthumously'			=> !! $posthumously
				];
				$updated = true;
				break;
			}
		}
	}

	if( ! $updated ){
		$rewards[] = [
			'reward_custom'			=> !! $custom_name,
			'reward_custom_name'	=> $custom_name,
			'reward_id'				=> $reward_id ?: mt_rand( 1000, 9999 ) . mt_rand( 1000, 9999 ) . mt_rand( 1000, 9999 ),
			'edict'					=> $edict,
			'reward_number'			=> $number,
			'reward_date'			=> $date,
			'for_what'				=> $for,
			'posthumously'			=> !! $posthumously
		];
	}

	update_field( 'rewards', $rewards, $memory_page_id );

	// Get all rewards to show on the frontend.
	$res = '';
	foreach( $rewards as $r ){
		$part_name = $r['reward_custom'] ? 'custom' : 'full';

		$res .= ih_load_template_part( 'components/cards/reward/preview', $part_name, [
			'id'		=> $r['reward_id'],
			'reward'	=> $r
		] );
	}

	wp_send_json_success( [
		'msg'		=> __( 'Нагороду успішно додано', 'inheart' ),
		'rewards'	=> $res
	] );
}

add_action( 'wp_ajax_ih_ajax_delete_reward', 'ih_ajax_delete_reward' );
/**
 * Step 3 Military - delete reward.
 *
 * @return void
 */
function ih_ajax_delete_reward(): void
{
	$memory_page_id	= $_SESSION['memory_page_id'] ?? null;
	$reward_id		= ih_clean( $_POST['id'] );

	if( ! $memory_page_id || ! $reward_id )
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	if( ! $rewards = get_field( 'rewards', $memory_page_id ) )
		wp_send_json_error( ['msg' => __( 'Нагород немає', 'inheart' )] );

	$rewards_upd = array_filter( $rewards, fn( $reward ) => $reward['reward_id'] != $reward_id );

	update_field( 'rewards', $rewards_upd, $memory_page_id );

	wp_send_json_success( ['msg' => __( 'Нагороду успішно видалено', 'inheart' )] );
}

add_action( 'wp_ajax_ih_ajax_save_data_step_3', 'ih_ajax_save_data_step_3' );
/**
 * Step 3 - save data.
 *
 * @return void
 */
function ih_ajax_save_data_step_3(): void
{
	$step_data			= isset( $_POST['stepData'] ) ? json_decode( stripslashes( $_POST['stepData'] ), true ) : null;
	$memory_page_id		= $_SESSION['memory_page_id'] ?? null;
	$epitaph			= isset( $step_data['epitaph'] ) ? ih_clean( $step_data['epitaph'] ) : '';
	$epitaph_lastname	= isset( $step_data['epitaph-lastname'] ) ? ih_clean( $step_data['epitaph-lastname'] ) : '';
	$epitaph_firstname	= isset( $step_data['epitaph-firstname'] ) ? ih_clean( $step_data['epitaph-firstname'] ) : '';
	$epitaph_role		= isset( $step_data['epitaph-role'] ) ? ih_clean( $step_data['epitaph-role'] ) : '';

	if( ! $memory_page_id )
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	update_field( 'epitaphy', $epitaph, $memory_page_id );
	update_field( 'epitaph_lastname', $epitaph_lastname, $memory_page_id );
	update_field( 'epitaph_firstname', $epitaph_firstname, $memory_page_id );
	update_field( 'epitaph_role', $epitaph_role, $memory_page_id );

	wp_send_json_success( ['msg' => __( 'Дані Кроку 3 збережено успішно!', 'inheart' )] );
}

add_action( 'wp_ajax_ih_ajax_upload_section_photo', 'ih_ajax_upload_section_photo' );
/**
 * Step 3 - upload bio sections photo.
 *
 * @return void
 */
function ih_ajax_upload_section_photo(): void
{
	$image			= $_FILES['file'];
	$section_id		= $_FILES['id'];
	$memory_page_id	= $_SESSION['memory_page_id'] ?? null;

	// If data is not set - send error.
	if( ! $image || ! $memory_page_id || ( ! $section_id && $section_id != 0 ) )
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	$allowed_image_types    = ['image/jpeg', 'image/png'];
	$max_image_size         = 10_485_760;   // 10 Mb

	// Check conditions for the image.
	if( ! in_array( $image['type'], $allowed_image_types ) || ( int ) $image['size'] > $max_image_size )
		wp_send_json_error( ['msg' => __( 'Тільки ( png | jpg | jpeg ) меньше 10 мб', 'inheart' )] );

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	$attach_id = media_handle_upload( 'file', $memory_page_id );

	if( is_wp_error( $attach_id ) )
		wp_send_json_error( ['msg' => __( 'Помилка під час завантаження зображення', 'inheart' )] );

	$image = ih_load_template_part( 'components/memory-page/bio-section-image', null, ['photo' => $attach_id] );
	wp_send_json_success( [
		'msg'		=> __( 'Зображення завантажено успішно', 'inheart' ),
		'image'		=> $image,
		'attach_id'	=> $attach_id
	] );
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
		wp_send_json_error( ['success' => 0, 'msg' => __( 'Невірні дані', 'inheart' )] );

	$is_expanded = get_field( 'is_expanded', $memory_page_id );
	$photos      = get_field( 'photo', $memory_page_id ) ?: [];

	// Simple page can attach only <= 4 photos.
	if( ! $is_expanded && count( $photos ) >= 4 )
		wp_send_json_error( [
			'success' => 0,
			'msg'     => __( 'Ви не можете завантажити більше зображень у цьому тарифі', 'inheart' )
		] );

	$allowed_image_types    = ['image/jpeg', 'image/png'];
	$max_image_size         = 10_485_760;   // 10 Mb

	// Check conditions for the image.
	if( ! in_array( $image['type'], $allowed_image_types ) || ( int ) $image['size'] > $max_image_size )
		wp_send_json_error( [
			'success'   => 0,
			'msg'       => __( 'Тільки ( png | jpg | jpeg ) меньше 10 мб', 'inheart' )
		] );

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	$attach_id = media_handle_upload( 'file', $memory_page_id );

	if( is_wp_error( $attach_id ) )
		wp_send_json_error( [
			'success'   => 0,
			'msg'       => __( 'Помилка під час завантаження зображення', 'inheart' )
		] );

	wp_send_json_success( [
		'success'	=> 1,
		'msg'		=> __( 'Зображення завантажено успішно', 'inheart' ),
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
	if( ! $image ) wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	$allowed_image_types    = ['image/jpeg', 'image/png'];
	$max_image_size         = 10_485_760;   // 10 Mb

	// Check conditions for the image.
	if( ! in_array( $image['type'], $allowed_image_types ) || ( int ) $image['size'] > $max_image_size )
		wp_send_json_error( ['msg' => __( 'Тільки ( png | jpg | jpeg ) меньше 10 мб', 'inheart' )] );

	$filename	= ih_modify_filename( $image['name'] );
	$moved		= move_uploaded_file( $image['tmp_name'], "{$_SESSION['step4']['video']['tmp_dir']}/$filename" );

	if( ! $moved )
		wp_send_json_error( ['msg' => __( 'Помилка під час завантаження зображення', 'inheart' )] );

	$_SESSION['step4']['video']['shots'][] = "{$_SESSION['step4']['video']['tmp_url']}/$filename";
	wp_send_json_success( [
		'msg'	=> __( 'Зображення завантажено успішно', 'inheart' ),
		'url'	=> "{$_SESSION['step4']['video']['tmp_url']}/$filename"
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
		wp_send_json_error( ['success' => 0, 'msg' => __( 'Невірні дані', 'inheart' )] );

	$is_expanded = get_field( 'is_expanded', $memory_page_id );

	// Simple page can't upload videos.
	if( ! $is_expanded )
		wp_send_json_error( [
			'success' => 0,
			'msg' => __( 'Ви не можете завантажувати відео у цьому тарифі', 'inheart' )
		] );

	$allowed_video_types	= ['video/mp4', 'video/mpeg', 'video/quicktime', 'video/x-msvideo'];
	$max_file_size			= 104_857_600;  // 100 Mb

	// Check conditions for the image.
    if ( ! in_array($file['type'], $allowed_video_types) || ( int ) $file['size'] > $max_file_size) {
        wp_send_json_error([
            'success' => 0,
            'msg'     => __('Тільки ( avi | mp4 | mpeg | mov ) меньше 100 мб', 'inheart')
        ]);
    }

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	$attach_id = media_handle_upload( 'file', $memory_page_id );

	if( is_wp_error( $attach_id ) )
		wp_send_json_error( [
			'success'   => 0,
			'msg'       => __( 'Помилка під час завантаження файлу', 'inheart' )
		] );

	$attach_path							= get_attached_file( $attach_id );
	$tmp_dir_name							= "{$file['size']}_" . time();
	$uploads_dir							= wp_get_upload_dir()['basedir'] . '/tmp_uploads/' . $tmp_dir_name;
	$uploads_url							= wp_get_upload_dir()['baseurl'] . '/tmp_uploads/' . $tmp_dir_name;
	$_SESSION['step4']['video']['tmp_url']	= $uploads_url;
	$_SESSION['step4']['video']['tmp_dir']	= $uploads_dir;
	$_SESSION['step4']['video']['file_id']	= $attach_id;

	$ffprobe			= FFMpeg\FFProbe::create();
	$duration			= ( int ) $ffprobe->format( $attach_path )->get( 'duration' );
	$duration_percent	= $duration / 100;
	$ffmpeg				= FFMpeg\FFMpeg::create();
	$video				= $ffmpeg->open( $attach_path );
	$shots_arr			= [];

	// Create temp dir for the screenshots.
	if( ! file_exists( $uploads_dir ) ){
		if( ! wp_mkdir_p( $uploads_dir ) )
			wp_send_json_error( [
				'success'   => 0,
				'msg'       => __( 'Помилка під час створення директорії', 'inheart' )
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
		'msg'		=> __( 'Файл завантажено успішно', 'inheart' ),
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
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

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
	wp_send_json_success( ['msg' => __( 'Файл видалено успішно', 'inheart' )] );
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
	if( ! $src ) wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	$step_data		= $_SESSION['step4']['video'] ?? null;
	$poster_name	= ih_modify_filename( basename( $src ) );
	$poster_src		= null;

	// No file/screenshots data in current session.
	if( empty( $step_data ) )
		wp_send_json_error( ['msg' => __( 'Невірні дані файлу', 'inheart' )] );

	// Search for selected poster in tmp dir.
	foreach( $step_data['shots'] as $shot ){
		if( basename( $shot ) === $poster_name ){
			$poster_src = "{$step_data['tmp_url']}/" . $poster_name;
			break;
		}
	}

	if( ! $poster_src ) wp_send_json_error( ['msg' => __( 'Обраний скрін не існує', 'inheart' )] );

	$thumb_desc	= sprintf( __( 'Постер для файлу з ID: %d', 'inheart' ), $step_data['file_id'] );
	$poster_id	= media_sideload_image( $poster_src, $step_data['file_id'], $thumb_desc, 'id' );

	if( is_wp_error( $poster_id ) )
		wp_send_json_error( ['msg' => __( 'Не вдалося встановити обкладинку', 'inheart' )] );

	// Set new poster for the video.
	set_post_thumbnail( $step_data['file_id'], $poster_id );
	$attach_id	= $step_data['file_id'];
	$attach_url	= wp_get_attachment_url( $attach_id );
	// Delete tmp dir with the screenshots.
	ih_delete_folder( $step_data['tmp_dir'] );

	// Delete data from the session.
	unset( $_SESSION['step4']['video'] );

	wp_send_json_success( [
		'msg'		=> __( 'Обкладинку встановлено успішно', 'inheart' ),
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
	$step_data      = isset( $_POST['stepData'] ) ? json_decode( stripslashes( $_POST['stepData'] ), true ) : null;
	$memory_page_id = $_SESSION['memory_page_id'] ?? null;
	$photos         = $step_data['photos'] ?? [];

	if( ! $memory_page_id )
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	$is_expanded = get_field( 'is_expanded', $memory_page_id );

	// Simple page can attach only <= 4 photos.
	if( ( ! $is_expanded && count( $photos ) <= 4 ) || $is_expanded )
		update_field( 'photo', $photos, $memory_page_id );

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

	update_field( 'field_638b12fcbe72b', $videos, $memory_page_id );

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

	if( $is_expanded ) update_field( 'field_63a16b6567b55', $links, $memory_page_id );

	wp_send_json_success( ['msg' => __( 'Дані Кроку 4 збережено успішно!', 'inheart' )] );
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

	if( ! $link ) wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	$video_links	= get_field( 'video_links', $memory_page_id ) ?: [];
	$video_links[]	= ['url' => $link];
	update_field( 'video_links', $video_links, $memory_page_id );

	wp_send_json_success( ['msg' => __( 'Посилання на відео збережено успішно!', 'inheart' )] );
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

	if( ! $memory_page_id )
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	// Update Memory page fields.
	update_field( 'address', $step_data['address'], $memory_page_id );
	if( $step_data['detail_address'] ) update_field( 'detail_address', $step_data['detail_address'], $memory_page_id );
	if( $step_data['longitude'] ) update_field( 'longitude', $step_data['longitude'], $memory_page_id );
	if( $step_data['latitude'] ) update_field( 'latitude', $step_data['latitude'], $memory_page_id );
	if( $step_data['how_to_find'] ) update_field( 'how_to_find', $step_data['how_to_find'], $memory_page_id );

	// This is the last step. Clean session data and publish memory page.
	unset( $_SESSION['memory_page_id'] );
	unset( $_SESSION['edit_mode'] );
	unset( $_SESSION['step0'] );
	unset( $_SESSION['step1'] );
	unset( $_SESSION['step4'] );
	$first_name		= get_field( 'first_name', $memory_page_id );
	$last_name		= get_field( 'last_name', $memory_page_id );
	$middle_name	= get_field( 'middle_name', $memory_page_id );
	$born_at		= ih_convert_input_date( get_field( 'born_at', $memory_page_id ), 'dots' );
	$died_at		= ih_convert_input_date( get_field( 'died_at', $memory_page_id ), 'dots' );
	$new_title		= "$last_name $first_name $middle_name, " . $born_at . '-' . $died_at;
	$post_id		= wp_update_post( [
		'ID'			=> $memory_page_id,
		'post_name'		=> $memory_page_id,
		'post_title'	=> $new_title,
		'post_status'	=> 'publish'
	] );

	$profile_url = get_the_permalink( pll_get_post( ih_get_profile_page_id() ) );
	wp_send_json_success( [
		'msg'		=> __( 'Дані Кроку 5 збережено успішно!', 'inheart' ),
		'redirect'	=> $profile_url,
		'link'		=> get_the_permalink( $post_id ),
		'qr_link'	=> $profile_url . "?expand=$post_id"
	] );
}


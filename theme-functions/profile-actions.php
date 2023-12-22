<?php

/**
 * Check if there are any errors in the fields.
 *
 * @param int $user_id
 * @param array $data	Form fields data.
 * @return array		Array of fields names with errors. Empty array if there are no errors.
 */
function ih_check_profile_settings_form( int $user_id, array $data ): array
{
	$first_name		= ih_clean( $data['firstname'] );
	$last_name		= ih_clean( $data['lastname'] );
	$email			= ih_clean( $data['email'] );
	$pass			= ih_clean( $data['pass'] );
	$new_pass		= ih_clean( $data['new-pass'] );
	$confirm_pass	= ih_clean( $data['confirm-pass'] );
	$errors			= [];

	if( ! $first_name || ! $last_name || ! $email ){
		if( ! $first_name ) $errors['firstname'] = __( "Обов'язкове поле", 'inheart' );
		if( ! $last_name ) $errors['lastname'] = __( "Обов'язкове поле", 'inheart' );
		if( ! $email ) $errors['email'] = __( "Обов'язкове поле", 'inheart' );

		return ['errors' => $errors];
	}

	if( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) )
		$errors['email'] = __( 'Невірний формат пошти', 'inheart' );

	if( $pass ){
		$user_data	= get_userdata( $user_id )->data;
		$user_pass	= $user_data->user_pass;

		if( ! wp_check_password( $pass, $user_pass, $user_id ) )
			$errors['pass'] = __( 'Невірний поточний пароль', 'inheart' );

		if( $new_pass && ! ih_check_length( $new_pass, 8, 256 ) )
			$errors['new-pass'] = __( 'Від 8 до 256 символів', 'inheart' );

		if( $new_pass !== $confirm_pass )
			$errors['confirm-pass'] = __( 'Паролі не співпадають', 'inheart' );
	}

	return [
		'errors'		=> $errors,
		'first_name'	=> $first_name,
		'last_name'		=> $last_name,
		'email'			=> $email,
		'pass'			=> $pass,
		'new_pass'		=> $new_pass,
		'confirm_pass'	=> $confirm_pass
	];
}

add_action( 'wp_ajax_ih_ajax_check_profile_settings', 'ih_ajax_check_profile_settings' );
/**
 * Check profile settings changes.
 *
 * @return void
 */
function ih_ajax_check_profile_settings(): void
{
	$errors = ih_check_profile_settings_form( get_current_user_id(), $_POST )['errors'];

	// If there are some errors in the fields.
	if( ! empty( $errors ) ) wp_send_json_error( ['errors' => $errors] );

	wp_send_json_success( ['msg' => esc_html__( 'Ваші дані можуть бути оновлені', 'inheart' )] );
}

add_action( 'wp_ajax_ih_ajax_save_profile', 'ih_ajax_save_profile' );
/**
 * Save profile changes.
 *
 * @return void
 */
function ih_ajax_save_profile(): void
{
	$user_id	= get_current_user_id();
	$res		= ih_check_profile_settings_form( $user_id, $_POST );
	$errors		= $res['errors'];

	// If there are some errors in the fields.
	if( ! empty( $errors ) ) wp_send_json_error( ['errors' => $errors] );

	$userdata = ['ID' => $user_id];

	if( $res['first_name'] ) $userdata['first_name'] = $res['first_name'];
	if( $res['last_name'] ) $userdata['last_name'] = $res['last_name'];
	if( $res['email'] ) $userdata['user_email'] = $res['email'];
	if( $res['new_pass'] ) wp_set_password( $res['new_pass'], $user_id );

	if( ! wp_update_user( $userdata ) )
		wp_send_json_error( ['msg' => esc_html__( 'Не вдалося оновити інформацію', 'inheart' )] );

	wp_send_json_success( ['msg' => esc_html__( 'Ваші дані оновлені', 'inheart' )] );
}

add_action( 'wp_ajax_ih_ajax_edit_memory_page', 'ih_ajax_edit_memory_page' );
/**
 * Edit existing memory page.
 *
 * @return void
 */
function ih_ajax_edit_memory_page(): void
{
	$memory_id = ( int ) ih_clean( $_POST['id'] );

	if( ! $memory_id || get_post_type( $memory_id ) !== 'memory_page' )
		wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	$user_id	= get_current_user_id();
	$author_id	= ( int ) get_post_field ( 'post_author', $memory_id );

	if( $user_id !== $author_id )
		wp_send_json_error( ['msg' => esc_html__( "Ви не автор цієї сторінки пам'яті", 'inheart' )] );

	$_SESSION['memory_page_id'] = $memory_id;

	wp_send_json_success( ['redirect' => get_the_permalink( pll_get_post( 167 ) ) . '?edit=1' ] );
}

add_action( 'wp_ajax_ih_ajax_add_person_memory', 'ih_ajax_add_person_memory' );
/**
 * Add a memory from a person.
 *
 * @return void
 */
function ih_ajax_add_person_memory(): void
{
	$photo			= $_FILES['photo'];
	$memory_page_id	= ih_clean( $_POST['page'] );
	$fullname		= ih_clean( $_POST['fullname'] );
	$role			= ih_clean( $_POST['role'] );
	$memory			= ih_clean( $_POST['memory'] );
	$agreement		= ih_clean( $_POST['agreement'] );

	// If data is not set - send error.
	if( ! $memory_page_id || ! $fullname || ! $role || ! $memory || ! $agreement )
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	$post_data = [
		'post_title'	=> "$fullname для сторінки " . get_the_title( $memory_page_id ),
		'post_status'	=> 'pending',
		'post_type'		=> 'memory',
		'post_author'	=> get_current_user_id()
	];
	$post_id = wp_insert_post( wp_slash( $post_data ) );

	if( is_wp_error( $post_id ) ) wp_send_json_error( ['msg' => __( 'Не вдалося створити спогад', 'inheart' )] );

	update_field( 'memory_page', $memory_page_id, $post_id );
	update_field( 'full_name', $fullname, $post_id );
	update_field( 'role', $role, $post_id );
	update_field( 'content', $memory, $post_id );

	if( ! $photo || $photo['size'] === 0 ){
		// send mail !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		// send mail !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		// send mail !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		// send mail !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		// send mail !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		wp_send_json_success( ['msg' => __( 'Спогад створено успішно', 'inheart' )] );
	}

	$allowed_image_types    = ['image/jpeg', 'image/png'];
	$max_image_size         = 50_000_000;

	// Check conditions for the image.
	if( ! in_array( $photo['type'], $allowed_image_types ) || ( int ) $photo['size'] > $max_image_size )
		wp_send_json_error( ['msg' => __( 'Тільки ( png | jpg | jpeg ) меньше 50 мб', 'inheart' )] );

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	$attach_id = media_handle_upload( 'photo', $post_id );

	if( is_wp_error( $attach_id ) )
		wp_send_json_error( ['msg' => __( 'Помилка під час завантаження зображення', 'inheart' )] );

	set_post_thumbnail( $post_id, $attach_id );

	$subject		= get_field( 'memory_created_subject', 'option' );
	$body			= get_field( 'memory_created_body', 'option' );
	$memory_author	= wp_get_current_user();
	$author_email	= $memory_author->user_email;
	$memory_wrap	= '<div style="width: 100%; max-width: 400px; color: #011C1A; font-size: 16px; line-height: 24px; background-color: #FFFFFF; border-radius: 40px;">' .
		( has_post_thumbnail( $post_id ) ?
		'<img
			src="' . get_the_post_thumbnail_url( $post_id, 'medium' ) . '"
			style="width: 100%; height: auto; border-radius: 20px; margin-bottom: 24px;"
			alt=""
		/>' : '' ) .
		'<div style="margin-bottom: 20px; opacity: 0.8;">' . esc_html( $memory ) . '</div>' .
		'<div style="margin-bottom: 4px; opacity: 0.8;">' . esc_html( $fullname ) . '</div>' .
		'<div style="color: #7E969B">' . esc_html( $role ) . '</div>' .
	'</div>';
	$body = str_replace( '[memory]', $memory_wrap, $body );

	add_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
	wp_mail( $author_email, $subject, $body );
	remove_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );

	wp_send_json_success( ['msg' => __( 'Спогад створено успішно', 'inheart' )] );
}

add_action( 'wp_ajax_ih_ajax_load_profile_memories', 'ih_ajax_load_profile_memories' );
/**
 * Show memories in Profile depending on a passed type.
 *
 * @return void
 */
function ih_ajax_load_profile_memories(): void
{
	$type		= ih_clean( $_POST['type'] );	// 'others' | 'yours'
	$page_id	= ih_clean( $_POST['id'] );	// 'others' | 'yours'

	if( ! $type ) wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	$current_user_id	= get_current_user_id();
	$args				= [
		'post_type'		=> 'memory',
		'posts_per_page'=> -1
	];

	if( $type === 'yours' ){
		$args['author__in']		= [$current_user_id];
		$args['post_status']	= ['publish', 'pending', 'trash'];
	}else{
		$memory_pages = get_posts( [
			'post_type'     => 'memory_page',
			'post_status'   => 'publish',
			'numberposts'   => -1,
			'author__in'    => $current_user_id
		] );
		$memory_pages_ids = [];

		foreach( $memory_pages as $page ) $memory_pages_ids[] = $page->ID;

		$args = array_merge( $args, [
			'post_status'   => 'any',
			'meta_query'    => [
				[
					'key'       => 'memory_page',
					'value'     => $memory_pages_ids,
					'compare'   => 'IN'
				],
				[
					'key'		=> 'is_rejected',
					'compare'	=> 'NOT EXISTS'
				]
			]
		] );
	}

	$memories_query = new WP_Query( $args );

	if( $memories_query->have_posts() ){
		$memories = '';

		while( $memories_query->have_posts() ){
			$memories_query->the_post();
			$memories .= ih_load_template_part(
				'components/cards/memory/preview',
				null,
				['id' => get_the_ID(), 'type' => $type]
			);
		}

		wp_reset_query();
		wp_send_json_success( ['memories' => $memories] );
	}

	$no_memories = ih_load_template_part(
		'components/profile/memories/no-memories',
		null,
		['id' => $page_id, 'type' => $type]
	);
	wp_send_json_success( ['no-memories' => $no_memories] );
}

add_action( 'wp_ajax_ih_ajax_publish_profile_memory', 'ih_ajax_publish_profile_memory' );
/**
 * Publish memory.
 *
 * @return void
 */
function ih_ajax_publish_profile_memory(): void
{
	$id = ih_clean( $_POST['id'] );

	if( ! $id ) wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	$current_user_id	= get_current_user_id();
	$memory_page_id		= get_field( 'memory_page', $id );
	$author_id			= ( int ) get_post_field ( 'post_author', $memory_page_id );

	// Current User is not an author of this Memory page - exit.
	if( $current_user_id !== $author_id )
		wp_send_json_error( ['msg' => __( "Ви не автор цієї сторінки пам'яті", 'inheart' )] );

	wp_publish_post( $id );
	$memory = ih_load_template_part( 'components/cards/memory/preview', null, ['id' => $id] );
	wp_send_json_success( [
		'msg'		=> __( 'Спогад успішно опубліковано!', 'inheart' ),
		'memory'	=> $memory
	] );
}

add_action( 'wp_ajax_ih_ajax_delete_profile_memory', 'ih_ajax_delete_profile_memory' );
/**
 * Delete memory.
 *
 * @return void
 */
function ih_ajax_delete_profile_memory(): void
{
	$id		= ih_clean( $_POST['id'] );
	$type	= ih_clean( $_POST['type'] );

	if( ! $id || ! $type ) wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	$memory_author_id		= ( int ) get_post_field( 'post_author', $id );
	$current_user_id		= get_current_user_id();
	$memory_page_id			= get_field( 'memory_page', $id );
	$memory_page_author_id	= ( int ) get_post_field( 'post_author', $memory_page_id );

	// If User changed his mind and wants to remove his own memory.
	if( $type === 'yours' ){
		// Check if he is an author of this memory.
		if( $current_user_id !== $memory_author_id )
			wp_send_json_error( ['msg' => __( 'Ви не автор цього спогаду', 'inheart' )] );

		if( get_post_status( $id ) === 'pending' ){
			$images = get_attached_media( 'image', $id );

			if( ! empty( $images ) )
				foreach ( $images as $image ) wp_delete_attachment( $image->ID, true );

			wp_delete_post( $id, true );
			wp_send_json_success( ['msg' => __( 'Ваш спогад видалено', 'inheart' )] );
		}
	}else{
		// If User is the author of the Memory page - let's check it.
		if( $current_user_id !== $memory_page_author_id )
			wp_send_json_error( ['msg' => __( "Ви не автор сторінки пам'яті, до якої належить цей спогад", 'inheart' )] );

		update_field( 'is_rejected', 'yes', $id );
		wp_update_post( ['ID' =>  $id, 'post_status' => 'pending'] );
		wp_send_json_success( ['msg' => __( 'Спогад успішно видалено', 'inheart' )] );
	}
}


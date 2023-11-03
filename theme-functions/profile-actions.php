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


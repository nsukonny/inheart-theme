<?php

add_action( 'wp_ajax_ih_ajax_save_profile', 'ih_ajax_save_profile' );
/**
 * Save profile changes.
 *
 * @return void
 */
function ih_ajax_save_profile(): void
{
	$user_id		= get_current_user_id();
	$first_name		= ih_clean( $_POST['firstname'] );
	$last_name		= ih_clean( $_POST['lastname'] );
	$email			= ih_clean( $_POST['email'] );
	$pass			= ih_clean( $_POST['pass'] );
	$new_pass		= ih_clean( $_POST['new-pass'] );
	$confirm_pass	= ih_clean( $_POST['confirm_pass'] );

	if( ! $first_name && ! $last_name && ! $email && ! $pass )
		wp_send_json_error( ['msg' => esc_html__( 'Заповніть поля, які хочете оновити', 'inheart' )] );

	$userdata = ['ID' => $user_id];

	if( $first_name ) $userdata['first_name'] = $first_name;

	if( $last_name ) $userdata['last_name'] = $last_name;

	if( $email ){
		if( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) )
			wp_send_json_error( ['msg' => esc_html__( 'Невірний формат пошти', 'inheart' )] );

		$userdata['user_email'] = $email;
	}

	if( $pass ){
		$user_data	= get_userdata( $user_id )->data;
		$user_pass	= $user_data->user_pass;

		if( ! wp_check_password( $pass, $user_pass, $user_id ) )
			wp_send_json_error( ['msg' => esc_html__( 'Невірний поточний пароль', 'inheart' )] );

		if( ! $new_pass || ! $confirm_pass )
			wp_send_json_error( ['msg' => esc_html__( 'Заповніть поля нового паролю, або видаліть поточний пароль', 'inheart' )] );

		if( $new_pass !== $confirm_pass )
			wp_send_json_error( ['msg' => esc_html__( 'Дані у полях нового паролю не однакові', 'inheart' )] );

		if( ! ih_check_length( $new_pass, 8, 256 ) )
			wp_send_json_error( ['msg' => esc_html__( 'Пароль не повинен бути меньше 8 або більше 256 символів', 'inheart' )] );

		wp_set_password( $new_pass, $user_id );
	}

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


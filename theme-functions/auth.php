<?php

/**
 * Authorization logic AJAX functions.
 *
 * @package WordPress
 * @subpackage inheart
 */

add_action( 'init', 'ih_block_admin_access' );
/**
 * Redirect website Visitors who trying to access Admin side.
 * Only Administrator and Editor User roles are allowed.
 *
 * @return void
 */
function ih_block_admin_access(): void
{
	if(
		is_admin()
		&& ! current_user_can( 'administrator' )
		&& ! current_user_can( 'editor' )
		&& ! ( defined( 'DOING_AJAX' ) && DOING_AJAX )
	){
		wp_redirect( get_the_permalink( pll_get_post( ih_get_login_page_id() ) ) );	// To Login page.
		exit;
	}

	// Redirect not logged Visitors to Login page from url with 'stvorennya-storinki-pamyati' in it.
	$uri = $_SERVER['REQUEST_URI'];

	if(
		$uri && ! is_user_logged_in() &&
		(
			str_contains( $uri, 'stvorennya-storinki-pamyati' ) ||
			str_contains( $uri, 'sozdanie-straniczy-pamyati' ) ||
			str_contains( $uri, 'creating-a-new-memory-page' )
		)
	){
		wp_redirect( get_the_permalink( pll_get_post( ih_get_login_page_id() ) ) . '?memory=1' );	// To Login page.
		exit;
	}
}

add_action( 'template_redirect', 'ih_template_redirect' );
/**
 * Redirect user depending on some conditions.
 *
 * @return void
 */
function ih_template_redirect(): void
{
	// Redirect from QR single page to its connected Memory page.
	if( is_singular( 'qr' ) && ( $redirect = get_field( 'memory_page_url' ) ) ){
		wp_redirect( $redirect );
		exit;
	}
}

add_action( 'wp_ajax_nopriv_ih_ajax_login', 'ih_ajax_login' );
/**
 * Custom login logic.
 *
 * @return void
 */
function ih_ajax_login(): void
{
	// Verify hidden nonce field.
	if( empty( $_POST ) || ! wp_verify_nonce( $_POST['ih_login_nonce'], 'ih_ajax_login' ) )
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	$login		= ih_clean( $_POST['email'] );
	$pass		= trim( str_replace( ' ', '', $_POST['pass'] ) );
//	$referer	= ih_clean( $_POST['referer'] );
	$errors		= [];

	// If data is not set - send error.
	if( ! $login || ! $pass ){
		if( ! $login )
			$errors[] = ['field' => 'email'];

		if( ! $pass )
			$errors[] = ['field' => 'pass'];

		wp_send_json_error( ['errors' => $errors, 'msg' => esc_html__( 'Невірна пошта або пароль', 'inheart' )] );
	}

	// If this login or email was not found - user not exists, send error.
	if( ! username_exists( $login ) && ! email_exists( $login ) ){
		$errors[] = ['field' => 'email'];
		wp_send_json_error( ['errors' => $errors, 'msg' => esc_html__( 'Такий Користувач не існує', 'inheart' )] );
	}

	// If not success - trying to find user by email field.
	if( ! $user = get_user_by( 'login', $login ) ){
		$user = get_user_by( 'email', $login );

		// If fail again - user not found, send error.
		if( ! $user ) wp_send_json_error( ['msg' => esc_html__( 'Неможливо отримати цього Користувача', 'inheart' )] );
	}

	$user_id	= $user->ID;
	$user_data	= get_userdata( $user_id )->data;
	$hash		= $user_data->user_pass;

	// If passwords are not equal - send error.
	if( ! wp_check_password( $pass, $hash, $user_id ) ){
		$errors[] = ['field' => 'pass'];
		wp_send_json_error( ['errors' => $errors, 'msg' => esc_html__( 'Невірний пароль', 'inheart' )] );
	}

	// Check if account is activated.
	if( get_user_meta( $user_id, 'activation_code', true ) ){
		$msg = '
			Акаунт не активований.<br />
			Будь ласка, перевірте свою пошту та розділ Спам.<br />
			Або спробуйте надіслати посилання на активацію ще раз 
			<a href="' . get_the_permalink( pll_get_post( ih_get_activation_page_id() ) ) .  '?user=' . $user_id . '&code=fail">тут</a>. 
		';
		wp_send_json_error( ['msg' => $msg] );
	}

	// If all is OK - trying to sign user on.
	$credentials = [
		'user_login'	=> $login,
		'user_password'	=> $pass,
		'remember'		=> true
	];
	$sign_on = wp_signon( $credentials, false );

	// If there is error during sign on - send it.
	if( is_wp_error( $sign_on ) ) wp_send_json_error( ['msg' => $sign_on->get_error_message()] );

	wp_set_current_user( $user_id );
	wp_set_auth_cookie( $user_id, true );

	wp_send_json_success( [
		'msg'		=> sprintf( esc_html__( 'Вітаємо, %s!', 'inheart' ), $user->display_name ),
		'redirect'	=> get_field( 'redirect_after_login', 'option' ) ?: home_url( '/' )
	] );
}

add_action( 'wp_ajax_ih_ajax_logout', 'ih_ajax_logout' );
/**
 * Custom logout logic.
 *
 * @return void
 */
function ih_ajax_logout(): void
{
	$page_id = isset( $_POST['page'] ) ? ( int ) ih_clean( $_POST['page'] ) : null;

	if( $page_id && is_numeric( $page_id ) && get_page_template( $page_id ) === 'page-templates/authorization.php' )
		$redirect = home_url( '/' );
	else
		$redirect = get_permalink( $page_id );

	wp_logout();

	wp_send_json_success( [
		'msg'		=> esc_html__( 'Success', 'inheart' ),
		'redirect'	=> $redirect
	] );
}

add_action( 'wp_ajax_nopriv_ih_ajax_register', 'ih_ajax_register' );
/**
 * AJAX register.
 *
 * @return void
 */
function ih_ajax_register(): void
{
	if( ! get_option( 'users_can_register' ) )
		wp_send_json_error( ['msg' => esc_html__( 'Реєстрація користувачів зараз неможлива, спробуйте пізніше', 'inheart' )] );

	// Verify hidden nonce field.
	if( empty( $_POST ) || ! wp_verify_nonce( $_POST['ih_register_nonce'], 'ih_ajax_register' ) )
		wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	$fullname		= ih_clean( $_POST['fullname'] );
	$email			= ih_clean( $_POST['email'] );
	$pass			= str_replace( ' ', '', trim( $_POST['pass'] ) );
	$pass2			= str_replace( ' ', '', trim( $_POST['pass-confirm'] ) );
	$agreement		= ih_clean( $_POST['agreement'] );
	$errors			= [];

	// If some of required data fields is not set - send error.
	if( ! $fullname || ! $email || ! $pass || ! $pass2 || ! $agreement ) {
		if ( ! $fullname ) $errors[] = ['field' => 'fullname'];
		if ( ! $email ) $errors[] = ['field' => 'email'];
		if ( ! $pass ) $errors[] = ['field' => 'pass'];
		if ( ! $pass2 ) $errors[] = ['field' => 'pass-confirm'];
		if ( ! $agreement ) $errors[] = ['field' => 'agreement'];

		wp_send_json_error( ['errors' => $errors, 'msg' => esc_html__( "Будь ласка, заповніть всі поля", 'inheart' )] );
	}

	// Additional checking.
	if( ! ih_check_length( $fullname, 1, 50 ) ) $errors[] = ['field' => 'fullname'];
	if( ! ih_check_length( $email, 1, 50 ) ) $errors[] = ['field' => 'email'];
	if( ! empty( $errors ) ) wp_send_json_error( ['errors' => $errors, 'msg' => esc_html__( "Максимум 50 символів", 'inheart' )] );

	if( ! ih_check_name( $fullname ) ) wp_send_json_error( ['errors' => [['field' => 'fullname']], 'msg' => esc_html__( "Зайві символи у полі імені", 'inheart' )] );

	if( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) )
		wp_send_json_error( ['errors' => [['field' => 'email']], 'msg' => esc_html__( "Невірний формат пошти", 'inheart' )] );

	if( ! ih_check_length( $pass, 8, 256 ) ) $errors[] = ['field' => 'pass'];
	if( ! ih_check_length( $email, 8, 256 ) ) $errors[] = ['field' => 'pass-confirm'];
	if( ! empty( $errors ) ) wp_send_json_error( ['errors' => $errors, 'msg' => esc_html__( "Від 8 до 256 символів", 'inheart' )] );

	// Check passwords' equality.
	if( $pass !== $pass2 ){
		$errors = [ ['field' => 'pass'], ['field' => 'pass-confirm'] ];
		wp_send_json_error( ['errors' => $errors, 'msg' => esc_html__( 'Паролі не однакові', 'inheart' )] );
	}

	$fullname = explode( ' ', $fullname, 2 );

	if( count( $fullname ) < 2 ) wp_send_json_error( ['errors' => [['field' => 'fullname']], 'msg' => esc_html__( "Будь ласка, вкажіть ім'я та прізвище", 'inheart' )] );

	// Data to create new User.
	$login			= substr( $email, 0, strrpos( $email, '@' ) );
	$new_user_id	= wp_insert_user( [
		'user_login'			=> $login,
		'user_email'			=> $email,
		'first_name'			=> $fullname[0],
		'last_name'				=> $fullname[1],
		'show_admin_bar_front'	=> 'false'
	] );

	// New User creation error.
	if( is_wp_error( $new_user_id ) ) ih_check_user_creation_errors( $new_user_id );

	// Set new User's password and meta fields.
	wp_set_password( $pass, $new_user_id );

	// Account activation.
	$code   = sha1( $new_user_id . time() );
	$link   = home_url() . '/activate/?code=' . $code . '&user=' . $new_user_id;
	add_user_meta( $new_user_id, 'activation_code', $code, true );

	/**
	 * Email template.
	 *
	 * @see Theme Settings -> Email Templates -> New User Registered.
	 */
	$lifetime   = get_field( 'registration_link_lifetime', 'option' ) ?? 5;
	$subject	= get_field( 'registered_subject', 'option' );
	$msg		= get_field( 'registered_body', 'option' );
	$msg		= str_replace( ['[user_login]', '[activation_url]', '[registration_link_lifetime]'], [$login, $link, $lifetime], $msg );
	$msg		= str_replace( ['https://https://', 'http://https://', 'http://http://'], ['https://', 'https://', 'http://'], $msg );

	add_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
	$send = wp_mail( $email, $subject, $msg );
	remove_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );

	// If letter with is not send - show error.
	if( ! $send )
		wp_send_json_error( [
			'msg' => sprintf(
				esc_html__( 'Новий Користувач %s зареєстрований, але лист з посиланням на активацію не відправлено на %s через невідому помилку.', 'inheart' ),
				$login, $email
			)
		] );

	// Store current date and time to check difference on E-mail submit.
	date_default_timezone_set( 'UTC' );
	add_user_meta( $new_user_id, 'registration_date', time(), true );

	wp_send_json_success( [
		'msg'       => esc_html__( 'Успішно. Вітаємо!', 'inheart' ),
		'redirect'	=> get_the_permalink( pll_get_post( ih_get_activation_page_id() ) ) . "?user=$new_user_id&registered=1"
	] );
}

add_action( 'wp_ajax_nopriv_ih_ajax_resend_activation_link', 'ih_ajax_resend_activation_link' );
/**
 * Re-send activation link if User is registered, but activation link is expired.
 */
function ih_ajax_resend_activation_link(): void
{
	if( ! $user_id = ih_clean( $_POST['user_id'] ) )
		wp_send_json_error( ['msg' => esc_html__( 'Невірні дані - Користувач не існує.', 'inheart' )] );

	// Get User E-mail address by ID.
	$userdata	= get_userdata( $user_id );
	$email		= $userdata->user_email;
	$login		= $userdata->user_login;
	$code		= sha1( $user_id . time() );
	$link		= home_url() . '/activate/?code=' . $code . '&user=' . $user_id;

	update_user_meta( $user_id, 'activation_code', $code );

	/**
	 * Email template.
	 *
	 * @see Options -> Email Templates -> Resend Activation Link.
	 */
	$lifetime   = get_field( 'registration_link_lifetime', 'option' ) ?? 5;
	$subject	= get_field( 'resend_activation_subject', 'option' );
	$msg		= get_field( 'resend_activation_body', 'option' );
	$msg		= str_replace( ['[user_login]', '[activation_url]', '[registration_link_lifetime]'], [$login, $link, $lifetime], $msg );
	$msg		= str_replace( ['https://https://', 'http://https://', 'http://http://'], ['https://', 'https://', 'http://'], $msg );

	add_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
	$send = wp_mail( $email, $subject, $msg );
	remove_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );

	// If letter with is not send - show error.
	if( ! $send ) wp_send_json_error( ['msg' => esc_html__( 'Помилка відправки.', 'inheart' )] );

	// Store current date and time to check difference on E-mail submit.
	date_default_timezone_set( 'UTC' );
	update_user_meta( $user_id, 'registration_date', time() );

	wp_send_json_success( [
		'msg'       => esc_html__( 'Успішно. Вітаємо!', 'inheart' ),
		'redirect'	=> get_the_permalink( pll_get_post( ih_get_activation_page_id() ) ) . "?user=$user_id&registered=1"
	] );
}

add_action( 'wp_ajax_nopriv_ih_ajax_lost_password', 'ih_ajax_lost_password' );
/**
 * Send specific link to change User's password.
 */
function ih_ajax_lost_password(): void
{
	$email_login = ih_clean( $_POST['email'] );

	// Verify hidden nonce field.
	if( empty( $_POST ) || ! wp_verify_nonce( $_POST['ih_lost_password_nonce'], 'ih_ajax_lost_password' ) )
		wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	if( ! $email_login )
		wp_send_json_error( ['errors' => [['field' => 'email']], 'msg' => esc_html__( 'Вкажіть пошту або логін', 'inheart' )] );

	if( ! filter_var( $email_login, FILTER_VALIDATE_EMAIL ) )
		wp_send_json_error( ['errors' => [['field' => 'email']], 'msg' => esc_html__( "Невірний формат пошти", 'inheart' )] );

	// Account not found.
	if( ! username_exists( $email_login ) && ! email_exists( $email_login ) )
		wp_send_json_error( ['errors' => [['field'	=> 'email']], 'msg' => esc_html__( 'Такий акаунт не існує', 'inheart' )] );

	// User not found - search by email.
	if( ! $user = get_user_by( 'login', $email_login ) ){
		$user = get_user_by( 'email', $email_login );

		// User not found, send error.
		if( ! $user )
			wp_send_json_error( ['errors' => [['field' => 'email']], 'msg' => esc_html__( 'Такий акаунт не існує', 'inheart' )] );
	}

	$user_id	= $user->ID;
	$user_email	= $user->data->user_email;

	// Generate specific URL.
	$code = sha1( $user_id . time() );
	$link = home_url() . '/lostpass/?code=' . $code . '&user=' . $user_id;
	update_user_meta( $user_id, 'pass_recovery_code', $code );
	date_default_timezone_set( 'UTC' );
	update_user_meta( $user_id, 'pass_recovery_date', time() );

	/**
	 * Email template.
	 *
	 * @see Theme Settings -> Email Templates -> Forgot Password.
	 */
	$lifetime   = get_field( 'registration_link_lifetime', 'option' ) ?? 5;
	$subject	= get_field( 'pass_recovery_subject', 'option' );
	$msg		= get_field( 'pass_recovery_body', 'option' );
	$msg		= str_replace( ['[user_login]', '[pass_url]', '[registration_link_lifetime]'], [$email_login, $link, $lifetime], $msg );
	$msg		= str_replace( ['https://https://', 'http://https://', 'http://http://'], ['https://', 'https://', 'http://'], $msg );

	add_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
	$send = wp_mail( $user_email, $subject, $msg );
	remove_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );

	// If letter with is not send - show error.
	if( ! $send ) wp_send_json_error( ['msg' => esc_html__( 'Помилка відправки.', 'inheart' )] );

	wp_send_json_success( [
		'msg'       => esc_html__( 'Успішно, вітаємо!', 'inheart' ),
		'redirect'	=> get_the_permalink( pll_get_post( ih_get_forgot_pass_page_id() ) ) . "?user=$user_id&send=1"
	] );
}

add_action( 'wp_ajax_nopriv_ih_ajax_new_password', 'ih_ajax_new_password' );
/**
 * Set new password for specific User.
 */
function ih_ajax_new_password(): void
{
	// Verify hidden nonce field.
	if( empty( $_POST ) || ! wp_verify_nonce( $_POST['ih_new_password_nonce'], 'ih_ajax_new_password' ) )
		wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	$user_id	= ih_clean( $_POST['user-id'] );
	$code		= ih_clean( $_POST['code'] );
	$pass		= str_replace( ' ', '', trim( $_POST['pass'] ) );
	$pass2		= str_replace( ' ', '', trim( $_POST['pass-confirm'] ) );
	$errors		= [];

	if( ! $user_id || ! $code )
		wp_send_json_error( ['msg' => esc_html__( 'Невірні дані', 'inheart' )] );

	if( ! $pass ) $errors[] = ['field' => 'pass'];
	if( ! $pass2 ) $errors[] = ['field' => 'pass-confirm'];
	if( ! empty( $errors ) ) wp_send_json_error( ['errors' => $errors, 'msg' => esc_html__( "Заповніть поля", 'inheart' )] );

	if( ! ih_check_length( $pass, 8, 256 ) ) $errors[] = ['field' => 'pass'];
	if( ! ih_check_length( $pass2, 8, 256 ) ) $errors[] = ['field' => 'pass-confirm'];
	if( ! empty( $errors ) ) wp_send_json_error( ['errors' => $errors, 'msg' => esc_html__( "Від 8 до 256 символів", 'inheart' )] );

	if( $pass !== $pass2 ) wp_send_json_error( ['errors' => [['field' => $pass2]], 'msg' => esc_html__( "Паролі не однакові", 'inheart' )] );

	// Account not found.
	if( ! $user = get_user_by( 'id', $user_id ) )
		wp_send_json_error( ['msg' => esc_html__( 'такий акаунт не існує', 'inheart' )] );

	$original_code = get_user_meta( $user_id, 'new_pass_code', true );

	// If original code from User's field differs from current code - send error.
	if( ! $original_code || $original_code !== $code )
		wp_send_json_error( ['msg' => esc_html__( 'Помилка: невірні дані', 'inheart' )] );

	delete_user_meta( $user_id, 'pass_recovery_code' );
	delete_user_meta( $user_id, 'pass_recovery_date' );
	delete_user_meta( $user_id, 'new_pass_code' );
	wp_set_password( $pass, $user_id );

	// Success!
	wp_send_json_success( [
		'msg'		=> esc_html__( 'Успішно, пароль змінено!', 'inheart' ),
		'redirect'	=> get_the_permalink( pll_get_post( ih_get_login_page_id() ) )	// To Login page.
	] );
}


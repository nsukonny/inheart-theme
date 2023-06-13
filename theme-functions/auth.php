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
	global $pagenow;

	if(
		'wp-login.php' === $pagenow
		&& ! current_user_can( 'administrator' )
		&& ! current_user_can( 'editor' )
	){
		wp_redirect( get_the_permalink( 10 ) );	// To Login page.
		exit;
	}

	if(
		is_admin()
		&& ! current_user_can( 'administrator' )
		&& ! current_user_can( 'editor' )
		&& ! ( defined( 'DOING_AJAX' ) && DOING_AJAX )
	){
		wp_redirect( get_the_permalink( 10 ) );	// To Login page.
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
		wp_send_json_error( ['msg' => esc_html__( 'Invalid request data.', 'inheart' )] );

	$login		= ih_clean( $_POST['email'] );
	$pass		= trim( str_replace( ' ', '', $_POST['pass'] ) );
	$referer	= ih_clean( $_POST['referer'] );
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
			Або спробуйте надіслати посилання на активацію ще раз <a href="' . get_the_permalink( 529 ) .  '?user=' . $user_id . '&code=fail">тут</a>. 
		';
		wp_send_json_error( ['msg' => $msg] );
	}

	// If all is OK - trying to sign user on.
	$credentials = [
		'user_login'	=> $login,
		'user_password'	=> $pass
	];
	$sign_on = wp_signon( $credentials, false );

	// If there is error during sign on - send it.
	if( is_wp_error( $sign_on ) ) wp_send_json_error( ['msg' => $sign_on->get_error_message()] );

	wp_set_current_user( $user_id );
	wp_set_auth_cookie( $user_id );

	wp_send_json_success( [
		'msg'		=> sprintf( esc_html__( 'Вітаємо, %s!', 'inheart' ), $user->display_name ),
		'redirect'	=> $referer ?: home_url( '/' )
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
		wp_send_json_error( [
			'msg' => esc_html__( 'User registration currently is unavailable, please try again later.', 'inheart' )
		] );

	// Verify hidden nonce field.
	if( empty( $_POST ) || ! wp_verify_nonce( $_POST['ih_register_nonce'], 'ih_ajax_register' ) )
		wp_send_json_error( ['msg' => esc_html__( 'Invalid request data.', 'inheart' )] );

	$firstname		= ih_clean( $_POST['form-register-firstname'] );
	$lastname		= ih_clean( $_POST['form-register-lastname'] );
	$company		= ih_clean( $_POST['form-register-company'] );
	$email			= ih_clean( $_POST['form-register-email'] );
	$address1		= ih_clean( $_POST['form-register-billing-address-1'] );
	$address2		= ih_clean( $_POST['form-register-billing-address-2'] );
	$country		= ih_clean( $_POST['form-register-country'] );
	$city			= ih_clean( $_POST['form-register-city'] );
	$pass			= str_replace( ' ', '', trim( $_POST['form-register-pass'] ) );
	$pass2			= str_replace( ' ', '', trim( $_POST['form-register-pass2'] ) );
	$errors			= [];

	// If some of required data fields is not set - send error.
	if(
		! $firstname || ! $lastname || ! $company || ! $email
		|| ! $address1 || ! $address2 || ! $country || ! $city
		|| ! $pass || ! $pass2
	){
		ih_collect_errors( $errors, $firstname, 'form-register-firstname', 'Please enter first name *' );
		ih_collect_errors( $errors, $lastname, 'form-register-lastname', 'Please enter last name *' );
		ih_collect_errors( $errors, $company, 'form-register-company', 'Please enter company name *' );
		ih_collect_errors( $errors, $email, 'form-register-email', 'Please enter Email *' );
		ih_collect_errors( $errors, $address1, 'form-register-billing-address-1', 'Please enter street address *' );
		ih_collect_errors( $errors, $address2, 'form-register-billing-address-2', 'Please enter apartment address *' );
		ih_collect_errors( $errors, $country, 'form-register-country', 'Please enter country *' );
		ih_collect_errors( $errors, $city, 'form-register-city', 'Please enter city *' );
		ih_collect_errors( $errors, $pass, 'form-register-pass', 'Please enter password *' );
		ih_collect_errors( $errors, $pass2, 'form-register-pass2', 'Please confirm password *' );

		wp_send_json_error( ['errors' => $errors] );
	}

	// Additional checking.
	ih_collect_errors( $errors, $firstname, 'form-register-firstname', 'Please enter from 1 to 50 symbols', ih_check_length( $firstname, 1, 50 ) );
	ih_collect_errors( $errors, $lastname, 'form-register-lastname', 'Please enter from 1 to 50 symbols', ih_check_length( $lastname, 1, 50 ) );
	ih_collect_errors( $errors, $company, 'form-register-company', 'Please enter from 1 to 100 symbols', ih_check_length( $company, 1, 100 ) );
	ih_collect_errors( $errors, $email, 'form-register-email', 'Invalid Email format', filter_var( $email, FILTER_VALIDATE_EMAIL ) );
	ih_collect_errors( $errors, $email, 'form-register-email', 'Please enter from 1 to 50 symbols', ih_check_length( $email, 1, 50 ) );
	ih_collect_errors( $errors, $address1, 'form-register-billing-address-1', 'Please enter from 1 to 100 symbols', ih_check_length( $address1, 1, 100 ) );
	ih_collect_errors( $errors, $address2, 'form-register-billing-address-2', 'Please enter from 1 to 100 symbols', ih_check_length( $address2, 1, 100 ) );
	ih_collect_errors( $errors, $country, 'form-register-country', 'Please enter from 1 to 50 symbols', ih_check_length( $country, 1, 50 ) );
	ih_collect_errors( $errors, $city, 'form-register-city', 'Please enter from 1 to 50 symbols', ih_check_length( $city, 1, 50 ) );
	ih_collect_errors( $errors, $pass, 'form-register-pass', 'Please enter from 6 to 128 symbols', ih_check_length( $pass, 6, 128 ) );
	ih_collect_errors( $errors, $pass2, 'form-register-pass2', 'Please enter from 6 to 128 symbols', ih_check_length( $pass2, 6, 128 ) );

	if( ! empty( $errors ) ) wp_send_json_error( ['errors' => $errors] );

	// Check passwords' equality.
	if( $pass !== $pass2 ){
		$errors[] = [
			'field'	=> 'form-register-pass',
			'error'	=> esc_html__( 'Passwords are not equal', 'inheart' )
		];
		$errors[] = [
			'field'	=> 'form-register-pass2',
			'error'	=> esc_html__( 'Passwords are not equal', 'inheart' )
		];

		wp_send_json_error( ['errors' => $errors] );
	}

	// Data to create new User.
	$login			= substr( $email, 0, strrpos( $email, '@' ) );
	$new_user_id	= wp_insert_user( [
		'user_login'			=> $login,
		'user_email'			=> $email,
		'first_name'			=> $firstname,
		'last_name'				=> $lastname,
		'show_admin_bar_front'	=> 'false'
	] );

	// New User creation error.
	if( is_wp_error( $new_user_id ) ) ih_check_user_creation_errors( $new_user_id );

	// Set new User's password and meta fields.
	wp_set_password( $pass, $new_user_id );
	update_user_meta( $new_user_id, 'billing_first_name', $firstname );
	update_user_meta( $new_user_id, 'billing_last_name', $lastname );
	update_user_meta( $new_user_id, 'billing_company', $company );
	update_user_meta( $new_user_id, 'billing_address_1', $address1 );
	update_user_meta( $new_user_id, 'billing_address_2', $address2 );
	update_user_meta( $new_user_id, 'billing_country', $country );
	update_user_meta( $new_user_id, 'billing_city', $city );

	// Account activation.
	$code = sha1( $new_user_id . time() );
	$link = home_url() . '/activate/?code=' . $code . '&user=' . $new_user_id;
	add_user_meta( $new_user_id, 'activation_code', $code, true );

	/**
	 * Email template.
	 *
	 * @see Theme Settings -> Email Templates -> New User Registered.
	 */
	$subject	= get_field( 'registered_subject', 'option' );
	$msg		= get_field( 'registered_body', 'option' );
	$msg		= str_replace( ['[user_login]', '[activation_url]'], [$login, $link], $msg );
	$msg		= str_replace( ['https://https://', 'http://https://', 'http://http://'], ['https://', 'https://', 'http://'], $msg );

	add_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
	$send = wp_mail( $email, $subject, $msg );
	remove_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );

	// If letter with is not send - show error.
	if( ! $send )
		wp_send_json_error( [
			'msg' => sprintf(
				esc_html__( 'New User %s registered successfully, but the letter with your account activation link was not sent to %s due to an unknown error.', 'inheart' ),
				$login, $email
			)
		] );

	// Store current date and time to check difference on E-mail submit.
	date_default_timezone_set( 'UTC' );
	add_user_meta( $new_user_id, 'registration_date', time(), true );

	// Success!
	$code_lifetime = get_field( 'code_lifetime', 'option' ) ?? 5;

	/**
	 * @see Theme Settings -> Email Templates -> New User Registered.
	 */
	if( $success_message = get_field( 'registered_success_message', 'option' ) ){
		$success_message = str_replace(
			['[user_full_name]', '[user_email]', '[code_lifetime]'],
			["$firstname $lastname", $email, $code_lifetime],
			$success_message
		);
	}	else{
		$success_message = sprintf(
			esc_html__( 'Congratulations! New User %s registered successfully. The letter with your account activation link was sent to %s. Hurry up - the link will expire in %d minute(s)!', 'inheart' ),
			$login, $email, $code_lifetime
		);
	}

	wp_send_json_success( ['msg' => $success_message] );
}

add_action( 'wp_ajax_nopriv_ih_ajax_resend_activation_link', 'ih_ajax_resend_activation_link' );
/**
 * Re-send activation link if User is registered, but activation link is expired.
 */
function ih_ajax_resend_activation_link(): void
{
	if( ! $user_id = ih_clean( $_POST['user_id'] ) )
		wp_send_json_error( ['msg' => esc_html__( 'Invalid request data - User does not exists.', 'inheart' )] );

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
	$subject	= get_field( 'resend_activation_subject', 'option' );
	$msg		= get_field( 'resend_activation_body', 'option' );
	$msg		= str_replace( ['[user_login]', '[activation_url]'], [$login, $link], $msg );
	$msg		= str_replace( ['https://https://', 'http://https://', 'http://http://'], ['https://', 'https://', 'http://'], $msg );

	add_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
	$send = wp_mail( $email, $subject, $msg );
	remove_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );

	// If letter with is not send - show error.
	if( ! $send ) wp_send_json_error( ['msg' => esc_html__( 'Unknown error. Link was not sent.', 'inheart' )] );

	// Store current date and time to check difference on E-mail submit.
	date_default_timezone_set( 'UTC' );
	update_user_meta( $user_id, 'registration_date', time() );

	$code_lifetime = get_field( 'code_lifetime', 'option' ) ?? 5;
	wp_send_json_success( [
		'msg' => sprintf(
			esc_html__( 'The letter with your link was sent to %s. The link will expire in %d minutes.', 'inheart' ),
			$email, $code_lifetime
		)
	] );
}

add_action( 'wp_ajax_nopriv_ih_ajax_lost_password', 'ih_ajax_lost_password' );
/**
 * Send specific link to change User's password.
 */
function ih_ajax_lost_password(): void
{
	$email_login	= ih_clean( $_POST['email'] );
	$errors			= [];

	ih_collect_errors( $errors, $email_login, 'email', 'Please enter account email or login *' );

	if( ! empty( $errors ) ) wp_send_json_error( ['errors' => $errors] );

	// Verify hidden nonce field.
	if( empty( $_POST ) || ! wp_verify_nonce( $_POST['ih_lost_password_nonce'], 'ih_ajax_lost_password' ) )
		wp_send_json_error( ['msg' => esc_html__( 'Invalid request data.', 'inheart' )] );

	// Account not found.
	if( ! username_exists( $email_login ) && ! email_exists( $email_login ) )
		wp_send_json_error( ['errors' => [ [
			'field'	=> 'email',
			'error'	=> esc_html__( 'This account does not exist.', 'inheart' )
		] ] ] );

	// Search User by login.
	$user = get_user_by( 'login', $email_login );

	// User not found - search by email.
	if( ! $user ){
		$user = get_user_by( 'email', $email_login );

		// User not found, send error.
		if( ! $user )
			wp_send_json_error( ['errors' => [ [
				'field'	=> 'email',
				'error'	=> esc_html__( 'This account does not exist.', 'inheart' )
			] ] ] );
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
	$subject	= get_field( 'pass_recovery_subject', 'option' );
	$msg		= get_field( 'pass_recovery_body', 'option' );
	$msg		= str_replace( ['[user_login]', '[pass_url]'], [$email_login, $link], $msg );
	$msg		= str_replace( ['https://https://', 'http://https://', 'http://http://'], ['https://', 'https://', 'http://'], $msg );

	add_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
	$send = wp_mail( $user_email, $subject, $msg );
	remove_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );

	// If letter with is not send - show error.
	if( ! $send )
		wp_send_json_error( [
			'msg' => sprintf(
				esc_html__( 'Sorry, but %s\'s letter was not sent to %s due to an unknown error.', 'inheart' ),
				$email_login, $user_email
			)
		] );

	// Success!
	$code_lifetime = get_field( 'code_lifetime', 'option' ) ?? 5;
	wp_send_json_success( [
		'msg' => sprintf(
			esc_html__( 'Congratulations! %s\'s letter was sent to %s. Hurry up - the link will expire in %d minute(s)!', 'inheart' ),
			$email_login, $user_email, $code_lifetime
		)
	] );
}


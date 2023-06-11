<?php

/**
 * Activation page template content.
 *
 * @package WordPress
 * @subpackage inheart
 */

$user_id	= isset( $_GET['user'] ) ? ( int ) $_GET['user'] : '';
$code		= $_GET['code'] ?? '';

// User doesn't exist.
if( ! get_user_by( 'id', $user_id ) ){
	esc_html_e( 'Account does not exist.', 'inheart' );
	return;
}

// User has no field with code - already activated.
if( ! $original_code = get_user_meta( $user_id, 'activation_code', true ) ){
	esc_html_e( 'Account already activated.', 'inheart' );
	echo '<div class="btn-wrap"><a href="' . get_the_permalink( 10 ) . '" class="btn md">Login</a></div>';
	return;
}

// If there are no GET-params in page address.
if( ! $user_id || ! $code ){
	esc_html_e( 'Activation parameters are missing or User is already activated. Please check your account E-mail for our letter with activation link. If the letter did not arrive in the inbox - don\'t forget to check "Spam" folder too.', 'inheart' );
	return;
}

// If code & original_code parameter from URL are equal - success.
if( $code === $original_code ){
	_e( 'Account activation is success! Now you can log in.', 'inheart' );
	echo '<div class="btn-wrap"><a href="' . get_the_permalink( 10 ) . '" class="btn md">'
		. esc_html__( 'Login', 'inheart' ) .
	'</a></div>';
	delete_user_meta( $user_id, 'activation_code' );
	delete_user_meta( $user_id, 'registration_date' );
	return;
}

esc_html_e( 'Activation parameters are invalid or your account is already activated.', 'inheart' );
echo '<div class="btn-wrap"><a href="' . get_the_permalink( 10 ) . '" class="btn md">'
	. esc_html__( 'Login', 'inheart' ) .
'</a></div>';


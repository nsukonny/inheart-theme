<?php

/**
 * Activation page template content.
 *
 * @package WordPress
 * @subpackage inheart
 */

$user_id	= isset( $_GET['user'] ) ? ( int ) $_GET['user'] : '';
$code		= $_GET['code'] ?? '';
$registered = isset( $_GET['registered'] ) ? ( int ) $_GET['registered'] : '';

// User doesn't exist.
if( ! get_user_by( 'id', $user_id ) ){
	get_template_part( 'template-parts/auth/activation-fail', null, [
		'msg' => esc_html__( 'Такий акаунт не існує.', 'inheart' )
	] );
	return;
}

// User just registered, show success screen.
if( $user_id && $registered === 1 && ! $code ){
	get_template_part( 'template-parts/auth/registration-success', null, ['user_id' => $user_id] );
	return;
}

// User has no field with code - already activated.
if( ! $original_code = get_user_meta( $user_id, 'activation_code', true ) ){
	get_template_part( 'template-parts/auth/activation-fail', null, [
		'msg' => esc_html__( 'Цей акаунт вже активований!', 'inheart' )
	] );
	return;
}

// If there are no GET-params in page address.
if( ! $user_id || ! $code ){
	get_template_part( 'template-parts/auth/activation-fail', null, [
		'msg' => esc_html__( 'Параметри активації відсутні або Користувач вже активований. Будь ласка, перевірте свою пошту та розділ "Спам".', 'inheart' )
	] );
	return;
}

// If code & original_code parameter from URL are equal - success.
if( $code === $original_code ){
	// If expired.
	if( ih_is_activation_link_expired( $user_id ) ){
		get_template_part( 'template-parts/auth/activation-fail', null, [
			'msg' => sprintf(
				esc_html__( 'Акаунт не активовано - посилання вже не дійсне. %sНадіслати ще раз%s', 'inheart' ),
				'<a href="#send-activation-again">', '</a>'
			)
		] );
	}	else {
		get_template_part( 'template-parts/auth/activation-success' );
		delete_user_meta( $user_id, 'activation_code' );
		delete_user_meta( $user_id, 'registration_date' );
	}

	return;
}

get_template_part( 'template-parts/auth/activation-fail', null, [
	'msg' => sprintf(
		esc_html__( 'Параметри активації невірні або аккаунт вже активований. %sУвійти%s', 'inheart' ),
		'<a href="' . get_the_permalink( 10 ) . '">', '</a>'
	)
] );


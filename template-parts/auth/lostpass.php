<?php

/**
 * Lost Password page template content.
 *
 * @package WordPress
 * @subpackage inheart
 */

$user_id	= isset( $_GET['user'] ) ? ( int ) $_GET['user'] : null;
$code		= $_GET['code'] ?? null;

// If no URL params - lost password form.
if( ! $user_id && ! $code ){
	get_template_part( 'template-parts/auth/lostpass-form' );
	return;
}

/**
 * If there are some params in URL - someone is trying to set new password.
 * Let's check.
 */

// User doesn't exist.
if( ! get_user_by( 'id', $user_id ) ){
	echo '<div class="activation-inner wrap-gray">'
		. esc_html__( 'Account does not exist.', 'inheart' ) .
		'</div>';
	return;
}

// User has no field with code.
if( ! $original_code = get_user_meta( $user_id, 'pass_recovery_code', true ) ){
	echo '<div class="activation-inner wrap-gray">' . esc_html__( 'This User did not ask for a new password.', 'inheart' ) . '</div>';
	return;
}

// If code & original_code parameter from URL are equal - success.
if( $code === $original_code ){
	$new_pass_code = sha1( $user_id . time() );
	update_user_meta( $user_id, 'new_pass_code', $new_pass_code );
	get_template_part( 'template-parts/auth/new-pass-form', null, [
		'code'		=> $new_pass_code,
		'user_id'	=> $user_id
	] );
	return;
}
?>

<div class="activation-inner wrap-gray">
	<?php esc_html_e( 'Change password parameters are invalid or account password is already changed.', 'inheart' ) ?>
	<div class="btn-wrap">
		<a href="<?php get_the_permalink( 10 ) ?>" class="btn md">
			<?php esc_html_e( 'Login', 'inheart' ) ?>
		</a>
	</div>
</div>


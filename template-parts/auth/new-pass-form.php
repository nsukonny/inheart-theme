<?php

/**
 * New Password form template.
 *
 * @var array $args
 *
 * @package WordPress
 * @subpackage inheart
 */

$code		= $args['code'] ?? null;
$user_id	= $args['user_id'] ?? null;

if( ! $code || ! $user_id ) return;
?>

<form id="form-new-pass" class="form-new-pass wrap-gray">
	<fieldset>
		<legend><?php esc_html_e( 'Change Your Password', 'inheart' ) ?></legend>

		<?php
		echo ih_generate_form_field( ['name' => 'pass', 'label' => 'Password*', 'type' => 'password', 'label_class' => 'label-animated full'] );
		echo ih_generate_form_field( ['name' => 'pass2', 'label' => 'Confirm Password*', 'type' => 'password', 'label_class' => 'label-animated full'] );
		?>

		<input type="hidden" name="user-id" value="<?php echo esc_attr( $user_id ) ?>" />
		<input type="hidden" name="code" value="<?php echo esc_attr( $code ) ?>" />
		<?php wp_nonce_field( 'ih_ajax_new_password', 'ih_new_password_nonce' ) ?>
	</fieldset>

	<div class="form-buttons display-flex flex-wrap justify-center align-center">
		<button class="btn md" type="submit">
			<?php esc_html_e( 'Change Password', 'inheart' ) ?>
		</button>
		<a class="btn link accent underlined" href="<?php echo get_the_permalink( 10 ) ?>">
			<?php esc_html_e( 'Login', 'inheart' ) ?>
		</a>
	</div>

	<div class="form-lost display-flex justify-center">
		<a class="btn link accent underlined" href="<?php echo get_the_permalink( 12 ) ?>">
			<?php esc_html_e( 'Register', 'inheart' ) ?>
		</a>
	</div>

	<div class="note"></div>
</form>


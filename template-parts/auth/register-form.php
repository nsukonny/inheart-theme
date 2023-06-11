<?php

/**
 * Register form template.
 *
 * @see Theme Settings -> Forms -> Registration.
 *
 * @package WordPress
 * @subpackage inheart
 */

$http_referer = $_SERVER['HTTP_REFERER'] ?? '';
?>

<form id="form-register" class="form-register wrap-gray">
	<fieldset class="display-flex flex-wrap">
		<legend><?php esc_html_e( 'Register', 'inheart' ) ?></legend>

		<?php
		echo ih_generate_form_field( ['name' => 'form-register-firstname', 'label' => 'First Name*'] );
		echo ih_generate_form_field( ['name' => 'form-register-lastname', 'label' => 'Last Name*'] );
		echo ih_generate_form_field( ['name' => 'form-register-email', 'label' => 'Business Email*', 'type' => 'email', 'label_class' => 'label-animated full'] );
		echo ih_generate_form_field( ['name' => 'form-register-pass', 'label' => 'Password*', 'type' => 'password'] );
		echo ih_generate_form_field( ['name' => 'form-register-pass2', 'label' => 'Confirm Password*', 'type' => 'password'] );
		?>

		<input type="hidden" name="referer" value="<?php echo esc_attr( $http_referer ) ?>" />
		<?php wp_nonce_field( 'ih_ajax_register', 'ih_register_nonce' ) ?>
	</fieldset>

	<div class="form-buttons display-flex flex-wrap justify-center align-center">
		<button class="btn md" type="submit">
			<?php esc_html_e( 'Register', 'inheart' ) ?>
		</button>
		<a class="btn link accent underlined" href="<?php echo get_the_permalink( 10 ) ?>">
			<?php esc_html_e( 'Login', 'inheart' ) ?>
		</a>
	</div>

	<div class="form-lost display-flex justify-center">
		<a class="btn link accent underlined" href="<?php echo get_the_permalink( 14 ) ?>">
			<?php esc_html_e( 'Forgot Your Password?', 'inheart' ) ?>
		</a>
	</div>

	<div class="note"></div>
</form>


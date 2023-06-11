<?php

/**
 * Login form template.
 *
 * @package WordPress
 * @subpackage inheart
 */

$http_referer = $_SERVER['HTTP_REFERER'] ?? '';
?>

<form id="form-login" class="form-login wrap-gray">
	<div class="note"></div>

	<fieldset>
		<legend><?php esc_html_e( 'Login', 'inheart' ) ?></legend>

		<?php
		echo ih_generate_form_field( ['name' => 'form-login-email', 'label' => 'User Name or Email*', 'label_class' => 'label-animated full'] );
		echo ih_generate_form_field( ['name' => 'form-login-pass', 'label' => 'Password*', 'type' => 'password', 'label_class' => 'label-animated full'] );
		echo ih_generate_form_field( ['name' => 'remember-me', 'class' => 'checkbox', 'label' => 'Remember me', 'type' => 'checkbox', 'label_class' => 'label-checkbox'] );
		?>

		<input type="hidden" name="referer" value="<?php echo esc_attr( $http_referer ) ?>" />
		<?php wp_nonce_field( 'ih_ajax_login', 'ih_login_nonce' ) ?>
	</fieldset>

	<div class="form-buttons display-flex flex-wrap justify-center align-center">
		<button class="btn md" type="submit">
			<?php esc_html_e( 'Login', 'inheart' ) ?>
		</button>
		<a class="btn link accent underlined" href="<?php echo get_the_permalink( 299 ) ?>">
			<?php esc_html_e( 'Register', 'inheart' ) ?>
		</a>
	</div>

	<div class="form-lost display-flex justify-center">
		<a class="btn link accent underlined" href="<?php echo get_the_permalink( 518 ) ?>">
			<?php esc_html_e( 'Forgot Your Password?', 'inheart' ) ?>
		</a>
	</div>
</form>


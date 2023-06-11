<?php

/**
 * Lost Password form template.
 *
 * @package WordPress
 * @subpackage inheart
 */
?>

<form id="form-lostpass" class="form-lostpass wrap-gray">
	<fieldset>
		<legend><?php the_title() ?></legend>

		<?php echo ih_generate_form_field( ['name' => 'email', 'label' => 'User Name or Email*', 'label_class' => 'label-animated full'] ) ?>

		<?php wp_nonce_field( 'ih_ajax_lost_password', 'ih_lost_password_nonce' ) ?>
	</fieldset>

	<div class="form-buttons display-flex flex-wrap justify-center align-center">
		<button class="btn md" type="submit">
			<?php esc_html_e( 'Send Link', 'inheart' ) ?>
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


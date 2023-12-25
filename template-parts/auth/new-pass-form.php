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

<div class="auth-form-wrapper">
	<form id="form-new-pass" class="auth-form form-new-pass">
		<fieldset>
			<legend class="legend h3"><?php esc_html_e( 'Зміна паролю', 'inheart' ) ?></legend>

			<?php
			get_template_part( 'components/inputs/password', null, [
				'name'			=> 'pass',
				'label'			=> __( 'Ваш пароль', 'inheart' ),
				'label_class'	=> 'dark',
				'placeholder'	=> __( 'Пароль', 'inheart' ),
				'autocomplete'	=> 'new-password',
				'required'		=> 1
			] );
			get_template_part( 'components/inputs/password', null, [
				'name'			=> 'pass-confirm',
				'label'			=> __( 'Підтвердьте пароль', 'inheart' ),
				'label_class'	=> 'dark',
				'placeholder'	=> __( 'Пароль ще раз', 'inheart' ),
				'autocomplete'	=> 'new-password',
				'required'		=> 1
			] );
			?>

			<input type="hidden" name="user-id" value="<?php echo esc_attr( $user_id ) ?>" />
			<input type="hidden" name="code" value="<?php echo esc_attr( $code ) ?>" />
			<?php wp_nonce_field( 'ih_ajax_new_password', 'ih_new_password_nonce' ) ?>
		</fieldset>

		<div class="form-submit">
			<div class="note note-with-icon"></div>
			<button class="btn lg primary full" type="submit">
				<?php esc_html_e( 'Змінити пароль', 'inheart' ) ?>
			</button>
		</div>
	</form><!-- #form-new-pass -->

	<?php get_template_part( 'components/auth/additional-form' ) ?>
</div><!-- .auth-form-wrapper -->

<?php get_template_part( 'template-parts/auth/illustration' ) ?>


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

<div class="auth-form-wrapper">
	<form id="form-register" class="auth-form form-register">
		<fieldset class="flex direction-column">
			<legend class="legend h3"><?php esc_html_e( 'Реєстрація', 'inheart' ) ?></legend>

			<?php
			get_template_part( 'components/inputs/default', null, [
				'name'			=> 'email',
				'label'			=> __( 'Електронна пошта', 'inheart' ),
				'label_class'	=> 'dark',
				'type'			=> 'email',
				'placeholder'	=> __( 'Ваша електронна пошта', 'inheart' ),
				'autocomplete'	=> 'email',
				'required'		=> 1
			] );
			get_template_part( 'components/inputs/default', null, [
				'name'			=> 'fullname',
				'label'			=> __( "Ім'я та прізвище", 'inheart' ),
				'label_class'	=> 'dark',
				'placeholder'	=> __( "Ваші ім'я та прізвище", 'inheart' ),
				'autocomplete'	=> 'name',
				'required'		=> 1
			] );
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
			get_template_part( 'components/inputs/checkbox', null, [
				'name'			=> 'agreement',
				'label'			=> sprintf(
					__( 'Погоджуюсь з умовами %sположення про обробку і захист персональних даних%s та %sофертою%s', 'inheart' ),
					'<a href="/">', '</a>', '<a href="/">', '</a>',
				),
				'label_class'	=> 'dark',
				'required'		=> 1
			] );
			?>

			<input type="hidden" name="referer" value="<?php echo esc_attr( $http_referer ) ?>" />
			<?php wp_nonce_field( 'ih_ajax_register', 'ih_register_nonce' ) ?>
		</fieldset>

		<div class="form-submit">
			<div class="note note-with-icon"></div>
			<button class="btn lg primary full" type="submit">
				<?php esc_html_e( 'Зареєструватись', 'inheart' ) ?>
			</button>
		</div>
	</form><!-- #form-register -->

	<?php get_template_part( 'components/auth/additional-form' ) ?>
</div><!-- .auth-form-wrapper -->


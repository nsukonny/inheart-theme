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

			<label for="pass" class="label">
				<span class="label-text"><?php esc_html_e( 'Ваш пароль', 'inheart' ) ?></span>
				<span class="pass-wrapper">
					<input id="pass" name="pass" type="password" placeholder="<?php esc_html_e( 'Пароль', 'inheart' ) ?>" required />
					<img class="pass-toggle" src="<?php echo THEME_URI . '/static/img/eye-light.svg' ?>" alt="" />
				</span>
			</label>
			<label for="pass-confirm" class="label">
				<span class="label-text"><?php esc_html_e( 'Підтвердьте пароль', 'inheart' ) ?></span>
				<span class="pass-wrapper">
					<input id="pass-confirm" name="pass-confirm" type="password" placeholder="<?php esc_html_e( 'Пароль ще раз', 'inheart' ) ?>" required />
					<img class="pass-toggle" src="<?php echo THEME_URI . '/static/img/eye-light.svg' ?>" alt="" />
				</span>
			</label>

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

	<div class="auth-additional">
		<span class="auth-additional-title flex align-center">
			<span class="auth-additional-title-line before"></span>
			<span class="auth-additional-title-text">
				<?php esc_html_e( 'або за допомогою', 'inheart' ) ?>
			</span>
			<span class="auth-additional-title-line after"></span>
		</span>

		<div class="auth-google">
			<?php
			echo do_shortcode(
				'[google_login
				button_text="' . esc_html__( 'Авторізація через Google', 'inheart' ) . '"
				redirect_to="' . home_url() . '" /]'
			);
			?>
		</div>

		<div class="auth-additional-option">
			<?php
			printf(
				__( 'Я вже маю акаунт %sУвійти%s', 'inheart' ),
				'<a href="' . get_the_permalink( pll_get_post( 10 ) ) . '">', '</a>'
			);
			?>
		</div>
	</div>
</div><!-- .auth-form-wrapper -->

<?php get_template_part( 'template-parts/auth/illustration' ) ?>


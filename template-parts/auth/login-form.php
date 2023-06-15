<?php

/**
 * Login form template.
 *
 * @package WordPress
 * @subpackage inheart
 */

$http_referer = $_SERVER['HTTP_REFERER'] ?? '';
?>

<div class="auth-form-wrapper">
	<form id="form-login" class="auth-form form-login">
		<fieldset class="flex direction-column">
			<legend class="legend h3"><?php esc_html_e( 'Вхід в особистий кабінет', 'inheart' ) ?></legend>

			<label for="email" class="label">
				<span class="label-text"><?php esc_html_e( 'Ваша пошта', 'inheart' ) ?></span>
				<input id="email" name="email" type="text" placeholder="<?php esc_html_e( 'Пошта', 'inheart' ) ?>" required />
			</label>
			<label for="pass" class="label">
				<span class="label-text"><?php esc_html_e( 'Ваш пароль', 'inheart' ) ?></span>
				<span class="pass-wrapper">
					<input id="pass" name="pass" type="password" placeholder="<?php esc_html_e( 'Пароль', 'inheart' ) ?>" required />
					<img class="pass-toggle" src="<?php echo THEME_URI . '/static/img/eye-light.svg' ?>" alt="" />
				</span>
				<span class="lostpass-wrapper">
					<a class="link bright-yellow" href="<?php echo get_the_permalink( 14 ) ?>">
						<?php esc_html_e( "Не пам'ятаю пароль", 'inheart' ) ?>
					</a>
				</span>
			</label>

			<input type="hidden" name="referer" value="<?php echo esc_attr( $http_referer ) ?>" />
			<?php wp_nonce_field( 'ih_ajax_login', 'ih_login_nonce' ) ?>
		</fieldset>

		<div class="form-submit">
			<div class="note note-with-icon"></div>
			<button class="btn lg primary full" type="submit">
				<?php esc_html_e( 'Увійти', 'inheart' ) ?>
			</button>
		</div>
	</form><!-- #form-login -->

	<div class="auth-additional">
		<span class="auth-additional-title flex align-center">
			<span class="auth-additional-title-line before"></span>
			<span class="auth-additional-title-text">
				<?php esc_html_e( 'або за допомогою', 'inheart' ) ?>
			</span>
			<span class="auth-additional-title-line after"></span>
		</span>

		<div class="auth-google">
			<button class="btn lg secondary full">
				<?php esc_html_e( 'Авторізація через Google', 'inheart' ) ?>
			</button>
		</div>

		<div class="auth-additional-option">
			<?php
			printf(
				__( 'Ще не зареєстровані? %sСтворити акаунт %s', 'inheart' ),
				'<a href="' . get_the_permalink( 12 ) . '">', '</a>'
			);
			?>
		</div>
	</div>
</div><!-- .form-login-wrapper -->


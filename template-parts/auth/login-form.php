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
				<input id="email" name="email" type="text" placeholder="<?php esc_html_e( 'Пошта', 'inheart' ) ?>" />
			</label>
			<label for="pass" class="label">
				<span class="label-text"><?php esc_html_e( 'Ваш пароль', 'inheart' ) ?></span>
				<span class="pass-wrapper">
					<input id="pass" name="pass" type="password" placeholder="<?php esc_html_e( 'Пароль', 'inheart' ) ?>" />
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
			<button class="btn lg primary full" type="submit">
				<?php esc_html_e( 'Увійти', 'inheart' ) ?>
			</button>

			<div class="note"></div>
		</div>
	</form><!-- .form-login -->
</div><!-- .form-login-wrapper -->


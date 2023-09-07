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

			<label for="email" class="label">
				<span class="label-text"><?php esc_html_e( 'Електронна пошта', 'inheart' ) ?></span>
				<input id="email" name="email" type="email" placeholder="<?php esc_html_e( 'Ваша електронна пошта', 'inheart' ) ?>" required />
			</label>
			<label for="fullname" class="label">
				<span class="label-text"><?php esc_html_e( "Ім'я та прізвище", 'inheart' ) ?></span>
				<input id="fullname" name="fullname" type="text" placeholder="<?php esc_html_e( "Ваші ім'я та прізвище", 'inheart' ) ?>" required />
			</label>
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
			<div class="checkbox-wrapper">
				<input id="agreement" name="agreement" type="checkbox" required />
				<label for="agreement" class="label-checkbox">
					<?php
					printf(
						__( 'Погоджуюсь з умовами %sположення про обробку і захист персональних даних%s та %sофертою%s', 'inheart' ),
						'<a href="/">', '</a>', '<a href="/">', '</a>',
					);
					?>
				</label>
			</div>

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


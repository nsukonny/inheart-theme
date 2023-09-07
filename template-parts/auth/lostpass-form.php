<?php

/**
 * Lost Password form template.
 *
 * @package WordPress
 * @subpackage inheart
 */
?>

<div class="auth-form-wrapper">
	<form id="form-lostpass" class="auth-form form-lostpass">
		<fieldset>
			<legend class="legend h3"><?php the_title() ?></legend>

			<label for="email" class="label">
				<span class="label-text"><?php esc_html_e( 'Ваша пошта', 'inheart' ) ?></span>
				<input id="email" name="email" type="text" placeholder="<?php esc_html_e( 'Пошта', 'inheart' ) ?>" required />
			</label>

			<?php wp_nonce_field( 'ih_ajax_lost_password', 'ih_lost_password_nonce' ) ?>
		</fieldset>

		<div class="form-submit">
			<div class="note note-with-icon"></div>
			<button class="btn lg primary full" type="submit">
				<?php esc_html_e( 'Оновити', 'inheart' ) ?>
			</button>
		</div>
	</form><!-- #form-lostpass -->

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
				__( 'Ще не зареєстровані? %sСтворити акаунт %s', 'inheart' ),
				'<a href="' . get_the_permalink( pll_get_post( 12 ) ) . '">', '</a>'
			);
			?>
		</div>
	</div>
</div>

<?php get_template_part( 'template-parts/auth/illustration' ) ?>


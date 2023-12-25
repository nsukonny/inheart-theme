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

			<?php
			get_template_part( 'components/inputs/default', null, [
				'name'			=> 'email',
				'label'			=> __( 'Ваша пошта', 'inheart' ),
				'label_class'	=> 'dark',
				'placeholder'	=> __( 'Пошта', 'inheart' ),
				'autocomplete'	=> 'email',
				'required'		=> 1
			] );
			?>

			<?php wp_nonce_field( 'ih_ajax_lost_password', 'ih_lost_password_nonce' ) ?>
		</fieldset>

		<div class="form-submit">
			<div class="note note-with-icon"></div>
			<button class="btn lg primary full" type="submit">
				<?php esc_html_e( 'Оновити', 'inheart' ) ?>
			</button>
		</div>
	</form><!-- #form-lostpass -->

	<?php get_template_part( 'components/auth/additional-form' ) ?>
</div>

<?php get_template_part( 'template-parts/auth/illustration' ) ?>


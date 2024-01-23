<?php

/**
 * Additional login form.
 *
 * @package WordPress
 * @subpackage inheart
 */

$type = $args['type'] ?? 'login';

switch( $type ){
	case 'register':
		$text = sprintf(
			__( 'Я вже маю акаунт %sУвійти%s', 'inheart' ),
			'<a href="' . get_the_permalink( pll_get_post( ih_get_login_page_id() ) ) . '">', '</a>'
		);
		break;

	default:
		$text = sprintf(
			__( 'Ще не зареєстровані? %sСтворити акаунт %s', 'inheart' ),
			'<a href="' . get_the_permalink( pll_get_post( ih_get_registration_page_id() ) ) . '">', '</a>'
		);
		break;
}
?>

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
		<?php echo $text ?>
	</div>
</div>


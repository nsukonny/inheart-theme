<?php

/**
 * Login form template.
 *
 * @package WordPress
 * @subpackage inheart
 */

$http_referer		= $_SERVER['HTTP_REFERER'] ?? '';
$comes_from_memory	= isset( $_GET['memory'] ) && $_GET['memory'] == 1;
$memory_tip			= get_field( 'memory_page_creation_tip' );
?>

<div class="auth-form-wrapper">
	<?php
	// Visitor tried to create memory, but needs to be authorized first.
	if( $comes_from_memory && $memory_tip ){
		?>
		<div class="memory-tip">
			<?php echo $memory_tip ?>
			<button title="<?php esc_attr_e( 'Закрити', 'inheart' ) ?>">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M3.40234 3.40332L12.5947 12.5957" stroke="#011C1A" stroke-width="1.2"/>
					<path d="M3.40234 12.5967L12.5947 3.40429" stroke="#011C1A" stroke-width="1.2"/>
				</svg>
			</button>
		</div>
		<?php
	}
	?>

	<form id="form-login" class="auth-form form-login">
		<fieldset class="flex direction-column">
			<legend class="legend h3"><?php esc_html_e( 'Вхід в особистий кабінет', 'inheart' ) ?></legend>

			<?php
			get_template_part( 'components/inputs/default', null, [
				'name'			=> 'email',
				'label'			=> __( 'Ваша пошта', 'inheart' ),
				'label_class'	=> 'dark',
				'placeholder'	=> __( 'Пошта', 'inheart' ),
				'autocomplete'	=> 'email',
				'required'		=> 1
			] );
			get_template_part( 'components/inputs/password', null, [
				'name'			=> 'pass',
				'label'			=> __( 'Ваш пароль', 'inheart' ),
				'label_class'	=> 'dark',
				'placeholder'	=> __( 'Пароль', 'inheart' ),
				'lost_pass'		=> 1
			] );
			?>

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

	<?php get_template_part( 'components/auth/additional-form' ) ?>
</div><!-- .auth-form-wrapper -->


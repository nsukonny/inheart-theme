<?php

/**
 * Authorization page - User just registered, success screen.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $user_id = $args['user_id'] ?? null ) return;

// No code - user doesn't need activation.
if( ! get_user_meta( $user_id, 'activation_code', true ) ){
	esc_html_e( 'Цей акаунт вже активований!', 'inheart' );
	?>
	<a href="<?php echo get_the_permalink( pll_get_post( 10 ) ) ?>" class="btn md">
		<?php esc_html_e( 'Увійти', 'inheart' ) ?>
	</a>
	<?php
	return;
}

$success_img	= get_field( 'registration_success_img' );
$success_text	= get_field( 'registration_success_text' );
?>

<div class="registration-success">
	<div class="container">
		<div class="registration-success-inner flex direction-column align-center">
			<?php
			if( $success_img )
				echo wp_get_attachment_image( $success_img['id'], 'large', null, ['width' => 197, 'height' => 194] );

			if( $success_text ) echo $success_text;
			?>
		</div>
	</div>
</div>


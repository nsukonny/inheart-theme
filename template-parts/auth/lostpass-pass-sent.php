<?php

/**
 * Lost Password page - pass change email sent screen.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $user_id = $args['user_id'] ?? null ) return;

// No code - user doesn't need pass change.
if(
	! get_user_meta( $user_id, 'pass_recovery_code', true ) ||
	! get_user_meta( $user_id, 'pass_recovery_date', true )
){
	printf(
		esc_html__( 'Цей Користувач не просив оновлення паролю. %sУвійти%s', 'inheart' ),
		'<a href="' . get_the_permalink( pll_get_post( 10 ) ) . '">', '</a>'
	);
	return;
}

$success_img	= get_field( 'lostpass_sent_img' );
$success_text	= get_field( 'lostpass_sent_text' );
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


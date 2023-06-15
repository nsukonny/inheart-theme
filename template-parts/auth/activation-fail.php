<?php

/**
 * Authorization page - account activation fail screen.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $err_msg = $args['msg'] ?? null ) return;

$err_img = get_field( 'activation_fail_img' );
?>

<div class="activation-fail">
	<div class="container">
		<div class="registration-success-inner flex direction-column align-center">
			<?php
			if( $err_img )
				echo wp_get_attachment_image( $err_img['id'], 'large', null, ['width' => 197, 'height' => 194] );

			echo "<p>$err_msg</p>";
			?>
		</div>
	</div>
</div>


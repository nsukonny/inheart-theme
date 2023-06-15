<?php

/**
 * Authorization page - account activation success screen.
 *
 * @package WordPress
 * @subpackage inheart
 */

$success_img	= get_field( 'activation_success_img' );
$success_msg	= get_field( 'activation_success_text' );
?>

<div class="activation-success">
	<div class="container">
		<div class="registration-success-inner flex direction-column align-center">
			<?php
			if( $success_img )
				echo wp_get_attachment_image( $success_img['id'], 'large', null, ['width' => 197, 'height' => 194] );
			echo $success_msg;
			?>
		</div>
	</div>
</div>


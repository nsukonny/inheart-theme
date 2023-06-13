<?php

/**
 * Login page illustration.
 *
 * @see Page -> Page Template: Login
 *
 * @package WordPress
 * @subpackage inheart
 */

$illustration = get_field( 'illustration' );
?>

<div class="auth-illustration auth-illustration-login justify-center">
	<div class="auth-illustration-inner">
		<?php if( $illustration ) echo wp_get_attachment_image( $illustration['id'], 'ih-illustration' ) ?>
	</div>
</div>


<?php

/**
 * Authorization page - if Visitor is already logged in.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $user = wp_get_current_user() ) return;
?>

<div class="already-logged-in">
	<?php printf( esc_html__( 'Hello, %s! You are already logged in.', 'inheart' ), $user->display_name ) ?>

	<a href="<?php echo home_url( '/' ) ?>">
		<?php esc_html_e( 'Go Home', 'inheart' ) ?>
	</a>
</div>


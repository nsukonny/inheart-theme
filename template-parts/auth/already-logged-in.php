<?php

/**
 * Authorization page - if Visitor is already logged in.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $user = wp_get_current_user() ) return;
?>

<div class="activation-success">
	<div class="container">
		<div class="registration-success-inner flex direction-column align-center">
			<p>
				<?php
				printf(
					esc_html__( 'Вітаємо, %s! Ви вже зайшли на сайт. %sДо Профілю%s', 'inheart' ),
					$user->display_name, '<a href="' . get_the_permalink( pll_get_post( 951 ) ) . '">', '</a>'
				);
				?>
			</p>
		</div>
	</div>
</div>


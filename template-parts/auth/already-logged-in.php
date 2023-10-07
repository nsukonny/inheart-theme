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
					esc_html__( 'Вітаємо, %s! Ви вже зайшли на сайт. %sУ Профіль%s', 'inheart' ),
					$user->display_name, '<a href="' . home_url( '/profile' ) . '">', '</a>'
				);
				?>
			</p>
		</div>
	</div>
</div>


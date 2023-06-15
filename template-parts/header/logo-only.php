<?php

/**
 * aasdas
 */

if( ! $logo = get_field( 'header_logo_light', 'option' ) ) return;
?>

<header class="header logo-only">
	<div class="container fluid">
		<div class="header-logo flex align-center">
			<a href="<?php echo home_url( '/' ) ?>">
				<?php echo wp_get_attachment_image( $logo['id'], 'ih-logo' ) ?>
			</a>

			<?php
			if( is_user_logged_in() ){
				?>
				<button class="logout">Logout</button>
				<?php
			}
			?>
		</div>
	</div>
</header>


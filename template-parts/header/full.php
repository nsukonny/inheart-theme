<?php

/**
 * Header template.
 *
 * @see Options -> Header.
 *
 * @package WordPress
 * @subpackage inheart
 */

$logo = get_field( 'header_logo_dark', 'option' );
?>

<header class="header full">
	<div class="container fluid">
		<div class="header-inner flex flex-wrap align-center">
			<div class="header-menu">
				<button class="header-menu-button flex align-center" type="button">
					<span class="header-menu-button-lines">
						<span></span>
						<span></span>
						<span></span>
					</span>
					<?php esc_html_e( 'Меню', 'inheart' ) ?>
				</button>
			</div>

			<?php
			if( $logo ){
				?>
				<div class="header-logo flex align-center">
					<a href="<?php echo home_url( '/' ) ?>">
						<?php echo wp_get_attachment_image( $logo['id'], 'ih-logo' ) ?>
					</a>
				</div>
				<?php
			}
			?>
		</div><!-- .header-inner -->
	</div><!-- .container -->
</header><!-- .header -->


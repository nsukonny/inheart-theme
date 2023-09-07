<?php

/**
 * Header template.
 *
 * @see Options -> Header.
 *
 * @package WordPress
 * @subpackage inheart
 */

$logo_dark	= get_field( 'header_logo_dark', 'option' );
$logo_light	= get_field( 'header_logo_light', 'option' );
?>

<header class="header full">
	<div class="container fluid">
		<div class="header-inner flex flex-wrap align-center">
			<div class="header-menu flex align-center">
				<button
					class="header-menu-button flex align-center"
					type="button"
					aria-label="<?php esc_attr_e( 'Відкрити меню', 'inheart' ) ?>"
				>
					<span class="header-menu-button-lines">
						<span></span>
						<span></span>
						<span></span>
					</span>
					<?php esc_html_e( 'Меню', 'inheart' ) ?>
				</button>

				<ul class="header-langs flex">
					<?php pll_the_languages() ?>
				</ul>
			</div>

			<div class="header-logo flex align-center">
				<a href="<?php echo home_url( '/' ) ?>">
					<?php
					echo wp_get_attachment_image( $logo_dark['id'], 'ih-logo', false, ['class' => 'dark'] );
					echo wp_get_attachment_image( $logo_light['id'], 'ih-logo', false, ['class' => 'light'] );
					?>
				</a>
			</div>

			<?php get_template_part( 'template-parts/header/profile' ) ?>
		</div><!-- .header-inner -->
	</div><!-- .container -->
</header><!-- .header -->


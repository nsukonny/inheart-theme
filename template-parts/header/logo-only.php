<?php

/**
 * Header template logo only.
 *
 * @see Options -> Header.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $logo = get_field( 'header_logo_light', 'option' ) ) return;
?>

<header class="header logo-only">
	<div class="container fluid">
		<div class="header-inner flex align-center justify-between">
			<div class="header-logo flex align-center">
				<a href="<?php echo home_url( '/' ) ?>">
					<?php echo wp_get_attachment_image( $logo['id'], 'ih-logo' ) ?>
				</a>
			</div>

			<ul class="header-langs flex">
				<?php pll_the_languages() ?>
			</ul>
		</div>
	</div>
</header>


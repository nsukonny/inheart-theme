<?php

/**
 * Header template logo only.
 *
 * @see Options -> Header.
 *
 * @package WordPress
 * @subpackage inheart
 */

 $logo_dark  = get_field( 'header_logo_dark', 'option' );
 $logo_light = get_field( 'header_logo_light', 'option' );
 $active_back = true;

if( ! $logo = get_field( 'header_logo_light', 'option' ) ) return;
?>

<header class="header logo-only logo-only-btn">
	<div class="container fluid">
		<div class="header-inner header-logo-custom flex align-center justify-between">
			<div><button class="new-memory-prev-step button dark sm button-icon-lead<?php echo ( $active_back ? '' : ' hidden' ) ?>" data-prev="0">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<mask id="mask0_2655_45267" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="6" y="3" width="12" height="18">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M15.7049 20.4429L7.2619 11.9999L15.7049 3.55688C16.0969 3.16588 16.0969 2.53488 15.7049 2.14288C15.3129 1.75188 14.6819 1.75188 14.2899 2.14288L5.1419 11.2909C4.9469 11.4869 4.8359 11.7529 4.8359 11.9999C4.8359 12.2469 4.9469 12.5129 5.1419 12.7089L14.2899 21.8569C14.6819 22.2479 15.3129 22.2479 15.7049 21.8569C16.0969 21.4649 16.0969 20.8339 15.7049 20.4429Z" fill="black"/>
				</mask>
				<g mask="url(#mask0_2655_45267)">
					<rect width="24" height="24" fill="currentColor"/>
				</g>
			</svg>
			<?php _e( 'Назад', 'inheart' ) ?>
		</button></div>
			<div><div class="header-logo flex align-center">
				<a href="<?php echo ih_get_logo_url() ?>">
					<?php
					echo wp_get_attachment_image( $logo_dark['id'], 'ih-logo', false, ['class' => 'dark'] );
					echo wp_get_attachment_image( $logo_light['id'], 'ih-logo', false, ['class' => 'light'] );
					?>
				</a>
			</div></div>
			<div></div>
		</div>
	</div>
</header>


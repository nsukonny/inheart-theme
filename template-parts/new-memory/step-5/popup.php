<?php

/**
 * New Memory page template.
 * Step 5. Coords popup.
 *
 * @see Page Template: New Memory -> Step 1
 *
 * @package WordPress
 * @subpackage inheart
 */

$popup_text		= get_field( 'coords_popup_text' );
$popup_image	= get_field( 'coords_popup_image' );

if( ! $popup_text && ! $popup_image ) return;
?>

<div id="coords-popup" class="popup coords-popup hidden">
	<div class="popup-inner flex flex-wrap">
		<?php
		if( $popup_text ) echo '<div class="coords-popup-text">' . $popup_text . '</div>';

		if( $popup_image )
			echo '<div class="coords-popup-image">'
				. wp_get_attachment_image( $popup_image['id'], 'ih-smartphone' ) .
			'</div>';
		?>

		<button class="coords-popup-close" type="button" aria-label="<?php esc_attr_e( 'Закрити', 'inheart' ) ?>">
			<?php esc_html_e( 'Закрити', 'inheart' ) ?>
		</button>
	</div>
</div>


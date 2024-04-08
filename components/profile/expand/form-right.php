<?php

/**
 * Profile expand memory page to full.
 * Form right part.
 *
 * @package WordPress
 * @subpackage inheart
 */

$page_to_expand = $args['expand'] ?? '';
$photo			= get_field( 'photo_placeholder' );
$advantages		= get_field( 'advantages' );
$advantages_pro	= get_field( 'advantages_pro' );
$title_qr		= get_field( 'title_qr' );
$photo_qr		= get_field( 'photo_qr' );
$desc_qr		= get_field( 'desc_qr' );
$full_price		= 0;

if( $page_to_expand ){
	$page_theme = get_field( 'theme', $page_to_expand );

	if( $page_theme === 'military' )
		$full_price = ih_get_metal_qr_military_price();
	else
		$full_price = ih_get_expanded_page_order_price();
}else{
	$full_price = ih_get_expanded_page_order_price();
}
?>

<div class="expand-page-form-right flex direction-column">
	<fieldset>
		<div class="advantages-header flex flex-wrap align-center">
			<?php if( $photo ) echo '<div class="advantages-header-img">', wp_get_attachment_image( $photo, 'ih-logo' ), '</div>' ?>

			<h3 class="advantages-header-title"><?php _e( "Розширена сторінка пам'яті", 'inheart' ) ?></h3>
		</div>

		<?php
		if( $advantages ){
			echo '<div class="advantages flex flex-wrap">';
			array_map( function( $item ){
				echo '<div class="advantage">', esc_html( $item ), '</div>';
			}, explode( ',', $advantages ) );
			echo '</div>';
		}

		if( $advantages_pro ){
			echo '<div class="advantages pro flex flex-wrap">';
			array_map( function( $item ){
				echo '<div class="advantage pro">', esc_html( $item ), '</div>';
			}, explode( ',', $advantages_pro ) );
			echo '</div>';
		}
		?>
	</fieldset>

	<fieldset class="qr-wrap flex flex-wrap">
		<?php
		if( $photo_qr )
			echo '<div class="qr-image">',
				wp_get_attachment_image( $photo_qr, '', false, ['loading' => 'lazy'] ),
			'</div>';

		if( $title_qr || $desc_qr ){
			echo '<div class="qr-body">';

			if( $title_qr ) echo '<h4 class="qr-title">', esc_html( $title_qr ), '</h4>';

			if( $desc_qr ) echo '<div class="qr-desc">', esc_html( $desc_qr ), '</div>';

			echo '</div>';
		}
		?>
	</fieldset>

	<div class="qr-count flex flex-wrap align-center justify-between">
		<span class="qr-count-label"><?php _e( 'Кількість', 'inheart' ) ?></span>

		<div class="qr-count-buttons flex align-center">
			<button class="button qty minus" disabled type="button"></button>
			<input type="text" name="qr-count-qty" id="qr-count-qty" class="qr-count-qty" value="1" />
			<button class="button qty plus" type="button"></button>
		</div>
	</div>

	<button class="button primary lg fw full-price-btn" type="submit">
		<?php
		printf(
			__( 'Замовити за %s%s%s грн', 'inheart' ),
			'<span>',
			number_format( $full_price, 0, '', ' ' ),
			'</span>'
		);
		?>
	</button>
</div>


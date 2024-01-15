<?php

/**
 * Profile expand memory page to full.
 * Form right part.
 *
 * @package WordPress
 * @subpackage inheart
 */

$photo			= get_field( 'photo_placeholder' );
$advantages		= get_field( 'advantages' );
$advantages_pro	= get_field( 'advantages_pro' );
$title_qr		= get_field( 'title_qr' );
$photo_qr		= get_field( 'photo_qr' );
$desc_qr		= get_field( 'desc_qr' );
$expanded_id	= get_field( 'expanded_memory_page', 'option' );
$qr_id			= get_field( 'qr_code_metal', 'option' );
$full_price		= 0;

if( $expanded_id && get_post_type( $expanded_id ) === 'production' ) $full_price += get_field( 'price', $expanded_id );

if( $qr_id && get_post_type( $qr_id ) === 'production' ) $full_price += get_field( 'price', $qr_id );
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
			<span class="qr-count-qty">1</span>
			<button class="button qty plus" type="button"></button>
		</div>
	</div>

	<button class="button primary lg fw" type="submit">
		<?php printf( __( 'Замовити за %s грн', 'inheart' ), number_format( $full_price, 0, '', ' ' ) ) ?>
	</button>
</div>


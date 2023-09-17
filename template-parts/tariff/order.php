<?php

/**
 * Tariff page template.
 * Your Order part.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $memory_page_id = $args['id'] ?? null ) return;

$thumb		= has_post_thumbnail( $memory_page_id ) ? get_the_post_thumbnail( $memory_page_id, 'thumbnail' ) : null;
$first_name	= get_field( 'first_name', $memory_page_id ) . ' ' . get_field( 'middle_name', $memory_page_id );
$last_name	= get_field( 'last_name', $memory_page_id );
$born_date	= get_field( 'born_at', $memory_page_id );
$born_date	= $born_date ? date( 'd.m.Y', strtotime( $born_date ) ) : '';
$died_date	= get_field( 'died_at', $memory_page_id );
$died_date	= $died_date ? date( 'd.m.Y', strtotime( $died_date ) ) : '';
$rust_image	= get_field( 'rust_image', 'option' );
$rust_text	= get_field( 'rust_text', 'option' );
?>

<div class="tariff-order">
	<div class="tariff-order-header flex flex-wrap">
		<?php if( $thumb ) echo '<div class="tariff-order-thumb">' . $thumb . '</div>' ?>

		<div class="tariff-order-header-info flex direction-column justify-center">
			<?php
			if( $first_name ) echo '<div class="tariff-order-firstname">' . esc_html( $first_name ) . '</div>';

			if( $last_name ) echo '<div class="tariff-order-lastname">' . esc_html( $last_name ) . '</div>';

			if( $born_date && $died_date )
				echo '<div class="tariff-order-dates">' . esc_html( $born_date ) . ' — ' . esc_html( $died_date ) . '</div>';
			?>
		</div>
	</div>

	<div class="tariff-order-rust flex flex-wrap">
		<?php if( $rust_image ): ?>
			<div class="tariff-order-rust-image">
				<?php echo wp_get_attachment_image( $rust_image['id'], 'large' ) ?>
			</div>
		<?php endif ?>

		<div class="tariff-order-rust-body">
			<?php if( $rust_text ): ?>
				<div class="tariff-order-rust-text"><?php echo $rust_text ?></div>
			<?php endif ?>

			<div class="tariff-order-rust-qr">
				<?php
				printf(
					__( 'Замовити металевий QR-код за %s0%s грн', 'inheart' ),
					'<span class="tariff-order-rust-qr-price">', '</span>'
				);?>
			</div>
		</div>
	</div>
</div>


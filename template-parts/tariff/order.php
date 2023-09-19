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
				<span class="tariff-order-rust-qr-checkbox">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M3 5.68182C3 4.20069 4.20069 3 5.68182 3H14.3182C15.7993 3 17 4.20069 17 5.68182V14.3182C17 15.7993 15.7993 17 14.3182 17H5.68182C4.20069 17 3 15.7993 3 14.3182V5.68182ZM13.8536 7.85355C14.0488 7.65829 14.0488 7.34171 13.8536 7.14645C13.6583 6.95118 13.3417 6.95118 13.1464 7.14645L8.5 11.7929L6.85355 10.1464C6.65829 9.95118 6.34171 9.95118 6.14645 10.1464C5.95118 10.3417 5.95118 10.6583 6.14645 10.8536L8.14645 12.8536C8.34171 13.0488 8.65829 13.0488 8.85355 12.8536L13.8536 7.85355Z" fill="#C69A39"/>
					</svg>
				</span>
				<?php
				printf(
					__( 'Замовити металевий QR-код за %s0%s грн', 'inheart' ),
					'<span class="tariff-order-rust-qr-price">', '</span>'
				);?>
			</div>
		</div>
	</div>

	<form class="tariff-order-promo">
		<fieldset class="flex align-end">
			<label for="promo" class="label dark">
				<span class="label-text"><?php esc_html_e( 'Промо-код', 'inheart' ) ?></span>
				<input
					id="promo"
					name="promo"
					type="text"
					placeholder="<?php esc_attr_e( 'Ваш промо-код (якщо маєте)', 'inheart' ) ?>"
				/>
			</label>
			<button
				class="btn lg primary"
				title="<?php esc_attr_e( 'Застосувати', 'inheart' ) ?>"
				type="submit"
				disabled
			>
				<?php esc_attr_e( 'Застосувати', 'inheart' ) ?>
			</button>
		</fieldset>
	</form>

	<div class="tariff-order-table">
		<div class="tariff-order-row plan flex flex-wrap justify-between align-center">
			<div class="tariff-order-col plan-name"></div>
			<div class="tariff-order-col plan-price"><span>0</span> <?php _e( 'грн / рік', 'inheart' ) ?></div>
		</div>
		<div class="tariff-order-row qr flex flex-wrap justify-between align-center">
			<div class="tariff-order-col qr-name"><?php _e( 'QR-код на металевій пластині', 'inheart' ) ?></div>
			<div class="tariff-order-col qr-price"><span>0</span> <?php _e( 'грн', 'inheart' ) ?></div>
		</div>
		<div class="tariff-order-row discount flex flex-wrap justify-between align-center">
			<div class="tariff-order-col discount-name"><?php _e( 'Знижка', 'inheart' ) ?></div>
			<div class="tariff-order-col discount-price">-<span>0</span> <?php _e( 'грн', 'inheart' ) ?></div>
		</div>
		<div class="tariff-order-row total flex flex-wrap justify-between align-center">
			<div class="tariff-order-col total-name"><?php _e( 'Усього', 'inheart' ) ?></div>
			<div class="tariff-order-col total-price"><span>0</span> <?php _e( 'грн', 'inheart' ) ?></div>
		</div>
	</div>

	<div class="tariff-order-purchase">
		<button class="btn lg primary full tariff-order-purchase-btn">
			<?php _e( 'До сплати', 'inheart' ) ?>
		</button>
	</div>
</div>


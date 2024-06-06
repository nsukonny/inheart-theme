<?php

/**
 * Order QR now - popup for New Memory page template.
 *
 * @package    WordPress
 * @subpackage inheart
 */

$memory_page_id     = $_SESSION['memory_page_id'] ?? 0;
$img_desktop        = get_field( 'order_qr_img_desktop' );
$img_mobile         = get_field( 'order_qr_img_mobile' ) ?: $img_desktop;
$title              = get_field( 'order_qr_title' ) ?: '';
$text               = get_field( 'order_qr_text' ) ?: '';
$btn_continue_label = get_field( 'order_qr_btn_continue_text' ) ?: '';
$note               = get_field( 'order_qr_note' ) ?: '';
$full_price         = ih_get_expanded_page_order_price( 1, true );
?>

<div class="popup order-now hidden">
	<div class="popup-inner flex flex-wrap">
		<?php
		if( $img_desktop ){
			?>
			<div class="order-now-image">
				<picture>
					<source
						media="(max-width:768px)"
						srcset="<?php echo wp_get_attachment_image_url( $img_mobile, 'ih-content-half' ) ?>"
					>
					<img
						class="c-image"
						src="<?php echo wp_get_attachment_image_url( $img_desktop, 'ih-content-half' ) ?>" alt=""
					/>
				</picture>
			</div>
			<?php
		}

		if( $title || $text ){
			echo '<div class="order-now-body flex direction-column justify-center">';

			if( $title ) echo '<h3 class="order-now-title">', esc_html( $title ), '</h3>';

			if( $text ) echo '<p class="order-now-text">', $text, '</p>';
			?>

			<a
				href="<?php echo get_the_permalink( pll_get_post( ih_get_profile_page_id() ) ), "?expand=", esc_attr( $memory_page_id ) ?>"
				class="button primary lg order-now-submit"
			>
				<?php _e( "Замовити QR-код за $full_price грн", 'inheart' ) ?>
			</a>

			<button class="btn lg outlined order-now-decline">
				<?php echo esc_html( $btn_continue_label ) ?>
			</button>

			<?php
			if( $note ) echo '<p class="order-now-note">', esc_html( $note ), '</p>';

			echo '</div>';
		}
		?>
	</div>
</div>


<?php

/**
 * Memory single page.
 * Map section.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $id = $args['id'] ?? null ) return;

$address		= get_field( 'address', $id );
$detail_address	= get_field( 'detail_address', $id );
$longitude		= get_field( 'longitude', $id );
$latitude		= get_field( 'latitude', $id );
$how_to_find	= get_field( 'how_to_find', $id );
?>

<section class="single-memory-place">
	<div class="container">
		<h2 class="single-memory-heading">
			<?php esc_html_e( 'Остання адреса', 'inheart' ) ?>
		</h2>

		<div class="single-memory-place-inner flex flex-wrap">
			<div id="map" class="map" data-long="<?php echo esc_attr( $longitude ) ?>" data-lat="<?php echo esc_attr( $latitude ) ?>"></div>

			<div class="map-desc">
				<?php
				if( $address ) echo '<div class="map-desc-title map-address">' . esc_html( $address ) . '</div>';

				if( $detail_address ){
					?>
					<div class="map-address-detail flex flex-wrap align-center">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<mask id="mask0_3149_42490" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="5" y="2" width="14" height="20">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M5 9.26562C5 5.40563 8.14 2.26562 12 2.26562C15.86 2.26562 19 5.40563 19 9.26562C19 13.4356 14.58 19.1856 12.77 21.3756C12.37 21.8556 11.64 21.8556 11.24 21.3756C9.42 19.1856 5 13.4356 5 9.26562ZM13 10.2656H15C15.55 10.2656 16 9.81563 16 9.26562C16 8.71562 15.55 8.26562 15 8.26562H13V6.26562C13 5.71563 12.55 5.26562 12 5.26562C11.45 5.26562 11 5.71563 11 6.26562V8.26562H9C8.45 8.26562 8 8.71562 8 9.26562C8 9.81563 8.45 10.2656 9 10.2656H11V16.2656C11 16.8156 11.45 17.2656 12 17.2656C12.55 17.2656 13 16.8156 13 16.2656V10.2656Z" fill="black"/>
							</mask>
							<g mask="url(#mask0_3149_42490)">
								<rect width="24" height="24" fill="#F7B941"/>
							</g>
						</svg>
						<span><?php echo esc_html( $detail_address ) ?></span>
					</div>
					<?php
				}

				if( $how_to_find ){
					?>
					<div class="map-howto">
						<div class="map-desc-title">
							<?php esc_html_e( 'Як знайти поховання', 'inheart' ) ?>
						</div>
						<?php echo esc_html( $how_to_find ) ?>
					</div>
					<?php
				}

				if( $longitude && $latitude ){
					?>
					<div class="map-coords">
						<span><?php esc_html_e( 'Точні координати:', 'inheart' ) ?></span>
						<?php echo esc_html( "$longitude, $latitude" ) ?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</section>

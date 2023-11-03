<?php

/**
 * Memory single page.
 * Media section - Photos.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! ( $id = $args['id'] ?? null ) || ! ( $photos = $args['photos'] ?? null ) ) return;

$lang = $args['lang'] ?? 'uk';
?>

<div class="media-photos">
	<div class="single-memory-media-title">
		<?php echo pll_translate_string( 'Фото', $lang ) ?>
	</div>

	<div class="media-photos-list flex flex-wrap">
		<?php
		foreach( $photos as $key => $photo ){
			$class = $key > 3 ? ' hidden' : '';
			?>
			<a
				href="<?php echo wp_get_attachment_image_url( $photo, 'full' ) ?>"
				class="media-photo<?php echo esc_attr( $class ) ?>"
				data-lightbox="media-photos"
			>
				<div class="media-photo-inner">
					<?php echo wp_get_attachment_image( $photo, 'ih-memory-photo', null, ['loading' => 'lazy'] ) ?>
					<div class="media-photo-overlay"></div>
				</div>
				<div class="media-photo-cursor-text">
					<?php esc_html_e( 'Open', 'inheart' ) ?>
				</div>
			</a>
			<?php
		}
		?>
	</div>

	<?php
	if( count( $photos ) - 4 > 0 ){
		?>
		<div class="media-photos-more flex justify-end">
			<button>
				<?php printf( pll_translate_string( 'Дивитись ще %d', $lang ), count( $photos ) - 4 ) ?>
			</button>
		</div>
		<?php
	}
	?>
</div>


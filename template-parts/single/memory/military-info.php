<?php

/**
 * Memory single page.
 * Military Info section.
 *
 * @package WordPress
 * @subpackage inheart
 */

if ( ! $id = $args['id'] ?? null ) {
	return;
}

$lang             = $args['lang'] ?? 'uk';
$brigadeType      = get_field( 'brigade_type', $id )?: null;
$armyTypeId       = get_field( 'army_type', $id ) ?: null;
$armyType         = $armyTypeId ? get_the_title( $armyTypeId ) : null;
$armyTypeImageUrl = $armyTypeId ? wp_get_attachment_image_url( get_post_thumbnail_id( $armyTypeId ), 'full' ) : null;
$militaryTitle    = get_field( 'military_title', $id );
$militaryPosition = get_field( 'military_position', $id );
$callSign         = get_field( 'call_sign', $id );
?>

<div class="single-memory__military-info">
    <div class="container">
		<?php
		if ( $brigadeType ) {
			echo '<div class="single-memory__military-info__brigade">', esc_html( $brigadeType ), '</div>';
		}
		?>

        <div class="single-memory__military-info__panel">
			<div class="single-memory__military-info__panel-item single-memory__military-info__panel-item--army">
				<div class="single-memory__military-info__panel-value">
					<?php
					if ( $armyTypeImageUrl ) {
						echo '<img class="single-memory__military-info__panel-img" src="', esc_url( $armyTypeImageUrl ), '" alt="">';
					}

					if ( $armyType ) {
						echo '<p class="single-memory__military-info__panel-army">', esc_html( $armyType ), '</p>';
					}
					?>
				</div>
			</div>

            <div class="single-memory__military-info__panel-item">
                <div class="single-memory__military-info__panel-item__label">
					<?php echo pll_translate_string('Позивний', $lang) ?>
				</div>

				<?php
				if ( $callSign ) {
					echo '<div class="single-memory__military-info__panel-value">', esc_html( $callSign ), '</div>';
				}
				?>
            </div>

            <div class="single-memory__military-info__panel-item">
                <div class="single-memory__military-info__panel-item__label">
					<?php echo pll_translate_string('Звання', $lang) ?>
				</div>

				<?php
				if ( $militaryTitle ) {
					echo '<div class="single-memory__military-info__panel-value">', esc_html( $militaryTitle ), '</div>';
				}
				?>
            </div>

            <div class="single-memory__military-info__panel-item">
                <div class="single-memory__military-info__panel-item__label">
					<?php echo pll_translate_string('Посада', $lang) ?>
				</div>

				<?php
				if ( $militaryPosition ) {
					echo '<div class="single-memory__military-info__panel-value">', esc_html( $militaryPosition ), '</div>';
				}
				?>
            </div>
        </div><!-- .single-memory__military-info__panel -->
    </div><!-- .container -->
</div><!-- .single-memory__military-info -->


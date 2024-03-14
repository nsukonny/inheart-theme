<?php

/**
 * Memory single page.
 * Military Info section.
 *
 * @package WordPress
 * @subpackage inheart
 */

if (!$id = $args['id'] ?? null)
{
    return;
}
$lang = $args['lang'] ?? 'uk';

$brigadeType = get_field( 'brigade_type', $id );
$armyTypeId = get_field( 'army_type', $id );
$armyType = get_the_title( $armyTypeId );
$armyTypeImageUrl = wp_get_attachment_image_url( get_post_thumbnail_id( $armyTypeId ), 'full' );
$militaryTitle = get_field( 'military_title', $id );
$militaryPosition = get_field( 'military_position', $id );
$callSign = get_field( 'call_sign', $id );

?>


<div class="single-memory__military-info">
    <div class="container">
        <div class="single-memory__military-info__brigade"><?php echo esc_html( $brigadeType ); ?></div>
        <div class="single-memory__military-info__panel">

            <div class="single-memory__military-info__panel-item single-memory__military-info__panel-item--army">
                <div class="single-memory__military-info__panel-value">
                    <img class="single-memory__military-info__panel-img" src="<?php echo $armyTypeImageUrl ?>" alt="">
                   <p class="single-memory__military-info__panel-army"><?php echo esc_html( $armyType ); ?></p>
                </div>
            </div>

            <div class="single-memory__military-info__panel-item">
                <div class="single-memory__military-info__panel-item__label"><?php echo pll_translate_string('Позивний', $lang) ?></div>
                <div class="single-memory__military-info__panel-value"><?php echo esc_html( $callSign ); ?></div>
            </div>

            <div class="single-memory__military-info__panel-item">
                <div class="single-memory__military-info__panel-item__label"><?php echo pll_translate_string('Звання', $lang) ?></div>
                <div class="single-memory__military-info__panel-value"><?php echo esc_html( $militaryTitle ); ?></div>
            </div>

            <div class="single-memory__military-info__panel-item">
                <div class="single-memory__military-info__panel-item__label"><?php echo pll_translate_string('Посада', $lang) ?></div>
                <div class="single-memory__military-info__panel-value"><?php echo esc_html( $militaryPosition ); ?></div>
            </div>

        </div>

    </div>
</div>
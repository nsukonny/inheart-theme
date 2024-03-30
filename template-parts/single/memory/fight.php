<?php

/**
 * Memory single page
 * Fight section.
 *
 * @package WordPress
 * @subpackage inheart
 */

if (!$id = $args['id'] ?? null)
{
    return;
}
$theme = get_field('theme', $id);
if ($theme != 'military')
{
    return;
}

$lang = $args['lang'] ?? 'uk';

$lastFightSection = get_field( 'last_fight', $id );

if(!isset($lastFightSection['location']) || !isset($lastFightSection['text']))
{
    return;
}

$location = $lastFightSection['location'];
$text = $lastFightSection['text'];
$died_at = ih_convert_input_date(get_field('died_at', $id), $theme == 'military' ? 'rome-lf' : null);

$mapBoxKey = get_field( 'map_box_key', 'option' );
?>

<section class="single-memory-fight">
    <div class="single-memory-fight__wrapper">
        <div class="single-memory-fight__section single-memory-fight__section--map">
            <div class="single-memory-fight__start" >
                <h2 class="single-memory-fight__start-title"><?php echo pll_translate_string( 'Останній бій', $lang ) ?></h2>
                <p class="single-memory-fight__start-description"><?php echo esc_html($text); ?></p>
                <div class="single-memory-fight__start-place">
                    <p class="single-memory-fight__start-place__title"><?php echo esc_html($location); ?></p>
                    <p class="single-memory-fight__start-place__country"><?php echo pll_translate_string( 'Україна', $lang ) ?></p>
                </div>

            </div>

			<?php
			if( $mapBoxKey ){
				?>
				<div
					class="single-memory-fight__map mapbox"
					id="single_memory_fight_map"
					data-long="37.553576"
					data-lat="55.725163"
					data-region="Київ"
					data-location="<?php echo esc_attr($location) ?>"
					data-key="<?php echo esc_attr($mapBoxKey) ?>"
				></div>
				<?php
			}
			?>
        </div>
        <div class="single-memory-fight__section single-memory-fight__section--over1">
            <canvas></canvas>
        </div>
        <div class="single-memory-fight__section single-memory-fight__section--over2">
            <canvas></canvas>
        </div>
        <div class="single-memory-fight__section single-memory-fight__section--over3">
            <div class="single-memory-fight__final">
                <div class="single-memory-fight__final-title"><?php echo $died_at?></div>
                <p class="single-memory-fight__final-caption"><?php echo pll_translate_string( 'Герої не вмирають', $lang ) ?></p>
            </div>
        </div>
    </div>
</section>

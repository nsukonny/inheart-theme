<?php

/**
 * Memory single page.
 * Top section.
 *
 * @package WordPress
 * @subpackage inheart
 */

if (!$id = $args['id'] ?? null)
{
    return;
}

$theme = get_field('theme', $id);
$first_name = get_field('first_name', $id);
$last_name = get_field('last_name', $id);
$middle_name = get_field('middle_name', $id);
$born_at = ih_convert_input_date(get_field('born_at', $id), $theme == 'military' ? 'rome' : null);
$died_at = ih_convert_input_date(get_field('died_at', $id), $theme == 'military' ? 'rome' : null);


?>

<section class="single-memory-top">
    <div class="container">
        <div class="single-memory-top-inner flex direction-column align-center">
            <?php get_template_part('template-parts/single/memory/thumb', null, ['id' => $id]) ?>

            <div class="single-memory-info">
                <div class="single-memory-name">
                    <?php echo esc_html("$first_name $middle_name") ?>
                </div>
                <div class="single-memory-lastname flex align-end justify-center">
                    <div class="single-memory-date hide-before-md">
                        <?php echo $born_at ?>
                    </div>
                    <div class="single-memory-name">
                        <?php echo esc_html($last_name) ?>
                    </div>
                    <div class="single-memory-date hide-before-md">
                        <?php echo $died_at ?>
                    </div>
                </div>
                <div class="hide-after-md flex flex-wrap align-center justify-between">
                    <div class="single-memory-date">
                        <?php echo $born_at; ?>
                    </div>
                    <span class="single-memory-date__divider"></span>
                    <div class="single-memory-date">
                        <?php echo $died_at ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php if ($theme == 'military'): ?>
        <?php get_template_part('template-parts/single/memory/top-pixel') ?>

        <?php get_template_part('template-parts/single/memory/military-info', null, ['id' => $id]) ?>

    <?php endif; ?>


</section>

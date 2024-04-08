<?php

/**
 * Memory single page
 * Rewards section.
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

$idCounter = 0;
$idCounter2 = 0;
$defaultImage = "/wp-content/uploads/2024/02/zolote-sercze-min-306x306.png";
$rewards = get_field('rewards', $id);

if(!$rewards)
{
    return;
}
$rewards = array_chunk($rewards, 4);

?>

<section class="single-memory-rewards">
    <div class="container">

        <h2 class="single-memory-heading single-memory-heading--rewards">
            <?php echo pll_translate_string( 'Відзнаки та нагороди', $lang ) ?>
        </h2>

        <div class="single-memory-rewards__items">
            <?php foreach ($rewards as $i=>$rows):?>
            <div class="single-memory-rewards__items-row <?php echo ($i>0?'hidden':'');?>">
                <?php foreach ($rows as $item):

                    $imageReward = wp_get_attachment_image_url( get_post_thumbnail_id( $item['reward_id'] ), 'ih-content-full' );
                    $imageUrl = $item['reward_custom'] ? $defaultImage : $imageReward;
                    $rewardTitle = $item['reward_custom'] ? $item['reward_custom_name'] : get_the_title($item['reward_id']);
                    $forWhatReward = $item['for_what'];
                    $rewardNumber = $item['reward_number'];


                ?>

                    <div class="single-memory-rewards__items-cell">
                        <div class="single-memory-rewards__item"  data-id="<?php echo $idCounter;?>">
                            <div class="single-memory-rewards__item-slide single-memory-rewards__item-slide--front">
                                <div class="single-memory-rewards__item-image-wrapper">
                                    <div class="single-memory-rewards__item-image" style="background-image: url(<?php echo esc_url($imageUrl)?>)"></div>
                                </div>
                                <div style="height: 60px">
                                    <div class="single-memory-rewards__item-title">
                                        <?php echo esc_html($rewardTitle); ?>
                                    </div>
                                    <div class="single-memory-rewards__item-degree">

                                    </div>
                                </div>

                            </div>

                            <div class="single-memory-rewards__item-slide single-memory-rewards__item-slide--back">
                                <div class="single-memory-rewards__item-title">
                                    <?php echo esc_html($rewardTitle); ?>
                                </div>
                                <div class="single-memory-rewards__item-degree">

                                </div>

                                <div class="single-memory-rewards__item-description">
                                    <?php
                                    echo esc_html($item['edict'])
                                        . ' № ' . esc_html($item['reward_number'])
                                        . ' ' .pll_translate_string('Від', $lang)
                                        .' ' . esc_html($item['reward_date'])
                                        . ', "' . esc_html($item['for_what'])
                                        . '", '. pll_translate_string('нагороджений орденом', $lang) .' ' . esc_html($rewardTitle)
                                        . ($item['posthumously'] ? "<br>" . pll_translate_string('Посмертно', $lang)  : "");
                                    ?>
                                </div>
                                <span class="single-memory-rewards__item-more"><?php echo pll_translate_string('Читати детальніше', $lang)?></span>
                            </div>
                        </div>
                    </div>
                <?php
                    $idCounter++;
                endforeach;?>
            </div>
            <?php endforeach;?>

            <div class="single-memory-rewards__items__more">
                <button class="btn xl secondary outlined single-memory-rewards__items__more-btn"><?php echo pll_translate_string( 'Переглянути всі нагороди', $lang ) ?></button>
            </div>
        </div>

        <?php foreach ($rewards as $rows):?>
            <?php foreach ($rows as $item):
                $imageReward = wp_get_attachment_image_url( get_post_thumbnail_id( $item['reward_id'] ), 'ih-content-full' );
                $imageUrl = $item['reward_custom'] ? $defaultImage : $imageReward;
                $rewardTitle = $item['reward_custom'] ? $item['reward_custom_name'] : get_the_title($item['reward_id']);
                $forWhatReward = $item['for_what'];
                $rewardNumber = $item['reward_number'];


                ?>
            <div class="single-memory-rewards__modal hidden" data-id="<?php echo $idCounter2;?>">
                <div class="single-memory-rewards__modal-wrapper">
                    <div class="single-memory-rewards__modal__close"></div>

                    <div class="single-memory-rewards__modal-content">
                        <div class="single-memory-rewards__item-image-wrapper">
                            <div class="single-memory-rewards__item-image" style="background-image: url(<?php echo esc_url($imageUrl)?>)"></div>
                        </div>
                        <div class="single-memory-rewards__item-title">
                            <?php echo esc_html($rewardTitle); ?>
                        </div>
                        <div class="single-memory-rewards__item-degree">
                            
                        </div>
                        <div class="single-memory-rewards__item-description">
                            <?php
                            echo esc_html($item['edict'])
                                . ' № ' . esc_html($item['reward_number'])
                                . ' ' .pll_translate_string('Від', $lang)
                                .' ' . esc_html($item['reward_date'])
                                . ', "' . esc_html($item['for_what'])
                                . '", '. pll_translate_string('нагороджений орденом', $lang) .' ' . esc_html($rewardTitle)
                                . ($item['posthumously'] ? "<br>" . pll_translate_string('Посмертно', $lang)  : "");
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                $idCounter2++;
            endforeach;?>
        <?php endforeach;?>
    </div>
</section>
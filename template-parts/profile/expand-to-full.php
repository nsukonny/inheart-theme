<?php

/**
 * Profile memory expand to full popup.
 *
 * @package WordPress
 * @subpackage inheart
 */

$page_to_expand = $args['expand'] ?? '';
$class          = $page_to_expand ? '' : ' hidden';
?>

<section class="expand-page <?php echo esc_attr($class) ?>">
    <?php
    get_template_part('components/profile/expand/title');
    get_template_part('components/profile/expand/form', null, ['expand' => $page_to_expand]);
    ?>
</section>


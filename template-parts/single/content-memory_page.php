<?php

/**
 * Memory single page.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! is_singular( 'memory_page' ) ) return;

$id		= get_the_ID();
$lang	= get_field( 'language', $id );
?>

<article class="single-memory">
    <?php
    get_template_part( 'template-parts/single/memory/top', null, ['id' => $id] );
    get_template_part( 'template-parts/single/memory/rewards', null, ['id' => $id] );
    get_template_part( 'template-parts/single/memory/epitaph', null, ['id' => $id] );
    get_template_part( 'template-parts/single/memory/biography', null, ['id' => $id, 'lang' => $lang] );
    get_template_part( 'template-parts/single/memory/fight', null, ['id' => $id, 'lang' => $lang] );
    get_template_part( 'template-parts/single/memory/memories', null, ['id' => $id, 'lang' => $lang] );
    get_template_part( 'template-parts/single/memory/media', null, ['id' => $id, 'lang' => $lang] );
    get_template_part( 'template-parts/single/memory/map', null, ['id' => $id, 'lang' => $lang] );
    get_template_part( 'template-parts/single/memory/cta', null, ['lang' => $lang] );
    ?>
</article>


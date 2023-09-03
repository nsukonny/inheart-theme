<?php

/**
 * Memory single page.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! is_singular( 'memory_page' ) ) return;

$id = get_the_ID();
?>

<article class="single-memory">
	<?php
	get_template_part( 'template-parts/single/memory/top', null, ['id' => $id] );
	get_template_part( 'template-parts/single/memory/biography', null, ['id' => $id] );
	get_template_part( 'template-parts/single/memory/memories', null, ['id' => $id] );
	get_template_part( 'template-parts/single/memory/media', null, ['id' => $id] );
	?>
</article><!-- .memory-single -->


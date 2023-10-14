<?php

/**
 * Memory single page.
 * Media section.
 *
 * @package WordPress
 * @subpackage inheart
 */

$lang = $args['lang'] ?? 'uk';
?>

<section class="single-memory-media">
	<div class="container">
		<h2 class="single-memory-heading">
			<?php echo pll_translate_string( 'Медiа-файли', $lang ) ?>
		</h2>

		<?php
		get_template_part( 'template-parts/single/memory/media', 'photos', ['id' => $args['id'], 'lang' => $lang] );
		get_template_part( 'template-parts/single/memory/media', 'videos', ['id' => $args['id'], 'lang' => $lang] );
		get_template_part( 'template-parts/single/memory/media', 'links', ['id' => $args['id'], 'lang' => $lang] );
		?>
	</div>
</section>


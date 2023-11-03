<?php

/**
 * Memory single page.
 * Media section.
 *
 * @package WordPress
 * @subpackage inheart
 */

$id		= $args['id'];
$lang	= $args['lang'] ?? 'uk';
$photos	= get_field( 'photo', $id );
$videos	= get_field( 'video', $id );
$links	= get_field( 'links', $id );

if( empty( $photos ) && empty( $videos ) && empty( $links ) ) return;
?>

<section class="single-memory-media">
	<div class="container">
		<h2 class="single-memory-heading">
			<?php echo pll_translate_string( 'Медiа-файли', $lang ) ?>
		</h2>

		<?php
		get_template_part( 'template-parts/single/memory/media', 'photos', [
			'id'	=> $id,
			'lang'	=> $lang,
			'photos'=> $photos
		] );
		get_template_part( 'template-parts/single/memory/media', 'videos', [
			'id'	=> $id,
			'lang'	=> $lang,
			'videos'=> $videos
		] );
		get_template_part( 'template-parts/single/memory/media', 'links', [
			'id'	=> $id,
			'lang'	=> $lang,
			'links'	=> $links
		] );
		?>
	</div>
</section>


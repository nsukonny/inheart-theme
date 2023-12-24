<?php

/**
 * Memory single page.
 * Memories not found layout.
 *
 * @package WordPress
 * @subpackage inheart
 */

$lang			= $args['lang'] ?? 'uk';
$add_memory_url	= $args['url'] ?? '#';
?>

<div class="single-memory-no-memories flex direction-column align-center">
	<?php
	if( $no_memories_img = get_field( 'no_memories_image', 'option' ) ){
		?>
		<div class="single-memory-no-memories-img">
			<?php echo wp_get_attachment_image( $no_memories_img['id'], 'ih-illustration', null, ['loading' => 'lazy'] ) ?>
		</div>
		<?php
	}
	?>

	<div class="single-memory-no-memories-title">
		<?php echo pll_translate_string( 'Наразі немає жодного спогаду', $lang ) ?>
	</div>
	<div class="single-memory-no-memories-desc">
		<?php echo pll_translate_string( 'Додайте перший спогад про близьку людину', $lang ) ?>
	</div>

	<?php
	get_template_part( 'components/memory-page/add-memory-button', null, [
		'url'	=> $add_memory_url,
		'lang'	=> $lang
	] );
	?>
</div>


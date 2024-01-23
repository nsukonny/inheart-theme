<?php

/**
 * Memory single page.
 * CTA section.
 *
 * @package WordPress
 * @subpackage inheart
 */

$lang = $args['lang'] ?? 'uk';
?>

<section class="single-memory-cta">
	<div class="container">
		<div class="single-memory-cta-inner flex flex-wrap align-center">
			<div class="cta-left flex direction-column">
				<h2 class="cta-title">
					<?php echo pll_translate_string( "Спадщина та пам'ять про ваших близьких не зникне.", $lang ) ?>
				</h2>
				<div class="cta-desc">
					<?php echo pll_translate_string( 'Створіть вічну сторінку спогадів про людину, яка вам була близька. Збережіть спогади для майбутніх поколінь', $lang ) ?>
				</div>
				<a href="<?php echo get_the_permalink( pll_get_post( ih_get_memory_creation_page_id() ) ) ?>" class="btn lg primary">
					<?php echo pll_translate_string( 'Створити сторінку спогадів', $lang ) ?>
				</a>
			</div>
			<img src="<?php echo THEME_URI ?>/static/img/registration-min.png" alt="" />
		</div>
	</div>
</section>


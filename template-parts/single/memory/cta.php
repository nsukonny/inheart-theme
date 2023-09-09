<?php

/**
 * Memory single page.
 * CTA section.
 *
 * @package WordPress
 * @subpackage inheart
 */
?>

<section class="single-memory-cta">
	<div class="container">
		<div class="single-memory-cta-inner flex flex-wrap align-center">
			<div class="cta-left flex direction-column">
				<h2 class="cta-title">
					<?php esc_html_e( "Спадщина та пам'ять про ваших близьких не зникне.", 'inheart' ) ?>
				</h2>
				<div class="cta-desc">
					<?php esc_html_e( 'Створіть вічну сторінку спогадів про людину, яка вам була близька. Збережіть спогади для майбутніх поколінь', 'inheart' ) ?>
				</div>
				<a href="<?php echo get_the_permalink( pll_get_post( 167 ) ) ?>" class="btn lg primary">
					<?php esc_html_e( 'Створити сторінку спогадів', 'inheart' ) ?>
				</a>
			</div>
			<img src="<?php echo THEME_URI ?>/static/img/registration-min.png" alt="" />
		</div>
	</div>
</section>


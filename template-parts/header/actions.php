<?php

/**
 * Header actions block, when User is not authorized.
 *
 * @package WordPress
 * @subpackage inheart
 */
?>

<div class="header-actions flex align-center justify-end hide-before-md">
	<a href="<?php echo get_the_permalink( pll_get_post( 10 ) ) ?>" class="header-sign-in flex align-center" aria-label="<?php esc_attr_e( 'Увійти', 'inheart' ) ?>">
		<svg class="header-profile-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M0.5 13.4922C0.5 13.0822 0.667765 12.6902 0.964286 12.4071L1.44048 11.9526C5.11151 8.44842 10.8885 8.44842 14.5595 11.9526L15.0357 12.4071C15.3322 12.6902 15.5 13.0822 15.5 13.4922V14C15.5 14.8284 14.8284 15.5 14 15.5H2C1.17157 15.5 0.5 14.8284 0.5 14V13.4922Z" stroke="#F7B941"/>
			<circle cx="8" cy="4" r="3.5" stroke="#F7B941"/>
		</svg>
		<?php echo esc_html_e( 'Увійти', 'inheart' ) ?>
	</a>
	<a href="<?php echo get_the_permalink( pll_get_post( 167 ) ) ?>" class="btn lg primary" aria-label="<?php esc_attr_e( "Створити сторінку пам'яті", 'inheart' ) ?>">
		<?php echo esc_html_e( "Створити сторінку пам'яті", 'inheart' ) ?>
	</a>
</div><!-- .header-actions -->


<?php

/**
 * Order is created.
 * Title part.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $title = get_field( 'order_created_title' ) ?: null ) return;
?>

<h2 class="profile-memories-title flex flex-wrap justify-between align-center">
	<span class="profile-memories-title-buttons flex flex-wrap align-center justify-between">
		<button class="menu-button profile-menu-button flex align-center hide-after-xl">
			<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
				<line y1="1" x2="14" y2="1" stroke="currentColor" />
				<line y1="13" x2="14" y2="13" stroke="currentColor" />
				<line y1="7" x2="14" y2="7" stroke="currentColor" />
			</svg>
			<span><?php _e( 'Меню', 'inheart' ) ?></span>
		</button>
	</span>

	<span class="profile-memories-title-text"><?php echo esc_html( $title ) ?></span>

	<span class="profile-breadcrumbs flex flex-wrap">
		<a href="<?php echo get_the_permalink( pll_get_post( ih_get_profile_page_id() ) ) ?>">
			<?php _e( "Сторiнки пам'ятi, якi ви створили", 'inheart' ) ?>
		</a>
		<span><?php _e( 'Замовлення створено', 'inheart' ) ?></span>
	</span>
</h2>


<?php

/**
 * Profile settings.
 * Title part.
 *
 * @package WordPress
 * @subpackage inheart
 */
?>

<h1 class="profile-memories-title flex flex-wrap justify-between align-center">
	<span class="profile-memories-title-text"><?php _e( 'Налаштування', 'inheart' ) ?></span>

	<span class="profile-memories-title-buttons flex flex-wrap align-center justify-between">
		<button class="menu-button profile-menu-button flex align-center hide-after-xl">
			<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
				<line y1="1" x2="14" y2="1" stroke="currentColor" />
				<line y1="13" x2="14" y2="13" stroke="currentColor" />
				<line y1="7" x2="14" y2="7" stroke="currentColor" />
			</svg>
			<span><?php _e( 'Меню', 'inheart' ) ?></span>
		</button>

		<button class="btn lg primary save-changes" disabled>
			<?php _e( 'Зберегти', 'inheart' ) ?>
		</button>
	</span>
</h1>


<?php

/**
 * Profile memory pages.
 * Title part.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $title = get_field( 'memory_pages_title' ) ?: null ) return;

$hide_button = $args['hide_button'] ?? '';
$draft       = $args['draft'] ?? null;
?>

<h1 class="profile-memories-title flex flex-wrap justify-between align-center">
	<span class="profile-memories-title-text"><?php echo esc_html( $title ) ?></span>

	<span class="profile-memories-title-buttons flex flex-wrap align-center justify-between">
		<button class="menu-button profile-menu-button flex align-center hide-after-xl">
			<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
				<line y1="1" x2="14" y2="1" stroke="currentColor" />
				<line y1="13" x2="14" y2="13" stroke="currentColor" />
				<line y1="7" x2="14" y2="7" stroke="currentColor" />
			</svg>
			<span><?php _e( 'Меню', 'inheart' ) ?></span>
		</button>

		<?php
		if( ! $hide_button ){
			?>
			<a class="button primary lg" href="<?php echo get_the_permalink( pll_get_post( ih_get_memory_creation_page_id() ) ) ?>">
				<?php
				$draft
					? _e( 'Продовжити створення сторінки', 'inheart' )
					: _e( "Створити сторінку пам'яті", 'inheart' );
				?>
			</a>
			<?php
		}
		?>
	</span>
</h1>


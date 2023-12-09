<?php

/**
 * Profile memory pages.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $memory_pages = $args['pages'] ?? null ) return;

$title = get_field( 'memory_pages_title' );
?>

<section class="profile-memories profile-body">
	<?php
	if( $title ){
		?>
		<h1 class="profile-memories-title flex flex-wrap justify-between align-center">
			<span class="profile-memories-title-text"><?php echo esc_html( $title ) ?></span>

			<span class="profile-memories-title-buttons flex flex-wrap align-center justify-between">
				<button class="menu-button flex align-center">
					<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
						<line y1="1" x2="14" y2="1" stroke="currentColor" />
						<line y1="13" x2="14" y2="13" stroke="currentColor" />
						<line y1="7" x2="14" y2="7" stroke="currentColor" />
					</svg>
					<span><?php _e( 'Меню', 'inheart' ) ?></span>
				</button>
				<a class="btn lg primary" href="<?php echo get_the_permalink( pll_get_post( 167 ) ) ?>">
					<?php _e( "Створити сторінку пам'яті", 'inheart' ) ?>
				</a>
			</span>
		</h1>
		<?php
	}
	?>

	<div class="profile-memories-list flex flex-wrap">
		<?php
		foreach( $memory_pages as $page )
			get_template_part( 'components/cards/memory-page/card', null, ['id' => $page->ID] );
		?>
	</div>
</section>


<?php

/**
 * Template name: Add New Memories
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! is_user_logged_in() ) wp_die( esc_html__( 'Тільки авторизовані користувачі', 'inheart' ) );

// No destination memory page ID - redirect to Profile Memories.
if( ! $memory_page = $_GET['mp'] ?? null ){
	wp_redirect( get_the_permalink( pll_get_post( 1361 ) ) );
	exit;
}

get_header();

wp_enqueue_style( 'add-new-memories', THEME_URI . '/static/css/pages/add-new-memories.min.css', [], THEME_VERSION );
wp_enqueue_script( 'add-new-memories', THEME_URI . '/static/js/add-new-memories/add-new-memories.min.js', [], THEME_VERSION, true );
?>

<main class="main add-new-memories flex direction-column">
	<div class="container">
		<div class="add-new-memories-inner flex flex-wrap align-start">
			<?php get_template_part( 'template-parts/add-new-memories/form', null, ['memory_page' => $memory_page] ) ?>
			<div class="add-new-memory-info">
				<?php
				get_template_part( 'template-parts/profile/memory-card', null, [
					'id'			=> $memory_page,
					'front'			=> 1,
					'date_format'	=> 'lang'
				] );
				?>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();


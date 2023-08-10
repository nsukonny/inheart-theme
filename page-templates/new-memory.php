<?php

/**
 * Template name: New Memory
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! is_user_logged_in() ) wp_die( esc_html__( 'Тільки авторизовані користувачі', 'inheart' ) );

get_header();

wp_enqueue_style( 'new-memory', THEME_URI . '/static/css/pages/new-memory.min.css', [], THEME_VERSION );
wp_enqueue_script( 'new-memory', THEME_URI . '/static/js/new-memory/new-memory.min.js', [], THEME_VERSION, true );

ih_create_new_memory_page();
?>

<main class="main new-memory flex direction-column">
	<?php
	get_template_part( 'template-parts/new-memory/step-0/step', '0' );
	get_template_part( 'template-parts/new-memory/step-1/step', '1' );
	get_template_part( 'template-parts/new-memory/step-2/step', '2' );
	get_template_part( 'template-parts/new-memory/step-3/step', '3' );
	get_template_part( 'template-parts/new-memory/step-4/step', '4' );
	get_template_part( 'template-parts/new-memory/step-5/step', '5' );
	?>
</main>

<?php
get_template_part( 'template-parts/new-memory/footer' );


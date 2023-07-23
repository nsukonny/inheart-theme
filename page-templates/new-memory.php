<?php

/**
 * Template name: New Memory
 *
 * @package WordPress
 * @subpackage inheart
 */

get_header();

wp_enqueue_style( 'new-memory', THEME_URI . '/static/css/pages/new-memory.min.css', [], THEME_VERSION );
wp_enqueue_script( 'new-memory', THEME_URI . '/static/js/new-memory/new-memory.min.js', [], THEME_VERSION, true );

$step = $_GET['step'] ?? 0;
?>

<main class="main new-memory flex direction-column">
	<?php
	get_template_part( 'template-parts/new-memory/step', '0', ['is_active' => $step == 0] );
	get_template_part( 'template-parts/new-memory/step', '1', ['is_active' => $step == 1] );
	get_template_part( 'template-parts/new-memory/step', '2', ['is_active' => $step == 2] );
	get_template_part( 'template-parts/new-memory/step', '3', ['is_active' => $step == 3] );
	get_template_part( 'template-parts/new-memory/step', '4', ['is_active' => $step == 4] );
	?>
</main>

<?php
get_template_part( 'template-parts/new-memory/footer', null, ['step' => $step] );


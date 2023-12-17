<?php

/**
 * Template name: Profile
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! is_user_logged_in() ){
	wp_redirect( get_the_permalink( pll_get_post( 10 ) ) );
	exit;
}

get_template_part( 'components/header/profile' );

wp_enqueue_style( 'profile', THEME_URI . '/static/css/pages/profile.min.css', [], THEME_VERSION );
wp_enqueue_script( 'profile', THEME_URI . '/static/js/profile/profile.min.js', [], THEME_VERSION, true );

$author_id		= get_current_user_id();
$memory_pages	= get_posts( [
	'post_type'	=> 'memory_page',
	'author'	=> $author_id
] );
?>

<main class="main profile flex flex-wrap">
	<?php
	get_template_part( 'components/sidebar/sidebar' );

	if( ! empty( $memory_pages ) ){
		get_template_part( 'template-parts/profile/memory-pages', 'exist', [ 'pages' => $memory_pages ] );
		get_template_part( 'template-parts/profile/expand-to-full-popup' );
	}else{
		get_template_part( 'template-parts/profile/memory-pages', 'none' );
	}
	?>
</main>

<?php
get_footer();


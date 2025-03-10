<?php

/**
 * Template name: Profile
 *
 * @package WordPress
 * @subpackage inheart
 */

if ( ! is_user_logged_in()) {
    wp_redirect(get_the_permalink(pll_get_post(ih_get_login_page_id())));
    exit;
}

// Redirect to the same Memory page where User wanted to leave his memory.
if (isset($_SESSION['redirect_to_mp']) && $_SESSION['redirect_to_mp']) {
    wp_redirect($_SESSION['redirect_to_mp']);
    exit;
}

get_template_part('components/header/profile');

wp_enqueue_style('profile', THEME_URI.'/static/css/pages/profile.min.css', [], THEME_VERSION);
wp_enqueue_script('profile', THEME_URI.'/static/js/profile/profile.min.js', [], THEME_VERSION, true);

$page_to_expand    = $_GET['expand'] ?? '';
$author_id         = get_current_user_id();
$memory_pages      = get_posts( [
	'post_type'   => 'memory_page',
	'post_status' => 'publish',
	'author'      => $author_id,
	'numberposts' => - 1
] );
$draft_memory_page = get_posts( [
	'post_type'   => 'memory_page',
	'post_status' => 'draft',
	'author'      => $author_id,
	'numberposts' => 1
] );
?>

	<main class="main profile flex flex-wrap">
        <?php
        get_template_part( 'components/sidebar/sidebar' );

        if ( ! empty( $memory_pages ) ) {
	        get_template_part( 'template-parts/profile/memory-pages', 'exist', [
		        'pages' => $memory_pages,
				'draft' => ! empty( $draft_memory_page ) ? $draft_memory_page[0] : null,
		        'hide'  => ! ! $page_to_expand,
	        ] );
        } else {
	        get_template_part( 'template-parts/profile/memory-pages', 'none', [
		        'hide' => ! ! $page_to_expand,
	        ] );
        }

        get_template_part('template-parts/profile/expand-to-full', null, ['expand' => $page_to_expand]);
        ?>
	</main>

<?php
get_footer();


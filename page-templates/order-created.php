<?php

/**
 * Template name: Order Created
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! is_user_logged_in() ){
	wp_redirect( get_the_permalink( pll_get_post( ih_get_login_page_id() ) ) );
	exit;
}

get_template_part( 'components/header/profile' );

wp_enqueue_style( 'profile', THEME_URI . '/static/css/pages/profile.min.css', [], THEME_VERSION );
wp_enqueue_style( 'order-created', THEME_URI . '/static/css/pages/order-created.min.css', [], THEME_VERSION );
?>

<main class="main profile flex flex-wrap">
	<?php
	get_template_part( 'components/sidebar/sidebar' );
	get_template_part( 'template-parts/profile/order-created' );
	?>
</main>

<?php
get_footer();


<?php

/**
 * Template name: Profile Memories
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
wp_enqueue_style( 'profile-memories', THEME_URI . '/static/css/pages/profile-memories.min.css', [], THEME_VERSION );
wp_enqueue_script( 'profile-memories', THEME_URI . '/static/js/profile-memories/profile-memories.min.js', [], THEME_VERSION, true );
?>

<main class="main profile-memories flex flex-wrap">
	<?php get_template_part( 'components/sidebar/sidebar' ) ?>

	<div class="profile-body">
		<?php
		get_template_part( 'components/profile/memory-pages/title', null, ['hide_button' => 1] );
		get_template_part( 'components/switcher/switcher' );
		get_template_part( 'template-parts/profile-memories/memories' );
		?>
	</div>
</main>

<?php
get_footer();


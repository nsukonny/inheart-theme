<?php

/**
 * Template name: Profile Memories
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! is_user_logged_in() ) wp_redirect( get_the_permalink( pll_get_post( 10 ) ) );

get_header();

wp_enqueue_style( 'profile', THEME_URI . '/static/css/pages/profile.min.css', [], THEME_VERSION );
wp_enqueue_style( 'profile-memories', THEME_URI . '/static/css/pages/profile-memories.min.css', [], THEME_VERSION );
wp_enqueue_script( 'profile-memories', THEME_URI . '/static/js/profile-memories/profile-memories.min.js', [], THEME_VERSION, true );
?>

<main class="main profile-memories flex flex-wrap">
	<?php get_template_part( 'template-parts/profile/sidebar' ); ?>

	<div class="profile-body">
		<h1 class="profile-memories-title flex flex-wrap justify-between align-center">
			<?php esc_html_e( 'Спогади', 'inheart' ) ?>
		</h1>

		<?php
		get_template_part( 'template-parts/profile-memories/switcher' );
		get_template_part( 'template-parts/profile-memories/memories' );
		?>
	</div>
</main>

<?php
get_footer();

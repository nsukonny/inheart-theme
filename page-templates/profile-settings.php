<?php

/**
 * Template name: Profile Settings
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! is_user_logged_in() ) wp_redirect( get_the_permalink( 10 ) );

get_header();

wp_enqueue_style( 'profile-settings', THEME_URI . '/static/css/pages/profile-settings.min.css', [], THEME_VERSION );
wp_enqueue_script( 'profile-settings', THEME_URI . '/static/js/profile-settings/profile-settings.min.js', [], THEME_VERSION, true );
?>

<main class="main profile-settings">
	<div class="container">
		<?php
		get_template_part( 'template-parts/profile-settings/header' );
		get_template_part( 'template-parts/profile-settings/form' );
		?>
	</div>
</main>

<?php
get_footer();


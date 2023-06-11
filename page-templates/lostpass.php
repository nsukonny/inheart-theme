<?php

/**
 * Template name: Lost Password
 *
 * @package    WordPress
 * @subpackage inheart
 */

get_header();

wp_enqueue_style( 'ih-auth', THEME_URI . '/static/css/pages/auth.min.css', [], THEME_VERSION );
wp_enqueue_style( 'ih-activation', THEME_URI . '/static/css/pages/activation.min.css', [], THEME_VERSION );
wp_enqueue_script( 'ih-login', THEME_URI . '/static/js/pages/lostpass.min.js', [], THEME_VERSION, true );
?>

<main class="main lostpass">
	<section class="lostpass-hero">
		<div class="container">
			<?php
			if( ! is_user_logged_in() ) get_template_part( 'template-parts/auth/lostpass' );
			else get_template_part( 'template-parts/auth/already-logged-in' );
			?>
		</div>
	</section>
</main>

<?php
get_footer();


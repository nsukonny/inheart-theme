<?php

/**
 * Template name: Login
 *
 * @package    WordPress
 * @subpackage inheart
 */

get_header();

wp_enqueue_style( 'ih-auth', THEME_URI . '/static/css/pages/auth.min.css', [], THEME_VERSION );
wp_enqueue_script( 'ih-login', THEME_URI . '/static/js/pages/login.min.js', [], THEME_VERSION, true );
?>

<main class="main login">
	<section class="login-hero">
		<div class="container">
			<div class="login-hero-inner">
				<?php
				if( ! is_user_logged_in() ) get_template_part( 'template-parts/auth/login-form' );
				else get_template_part( 'template-parts/auth/already-logged-in' );
				?>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();


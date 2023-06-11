<?php

/**
 * Template name: Register
 *
 * @package    WordPress
 * @subpackage inheart
 */

get_header();

wp_enqueue_style( 'ih-auth', THEME_URI . '/static/css/pages/auth.min.css', [], THEME_VERSION );
wp_enqueue_script( 'ih-register', THEME_URI . '/static/js/pages/register.min.js', [], THEME_VERSION, true );
?>

<main class="main register">
	<section class="register-hero">
		<div class="container">
			<div class="register-hero-inner">
				<?php
				if( ! is_user_logged_in() ) get_template_part( 'template-parts/auth/register-form' );
				else get_template_part( 'template-parts/auth/already-logged-in' );
				?>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();


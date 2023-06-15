<?php

/**
 * Template name: Register
 *
 * @package    WordPress
 * @subpackage inheart
 */

get_header();

wp_enqueue_style( 'ih-auth', THEME_URI . '/static/css/pages/auth.min.css', [], THEME_VERSION );
wp_enqueue_script( 'ih-register', THEME_URI . '/static/js/register/register.min.js', [], THEME_VERSION, true );
?>

<main class="main auth register">
	<section class="auth-hero">
		<div class="container">
			<div class="auth-hero-inner flex flex-wrap align-center">
				<?php
				if( ! is_user_logged_in() ){
					get_template_part( 'template-parts/auth/register-form' );
					get_template_part( 'template-parts/auth/illustration' );
				}	else{
					get_template_part( 'template-parts/auth/already-logged-in' );
				}
				?>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();


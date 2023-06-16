<?php

/**
 * Template name: Lost Password
 *
 * @package    WordPress
 * @subpackage inheart
 */

get_header();

wp_enqueue_style( 'ih-auth', THEME_URI . '/static/css/pages/auth.min.css', [], THEME_VERSION );
wp_enqueue_script( 'ih-lostpass', THEME_URI . '/static/js/lostpass/lostpass.min.js', [], THEME_VERSION, true );
?>

<main class="main auth lostpass">
	<section class="auth-hero">
		<div class="container">
			<div class="auth-inner flex flex-wrap align-center">
				<?php
				if( ! is_user_logged_in() ) get_template_part( 'template-parts/auth/lostpass' );
				else get_template_part( 'template-parts/auth/already-logged-in' );
				?>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();


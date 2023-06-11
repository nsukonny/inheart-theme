<?php

/**
 * Template name: Account Activation
 *
 * @package    WordPress
 * @subpackage inheart
 */

get_header();

wp_enqueue_style( 'ih-activation', THEME_URI . '/static/css/pages/activation.min.css', [], THEME_VERSION );
wp_enqueue_script( 'ih-activation', THEME_URI . '/static/js/pages/activation.min.js', [], THEME_VERSION, true );
?>

<main class="main activation">
	<section class="activation-hero">
		<div class="container">
			<div class="activation-inner wrap-gray">
				<?php
				if( ! is_user_logged_in() ) get_template_part( 'template-parts/auth/activation' );
				else get_template_part( 'template-parts/auth/already-logged-in' );
				?>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();


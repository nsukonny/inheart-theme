<?php

/**
 * Template name: Profile Settings
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! is_user_logged_in() ) wp_redirect( get_the_permalink( pll_get_post( 10 ) ) );

get_template_part( 'components/header/profile' );

wp_enqueue_style( 'profile', THEME_URI . '/static/css/pages/profile.min.css', [], THEME_VERSION );
wp_enqueue_style( 'profile-settings', THEME_URI . '/static/css/pages/profile-settings.min.css', [], THEME_VERSION );
wp_enqueue_script( 'profile-settings', THEME_URI . '/static/js/profile-settings/profile-settings.min.js', [], THEME_VERSION, true );
?>

<main class="main profile-settings flex flex-wrap">
	<?php get_template_part( 'components/sidebar/sidebar' ) ?>

	<div class="profile-body">
		<h1 class="profile-memories-title flex flex-wrap justify-between align-center">
			<?php esc_html_e( 'Налаштування', 'inheart' ) ?>
			<button class="btn lg primary save-changes" disabled>
				<?php esc_html_e( 'Зберегти', 'inheart' ) ?>
			</button>
		</h1>

		<?php
		get_template_part( 'template-parts/profile-settings/form' );
		get_template_part( 'template-parts/profile-settings/plan' );
		?>
	</div>
</main>

<?php
get_footer();


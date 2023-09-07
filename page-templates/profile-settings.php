<?php

/**
 * Template name: Profile Settings
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! is_user_logged_in() ) wp_redirect( get_the_permalink( pll_get_post( 10 ) ) );

get_header();

wp_enqueue_style( 'profile-settings', THEME_URI . '/static/css/pages/profile-settings.min.css', [], THEME_VERSION );
wp_enqueue_script( 'profile-settings', THEME_URI . '/static/js/profile-settings/profile-settings.min.js', [], THEME_VERSION, true );
?>

<main class="main profile-settings">
	<div class="container">
		<div class="profile-settings-inner flex flex-wrap">
			<div class="profile-settings-left">
				<?php
				get_template_part( 'template-parts/profile-settings/header' );
				get_template_part( 'template-parts/profile-settings/form' );
				get_template_part( 'template-parts/profile-settings/plan' );
				?>
			</div>

			<div class="profile-settings-right flex flex-wrap align-start justify-end">
				<a href="<?php echo get_the_permalink( pll_get_post( 951 ) ) ?>" class="btn lg outlined">
					<?php esc_html_e( 'Скасувати', 'inheart' ) ?>
				</a>
				<a href="<?php echo get_the_permalink( pll_get_post( 951 ) ) ?>" class="btn lg primary save-changes">
					<?php esc_html_e( 'Зберегти', 'inheart' ) ?>
				</a>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();


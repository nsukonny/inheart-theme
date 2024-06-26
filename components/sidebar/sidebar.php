<?php

/**
 * Sidebar.
 *
 * @package WordPress
 * @subpackage inheart
 */

$logo = get_field( 'header_logo_light', 'option' );
?>

<aside class="sidebar flex">
	<div id="sidebar-inner" class="sidebar-inner flex direction-column">
		<header class="sidebar-header flex flex-wrap align-center justify-between">
			<?php
			if( $logo ){
				?>
				<a class="header-logo flex align-center" href="<?php echo ih_get_logo_url() ?>">
					<?php echo wp_get_attachment_image( $logo['id'], 'ih-logo' ) ?>
				</a>
				<?php
			}
			?>

			<button class="button button-return flex align-center sidebar-header-return hide-after-xl" type="button">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<path d="M11.828 11.9992L14.657 14.8272L13.243 16.2422L9 11.9992L13.243 7.75619L14.657 9.17119L11.828 11.9992Z" fill="currentColor"/>
				</svg>
				<span><?php _e( 'Назад', 'inheart' ) ?></span>
			</button>
		</header>

		<?php get_template_part( 'components/menu/main', null, ['type' => 'profile_sidebar_menu', 'class' => 'sidebar-nav'] ) ?>

		<footer class="sidebar-footer">
			<?php
			get_template_part( 'components/menu/main' );
			echo ih_get_socials( 'sidebar-icons' );
			?>

			<button class="button button-menu button-icon-lead logout">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
					<path d="M3.33317 15.1663C3.15636 15.1663 2.98679 15.0961 2.86177 14.9711C2.73674 14.8461 2.6665 14.6765 2.6665 14.4997V2.49967C2.6665 2.32286 2.73674 2.15329 2.86177 2.02827C2.98679 1.90325 3.15636 1.83301 3.33317 1.83301H12.6665C12.8433 1.83301 13.0129 1.90325 13.1379 2.02827C13.2629 2.15329 13.3332 2.32286 13.3332 2.49967V14.4997C13.3332 14.6765 13.2629 14.8461 13.1379 14.9711C13.0129 15.0961 12.8433 15.1663 12.6665 15.1663H3.33317ZM9.99984 11.1663L13.3332 8.49967L9.99984 5.83301V7.83301H5.99984V9.16634H9.99984V11.1663Z" fill="#F74141"/>
				</svg>
				<?php _e( 'Вийти з профілю', 'inheart' ) ?>
			</button>

			<div class="sidebar-footer-info">
				<div class="footer-copyright">&copy; Inheart 2022. All rights reserved.</div>

				<?php
				wp_nav_menu( [
					'theme_location'	=> 'footer_bottom_menu',
					'container'			=> 'nav',
					'container_class'	=> 'footer-bottom-nav'
				] );
				?>

				<a href="https://www.anthracite.studio/" class="sidebar-footer-by" target="_blank">
					<img src="<?php echo THEME_URI ?>/static/img/designed-by.svg" alt="Designed by Anthracite" />
				</a>
			</div>
		</footer>
	</div><!-- .sidebar-inner -->
</aside>


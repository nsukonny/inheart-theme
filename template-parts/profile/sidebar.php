<?php

/**
 * Profile - sidebar.
 *
 * @package WordPress
 * @subpackage inheart
 */

$logo			= get_field( 'header_logo_light', 'option' );
$logo_mobile	= get_field( 'header_logo_light_mobile', 'option' );
$socials		= get_field( 'social_icons', 'option' );
?>

<aside class="profile-sidebar flex direction-column">
	<header class="profile-sidebar-header flex">
		<?php
		if( $logo ){
			?>
			<a class="header-logo align-center" href="<?php echo get_the_permalink( pll_get_post( 2 ) ) ?>">
				<?php echo wp_get_attachment_image( $logo['id'], 'ih-logo' ) ?>
			</a>
			<?php
		}

		if( $logo_mobile ){
			?>
			<a class="header-logo mobile align-center" href="<?php echo get_the_permalink( pll_get_post( 2 ) ) ?>">
				<?php echo wp_get_attachment_image( $logo_mobile['id'], 'ih-logo-mobile' ) ?>
			</a>
			<?php
		}
		?>

		<div class="header-menu flex justify-center align-center">
			<div id="header-nav-wrap" class="header-nav-wrap">
				<ul class="header-langs flex align-center justify-center hide-after-md">
					<?php pll_the_languages() ?>
				</ul>

				<?php
				wp_nav_menu( [
					'theme_location'	=> 'header_menu',
					'container'			=> 'nav',
					'container_class'	=> 'header-nav',
					'container_id'		=> 'header-nav'
				] );

				if( ! empty( $socials ) ){
					echo '<div class="header-nav-icons flex align-center justify-center">';

					foreach(  $socials as $soc ){
						$icon	= $soc['icon'];
						$url	= $soc['url'];

						if( ! $icon || ! $url ) continue;
						?>
						<a href="<?php echo esc_url( $url ) ?>" target="_blank">
							<?php echo wp_get_attachment_image( $icon['id'], 'icon', false, ['loading' => 'lazy', 'class' => 'style-svg'] ) ?>
						</a>
						<?php
					}

					echo '</div>';
				}
				?>
			</div>

			<button
				class="header-menu-button flex align-center"
				type="button"
				aria-label="<?php esc_attr_e( 'Відкрити меню', 'inheart' ) ?>"
			>
				<span class="header-menu-button-lines">
					<span></span>
					<span></span>
					<span></span>
				</span>
				<span class="header-menu-button-label">
					<?php esc_html_e( 'Меню', 'inheart' ) ?>
				</span>
			</button>
		</div>
	</header>

	<?php
	wp_nav_menu( [
		'theme_location'	=> 'profile_sidebar_menu',
		'container'			=> 'nav',
		'container_class'	=> 'profile-sidebar-nav'
	] );
	?>

	<footer class="profile-sidebar-footer">
		<button class="btn profile-nav logout">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
				<path d="M3.33317 15.1663C3.15636 15.1663 2.98679 15.0961 2.86177 14.9711C2.73674 14.8461 2.6665 14.6765 2.6665 14.4997V2.49967C2.6665 2.32286 2.73674 2.15329 2.86177 2.02827C2.98679 1.90325 3.15636 1.83301 3.33317 1.83301H12.6665C12.8433 1.83301 13.0129 1.90325 13.1379 2.02827C13.2629 2.15329 13.3332 2.32286 13.3332 2.49967V14.4997C13.3332 14.6765 13.2629 14.8461 13.1379 14.9711C13.0129 15.0961 12.8433 15.1663 12.6665 15.1663H3.33317ZM9.99984 11.1663L13.3332 8.49967L9.99984 5.83301V7.83301H5.99984V9.16634H9.99984V11.1663Z" fill="#F74141"/>
			</svg>
			<?php esc_html_e( 'Вийти з профілю', 'inheart' ) ?>
		</button>
	</footer>
</aside>


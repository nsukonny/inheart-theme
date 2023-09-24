<?php

/**
 * Header template.
 *
 * @see Options -> Header.
 *
 * @package WordPress
 * @subpackage inheart
 */

$logo_dark	= get_field( 'header_logo_dark', 'option' );
$logo_light	= get_field( 'header_logo_light', 'option' );
$socials	= get_field( 'social_icons', 'option' );
?>

<header class="header full">
	<div class="container fluid">
		<div class="header-inner flex flex-wrap align-center">
			<div class="header-menu flex align-center">
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

				<ul class="header-langs flex hide-before-md">
					<?php pll_the_languages() ?>
				</ul>
			</div>

			<div class="header-logo flex align-center">
				<a href="<?php echo home_url( '/' ) ?>">
					<?php
					echo wp_get_attachment_image( $logo_dark['id'], 'ih-logo', false, ['class' => 'dark'] );
					echo wp_get_attachment_image( $logo_light['id'], 'ih-logo', false, ['class' => 'light'] );
					?>
				</a>
			</div>

			<?php
			if( is_user_logged_in() ) get_template_part( 'template-parts/header/profile' );
			else get_template_part( 'template-parts/header/actions' );
			?>
		</div><!-- .header-inner -->
	</div><!-- .container -->
</header><!-- .header -->


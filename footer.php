<?php

/**
 * Footer default template.
 *
 * @see Options -> Footer.
 *
 * @package WordPress
 * @subpackage inheart
 */

$logo			= get_field( 'header_logo_light', 'option' );
$copyright		= '&copy; Inheart ' . date( 'Y' ) . '. All rights reserved.';
?>

			<?php
			// Hide Footer page option is enabled.
			if( ! get_field( 'hide_footer', get_the_ID() ) ){
				?>
				<footer class="footer">
					<div class="container">
						<div class="footer-top flex flex-wrap align-center">
							<?php
							if( $logo ){
								echo '<a class="footer-logo" href="' . home_url( '/' ) . '" title="' . esc_attr__( 'На Головну', 'inheart' ) . '">'
									. wp_get_attachment_image( $logo['id'], 'ih-logo' ) .
								'</a>';
							}

							wp_nav_menu( [
								'theme_location'	=> 'footer_menu',
								'container'			=> 'nav',
								'container_class'	=> 'footer-nav'
							] );

							if( have_rows( 'social_icons', 'option' ) ){
								echo '<div class="footer-links flex flex-wrap">';

								while( have_rows( 'social_icons', 'option' ) ){
									the_row();
									$icon	= get_sub_field( 'icon' );
									$url	= get_sub_field( 'url' );

									if( ! $icon || ! $url ) continue;
									?>
									<div class="footer-link">
										<a
											href="<?php echo esc_url( $url ) ?>"
											target="_blank"
											title="<?php esc_attr_e( 'Відкрити у новій вкладці', 'inheart' ) ?>"
										>
											<?php echo wp_get_attachment_image( $icon['id'] ) ?>
										</a>
									</div>
									<?php
								}

								echo '</div>';
							}
							?>
						</div>

						<div class="footer-bottom flex flex-wrap align-center">
							<div class="footer-copyright">
								<?php echo $copyright ?>
							</div>

							<?php
							wp_nav_menu( [
								'theme_location'	=> 'footer_bottom_menu',
								'container'			=> 'nav',
								'container_class'	=> 'footer-bottom-nav'
							] );
							?>
						</div>
					</div><!-- .container -->
				</footer>
				<?php
			}

			wp_footer();
			?>
		</div><!-- .wrapper -->
	</body>
</html>

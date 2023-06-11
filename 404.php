<?php

/**
 * 404 page default template.
 *
 * @see Options -> 404 Page.
 *
 * @package WordPress
 * @subpackage inheart
 */

get_header();

$title_404	= get_field( 'title_404', 'option' );
$desc_404	= get_field( 'desc_404', 'option' );
$img_404	= get_field( 'img_404', 'option' );
$img_404	= $img_404
			? ' style="background-image: url(' . esc_url( wp_get_attachment_image_url( $img_404['id'], 'medium' ) ) . ')'
			: '';
?>

	<main class="main">
		<div class="hero hero-404">
			<div class="container">
				<div class="hero-inner"<?php echo $img_404 ?>>
					<div class="hero-body">
						<?php
						if( $title_404 ){
							?>
							<h1 class="hero-title">
								<?php printf( esc_html__( '%s', THEME_NAME ), $title_404 ) ?>
							</h1>
							<?php
						}
						?>
					</div>
				</div>
			</div>
		</div><!-- .hero.hero-404 -->

		<div class="page-content page-content-404">
			<div class="container">
				<?php
				if( $desc_404 ){
					printf( __( '%s', THEME_NAME ), $desc_404 );
				}
				?>
			</div>
		</div>
	</main>

<?php
get_footer();

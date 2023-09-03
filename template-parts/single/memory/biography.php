<?php

/**
 * Memory single page.
 * Biography section.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $id = $args['id'] ?? null ) return;

$sections = get_field( 'biography_sections', $id );
?>

<section class="single-memory-bio">
	<div class="container">
		<h2 class="single-memory-heading">
			<?php esc_html_e( 'Біографія', 'inheart' ) ?>
		</h2>

		<?php
		if( ! empty( $sections ) ){
			usort( $sections, function( $a, $b ){
				if( $a['position'] === $b['position'] ) return 0;

				return ( $a['position'] < $b['position'] ) ? -1 : 1;
			} );
			?>
			<div class="single-memory-bio-sections">
				<?php
				foreach( $sections as $section ){
					$title	= $section['category'];
					$text	= $section['text'];

					// Show only full sections.
					if( ! $title || ! $text ) continue;
					?>
					<div class="single-memory-bio-section">
						<div class="single-memory-bio-title">
							<?php echo esc_html( $title ) ?>
						</div>
						<div class="single-memory-bio-text">
							<?php echo esc_html( $text ) ?>
						</div>
					</div>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>
	</div>
</section>


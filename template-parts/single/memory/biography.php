<?php

/**
 * Memory single page.
 * Biography section.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $id = $args['id'] ?? null ) return;

$lang		= $args['lang'] ?? 'uk';
$sections	= get_field( 'biography_sections', $id );
?>


<section class="single-memory-bio">
	<div class="container">
		<h2 class="single-memory-heading">
			<?php echo pll_translate_string( 'Біографія', $lang ) ?>
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
                    $media = $section['photos'];

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
                        <?php
                            if ( !empty( $media ) ):
                                foreach ( $media as $item ):

                                    $image_url = wp_get_attachment_image_url($item, 'ih-content-full');?>

                                    <div class="single-memory-bio-media">
                                            <div class="single-memory-bio-media__item">
                                                <img src="<?php echo esc_url($image_url); ?>"/>
                                            </div>
                                    </div>
                        <?php
                            endforeach;
                            endif;
                        ?>
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


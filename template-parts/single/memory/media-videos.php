<?php

/**
 * Memory single page.
 * Media section - Videos.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $id = $args['id'] ?? null ) return;

if( ! $videos = get_field( 'video', $id ) ?: null ) return;
?>

<div class="media-videos">
	<div class="single-memory-media-title">
		<?php esc_html_e( 'Відео', 'inheart' ) ?>
	</div>

	<div class="media-videos-list flex flex-wrap">
		<?php
		foreach( $videos as $key => $video ){
			$class	= $key > 4 ? ' hidden' : '';
			$file	= $video['file'];
			$poster	= $video['poster'];
			$title	= get_the_title( $file );
			?>
			<div class="media-video<?php echo esc_attr( $class ) ?>">
				<div class="media-video-top">
					<div class="media-photo-inner">
						<video src="<?php echo wp_get_attachment_url( $file ) ?>" poster="<?php echo wp_get_attachment_image_url( $poster, 'ih-illustration' ) ?>"></video>
						<div class="media-photo-overlay"></div>
					</div>
					<div class="media-photo-cursor-text">
						<?php esc_html_e( 'Play', 'inheart' ) ?>
					</div>
				</div>
				<div class="media-video-bottom">
					<div class="media-video-name">
						<?php echo esc_html( $title ) ?>
					</div>
					<div class="media-video-duration">
						<?php echo ih_prettify_duration( wp_get_attachment_metadata( $file )['length'] ) ?>
					</div>
				</div>
			</div>
			<?php
		}

		if( count( $videos ) - 5 > 0 ){
			?>
			<div class="media-video media-video-more">
				<div class="media-video-top">
					<div class="media-photo-inner">
						<div class="media-photo-overlay"></div>
					</div>
					<div class="media-video-more-text flex direction-column align-center justify-center">
						<div><?php esc_html_e( 'та ще', 'inheart' ) ?></div>
						<div class="count"><?php echo count( $videos ) - 5 ?></div>
						<div><?php esc_html_e( 'Перегалянути всі відео', 'inheart' ) ?></div>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>

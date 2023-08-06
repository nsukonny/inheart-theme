<?php

/**
 * New Memory page template.
 * Step 4, video item.
 *
 * @see Page Template: New Memory -> Step 4
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $video = $args['video'] ?? null ) return;

// If this is uploaded video file.
if( $file_id = $video['file'] ){
	$poster		= has_post_thumbnail( $file_id ) ? get_the_post_thumbnail_url( $file_id, 'ih-profile-media' ) : '';
	$poster_id	= $video['poster'] ?? '';
	$file_meta	= wp_get_attachment_metadata( $file_id );
	$duration	= ih_prettify_duration( $file_meta['length'] );
	?>
	<div class="droparea-img-loaded droparea-video-loaded">
		<div class="droparea-video-wrapper">
			<video
				src="<?php echo wp_get_attachment_url( $file_id ) ?>"
				poster="<?php echo esc_url( $poster ) ?>"
				data-poster-id="<?php echo esc_attr( $poster_id ) ?>"
			></video>
			<div
				class="droparea-img-delete flex align-center justify-center"
				data-id="<?php echo esc_attr( $file_id ) ?>"
				data-is-video="1"
			>
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<mask id="mask_<?php echo esc_attr( $file_id ) ?>" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="5" y="3" width="14" height="18">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M14.79 3.29L15.5 4H18C18.55 4 19 4.45 19 5C19 5.55 18.55 6 18 6H6C5.45 6 5 5.55 5 5C5 4.45 5.45 4 6 4H8.5L9.21 3.29C9.39 3.11 9.65 3 9.91 3H14.09C14.35 3 14.61 3.11 14.79 3.29ZM6 19C6 20.1 6.9 21 8 21H16C17.1 21 18 20.1 18 19V9C18 7.9 17.1 7 16 7H8C6.9 7 6 7.9 6 9V19ZM9 9H15C15.55 9 16 9.45 16 10V18C16 18.55 15.55 19 15 19H9C8.45 19 8 18.55 8 18V10C8 9.45 8.45 9 9 9Z" fill="black"/>
					</mask>
					<g mask="url(#mask_<?php echo esc_attr( $file_id ) ?>)">
						<rect width="24" height="24" fill="currentColor"/>
					</g>
				</svg>
			</div>
		</div>
		<div class="droparea-video-title"><?php echo get_the_title( $file_id ) ?></div>
		<div class="droparea-video-duration"><?php echo esc_html( $duration ) ?></div>
	</div>
	<?php
}else{
	if( $external_url = $video['link'] ){
		$key = $args['key'] ?? 0;
		?>
		<div class="droparea-img-loaded droparea-video-loaded">
			<div class="droparea-video-wrapper">
				<iframe src="<?php echo esc_url( $external_url ) ?>"></iframe>
				<div
					class="droparea-img-delete flex align-center justify-center"
					data-id="<?php echo esc_attr( $key ) ?>"
					data-is-video="1"
				>
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<mask id="mask_<?php echo esc_attr( $key ) ?>" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="5" y="3" width="14" height="18">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M14.79 3.29L15.5 4H18C18.55 4 19 4.45 19 5C19 5.55 18.55 6 18 6H6C5.45 6 5 5.55 5 5C5 4.45 5.45 4 6 4H8.5L9.21 3.29C9.39 3.11 9.65 3 9.91 3H14.09C14.35 3 14.61 3.11 14.79 3.29ZM6 19C6 20.1 6.9 21 8 21H16C17.1 21 18 20.1 18 19V9C18 7.9 17.1 7 16 7H8C6.9 7 6 7.9 6 9V19ZM9 9H15C15.55 9 16 9.45 16 10V18C16 18.55 15.55 19 15 19H9C8.45 19 8 18.55 8 18V10C8 9.45 8.45 9 9 9Z" fill="black"/>
						</mask>
						<g mask="url(#mask_<?php echo esc_attr( $key ) ?>)">
							<rect width="24" height="24" fill="currentColor"/>
						</g>
					</svg>
				</div>
			</div>
			<div class="droparea-video-title"><?php echo esc_html( basename( $external_url ) ) ?></div>
		</div>
		<?php
	}
}


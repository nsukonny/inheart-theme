<?php

/**
 * Memory single page.
 * Media section - Links.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $id = $args['id'] ?? null ) return;

if( ! $links = get_field( 'links', $id ) ?: null ) return;
?>

<div class="media-links flex flex-wrap align-start">
	<div class="media-links-title">
		<?php esc_html_e( 'Посилання на соціальні мережі та згадування в інтернеті', 'inheart' ) ?>
	</div>

	<div class="media-links-list">
		<?php
		foreach( $links as $link ){
			$url	= $link['url'];
			$title	= $link['title'];

			if( ! $url || ! $title ) continue;
			?>
			<a
				href="<?php echo esc_url( $url ) ?>"
				class="media-link flex flex-wrap align-center"
				title="<?php printf( esc_html__( 'Відкрити %s у новій вкладці', 'inheart' ), $title ) ?>"
				target="_blank"
			>
				<span class="media-link-title">
					<?php echo esc_html( $title ) ?>
				</span>
				<span class="media-link-url">
					<?php echo esc_html( $url ) ?>
				</span>
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<mask id="mask0_3149_42468" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="4" y="4" width="16" height="16">
						<path d="M9.29563 5.70312C9.29563 6.26312 9.74563 6.70312 10.2956 6.70312H15.8856L4.99562 17.5931C4.60563 17.9831 4.60563 18.6131 4.99562 19.0031C5.38563 19.3931 6.01562 19.3931 6.40562 19.0031L17.2956 8.11313V13.7031C17.2956 14.2531 17.7456 14.7031 18.2956 14.7031C18.8456 14.7031 19.2956 14.2531 19.2956 13.7031V5.70312C19.2956 5.15312 18.8456 4.70312 18.2956 4.70312H10.2956C9.74563 4.70312 9.29563 5.15312 9.29563 5.70312Z" fill="black"/>
					</mask>
					<g mask="url(#mask0_3149_42468)">
						<rect width="24" height="24" fill="#F7B941"/>
					</g>
				</svg>
				<div class="media-photo-cursor-text">
					<?php esc_html_e( 'Перейти', 'inheart' ) ?>
				</div>
			</a>
			<?php
		}
		?>
	</div>
</div>


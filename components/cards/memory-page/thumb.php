<?php

/**
 * Memory page card thumb.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $id = $args['id'] ?? null ) return;

$lifetime	= ih_get_lifetime_numbers( $id );
$years		= $lifetime['years'];
$months		= $lifetime['months'];
$weeks		= $lifetime['weeks'];
$days		= $lifetime['days'];
$hours		= $lifetime['hours'];
?>

<div class="memory-card-thumb">
	<div class="memory-card-thumb-border">
		<svg viewBox="-20 -20 560 800" class="page-head-portrait-text">
			<defs>
				<linearGradient id="Gradient" gradientTransform="rotate(90)">
					<stop offset="0" stop-color="white" stop-opacity="1" />
					<stop offset="1" stop-color="white" stop-opacity="0" />
				</linearGradient>
				<mask id="Mask">
					<rect x="-20" y="-20" width="100%" height="70%" fill="url(#Gradient)" />
				</mask>
			</defs>

			<path
					id="curve"
					d="M265.5,651h-16c0,0-249.5,0-249.5-249.5l0-152C0,249.5,0,0,249.5,0l16,0c0,0,249.5,0,249.5,249.5v152
			C515,401.5,515,651,265.5,651"
					fill="transparent"
			></path>

			<text mask="url(#Mask)">
				<textPath href="#curve" startOffset="50%" text-anchor="middle">
					<?php echo ih_get_memory_page_name( $id ) ?>
				</textPath>
			</text>
		</svg>
	</div>

	<div class="memory-card-thumb-img">
		<?php
		if( has_post_thumbnail( $id ) ) {
			echo get_the_post_thumbnail( $id, 'full' );
		} else {
			$default_thumb = get_field( 'default_memory_page_thumbnail', 'option' );
			if( $default_thumb ) {
				echo wp_get_attachment_image( $default_thumb['id'], 'full' );
			}
		}
		?>
	</div>
</div>


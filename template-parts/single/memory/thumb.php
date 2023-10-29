<?php

/**
 * Memory single page.
 * Top section thumb.
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

<div class="single-memory-thumb">
	<?php if( has_post_thumbnail( $id ) ) echo get_the_post_thumbnail( $id, 'full' ) ?>

	<div class="single-memory-thumb-border">
		<svg viewBox="-20 -20 560 800" class="page-head-portrait-text">
			<path
				id="curve"
				d="
					M250.6289 0.9102h16s249.5
					0 249.5
					249.5v152s0 249.5
					-249.5 249.5h-16s-249.5
					0 -249.5
					-249.5v-152s0 -249.5
					249.5 -249.5
				"
				fill="transparent"
			></path>
			<text>
				<textPath xlink:href="#curve" startOffset="0">
					<?php
					printf(
						esc_html__( '%d років ~ %d місяців ~ %d тижнів ~ %d днів ~ %d годин %d років ~ %d місяців ~ %d тижнів ~ %d днів ~ %d годин %d років ~ %d місяців ~ %d тижнів ~ %d днів ~ %d годин %s років ~ %s місяців ~ %d тижнів ~ %d днів ~ %d годин %s років ~ %s місяців ~ %d тижнів ~ %d днів ~ %d годин', 'inheart' ),
						$years, $months, $weeks, $days, $hours,
						$years, $months, $weeks, $days, $hours,
						$years, $months, $weeks, $days, $hours,
						$years, $months, $weeks, $days, $hours,
						$years, $months, $weeks, $days, $hours
					);
					?>
				</textPath>
			</text>
		</svg>
	</div>
</div>


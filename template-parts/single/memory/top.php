<?php

/**
 * Memory single page.
 * Top section.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $id = $args['id'] ?? null ) return;

$first_name		= get_field( 'first_name', $id );
$last_name		= get_field( 'last_name', $id );
$middle_name	= get_field( 'middle_name', $id );
$born_at		= get_field( 'born_at', $id );
$died_at		= get_field( 'died_at', $id );
$born_at		= date( 'jS M Y', strtotime( $born_at ) );
$died_at		= date( 'jS M Y', strtotime( $died_at ) );

$diff	= abs( strtotime( $died_at ) - strtotime( $born_at ) );
$years	= floor( $diff /  (365 * 60 * 60 * 24 ) );
$months	= floor( $diff / ( 30 * 60 * 60 * 24 ) );
$weeks	= floor( $diff / ( 60 * 60 * 24 * 7 ) );
$days	= floor( $diff / ( 60 * 60 * 24 ) );
$hours	= floor( $diff / ( 60 * 60 ) );
?>

<section class="single-memory-top">
	<div class="container">
		<div class="single-memory-top-inner flex direction-column align-center">
			<?php
			if( has_post_thumbnail( $id ) ){
				?>
				<div class="single-memory-thumb">
					<?php echo get_the_post_thumbnail( $id, 'full' ) ?>
					<div class="single-memory-thumb-border">
						<svg viewBox="-20 -20 560 800" width="560" class="page-head-portrait-text">
							<path
								id="curve"
								d="M250.6289 0.9102h16s249.5
									0 249.5
									249.5v152s0 249.5
									-249.5 249.5h-16s-249.5
									0 -249.5
									-249.5v-152s0 -249.5
									249.5 -249.5"
								fill="transparent"
							></path>
							<text>
								<textPath xlink:href="#curve" startOffset="1000">
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
				<?php
			}
			?>

			<div class="single-memory-info">
				<div class="single-memory-name">
					<?php echo esc_html( "$first_name $middle_name" ) ?>
				</div>
				<div class="single-memory-lastname flex align-end justify-center">
					<div class="single-memory-date hide-before-lg">
						<?php echo esc_html( $born_at ) ?>
					</div>
					<div class="single-memory-name">
						<?php echo esc_html( $last_name ) ?>
					</div>
					<div class="single-memory-date hide-before-lg">
						<?php echo esc_html( $died_at ) ?>
					</div>
				</div>
				<div class="single-memory-date hide-after-lg">
					<?php echo esc_html( "$born_at - $died_at" ) ?>
				</div>
			</div>
		</div>
	</div>
</section>


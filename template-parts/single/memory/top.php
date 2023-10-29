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
$born_at		= ih_convert_input_date( get_field( 'born_at', $id ) );
$died_at		= ih_convert_input_date( get_field( 'died_at', $id ) );
?>

<section class="single-memory-top">
	<div class="container">
		<div class="single-memory-top-inner flex direction-column align-center">
			<?php get_template_part( 'template-parts/single/memory/thumb', null, ['id' => $id] ) ?>

			<div class="single-memory-info">
				<div class="single-memory-name">
					<?php echo esc_html( "$first_name $middle_name" ) ?>
				</div>
				<div class="single-memory-lastname flex align-end justify-center">
					<div class="single-memory-date hide-before-md">
						<?php echo esc_html( $born_at ) ?>
					</div>
					<div class="single-memory-name">
						<?php echo esc_html( $last_name ) ?>
					</div>
					<div class="single-memory-date hide-before-md">
						<?php echo esc_html( $died_at ) ?>
					</div>
				</div>
				<div class="single-memory-date hide-after-md flex flex-wrap align-center justify-between">
					<?php echo esc_html( $born_at ), '<span></span>', esc_html( $died_at ) ?>
				</div>
			</div>
		</div>
	</div>
</section>


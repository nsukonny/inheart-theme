<?php

/**
 * Memory single page.
 * Epitaph section.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $id = $args['id'] ?? null ) return;

$epitaph			= get_field( 'epitaphy', $id );
$epitaph_lastname	= get_field( 'epitaph_lastname', $id );
$epitaph_firstname	= get_field( 'epitaph_firstname', $id );
$epitaph_name		= "$epitaph_lastname $epitaph_firstname";
$epitaph_role		= get_field( 'epitaph_role', $id );
?>

<section class="single-memory-epitaph">
	<div class="container">
		<div class="single-memory-epitaph-inner">
			<?php
			if( $epitaph )
				echo '<div class="single-memory-epitaph-text">' . esc_html( $epitaph ) . '</div>';

			if( $epitaph_name )
				echo '<div class="single-memory-epitaph-name">' . esc_html( $epitaph_name ) . '</div>';

			if( $epitaph_role )
				echo '<div class="single-memory-epitaph-role">' . esc_html( $epitaph_role ) . '</div>';
			?>
		</div>
	</div>
</section>


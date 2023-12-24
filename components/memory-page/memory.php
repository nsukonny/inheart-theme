<?php

/**
 * Memory page single.
 * Memories section, single memory layout.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $memory_id = $args['id'] ?? null ) return;

$text		= get_field( 'content', $memory_id );
$length		= 340;
$excerpt	= mb_strlen( $text ) > $length
			? mb_substr( $text, 0, $length ) . '<span class="read-more">' . __( 'Читати далі', 'inheart' ) . '</span>'
			: '';
$fullname	= get_field( 'full_name', $memory_id );
$role		= get_field( 'role', $memory_id );

if( ! $text ) return;
?>

<div class="single-memory-memories-item">
	<?php
	if( has_post_thumbnail( $memory_id ) )
		echo '<div class="single-memory-memories-thumb flex justify-center">',
			get_the_post_thumbnail( $memory_id, 'ih-memory-photo' ),
		'</div>';

	if( $text ){
		?>
		<div class="single-memory-memories-text">
			<?php
			if( $excerpt ) echo '<div class="single-memory-memories-excerpt">', $excerpt, '</div>';
			echo '<div class="single-memory-memories-content', ( $excerpt ? ' hidden' : '' ), '">', $text, '</div>';
			?>
		</div>
		<?php
	}

	if( $fullname ) echo '<div class="single-memory-memories-name">', esc_html( $fullname ), '</div>';

	if( $text ) echo '<div class="single-memory-memories-role">', esc_html( $role ), '</div>';
	?>
</div>


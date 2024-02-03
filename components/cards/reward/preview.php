<?php

/**
 * Reward preview.
 *
 * @package    WordPress
 * @subpackage inheart
 */

if( ! $id = $args['id'] ?? null ) return;
?>

<div class="reward-preview flex direction-column align-center" data-id="<?php echo esc_attr( $id ) ?>">
	<?php
	if( has_post_thumbnail( $id ) )
		echo '<div class="reward-preview-thumb">',
			get_the_post_thumbnail( $id, 'medium', ['loading' => 'lazy'] ),
		'</div>';
	?>

	<div class="reward-preview-title"><?php echo get_the_title( $id ) ?></div>
</div>


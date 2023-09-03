<?php

/**
 * Memory single page.
 * Media section.
 *
 * @package WordPress
 * @subpackage inheart
 */
?>

<section class="single-memory-media">
	<div class="container">
		<h2 class="single-memory-heading">
			<?php esc_html_e( 'Медiа-файли', 'inheart' ) ?>
		</h2>

		<?php
		get_template_part( 'template-parts/single/memory/media', 'photos', ['id' => $args['id']] );
		get_template_part( 'template-parts/single/memory/media', 'videos', ['id' => $args['id']] );
		?>
	</div>
</section>


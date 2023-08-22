<?php

/**
 * Profile memory pages.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $memory_pages = $args['pages'] ?? null ) return;

$title = get_field( 'memory_pages_title' );
?>

<section class="profile-memories">
	<div class="container">
		<div class="profile-memories-inner">
			<?php
			if( $title )
				echo '<h1 class="profile-memories-title">' . esc_html( $title ) . '</h1>';
			?>

			<div class="profile-memories-list flex flex-wrap">
				<?php
				foreach( $memory_pages as $page )
					get_template_part( 'template-parts/profile/memory-card', null, ['id' => $page->ID] );
				?>
			</div>
		</div>
	</div>
</section>


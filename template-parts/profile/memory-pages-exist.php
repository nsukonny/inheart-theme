<?php

/**
 * Profile memory pages.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $memory_pages = $args['pages'] ?? null ) return;
?>

<section class="profile-memories profile-body">
	<?php get_template_part( 'components/profile/memory-pages/title' ) ?>

	<div class="profile-memories-list flex flex-wrap">
		<?php
		foreach( $memory_pages as $page )
			get_template_part( 'components/cards/memory-page/card', null, ['id' => $page->ID] );
		?>
	</div>
</section>


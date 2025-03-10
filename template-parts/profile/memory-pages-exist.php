<?php

/**
 * Profile memory pages.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $memory_pages = $args['pages'] ?? null ) return;

$class             = ( isset( $args['hide'] ) && $args['hide'] ) ? ' hidden' : '';
$draft_memory_page = $args['draft'] ?? null;
?>

<section class="profile-memories profile-body<?php echo esc_attr( $class ) ?>">
	<?php get_template_part( 'components/profile/memory-pages/title', null, [ 'draft' => $draft_memory_page ] ) ?>

	<div class="profile-memories-list flex flex-wrap">
		<?php
		if ( $draft_memory_page ) {
			get_template_part( 'components/cards/memory-page/card', null, [ 'id' => $draft_memory_page->ID ] );
		}

		foreach( $memory_pages as $page ) {
			get_template_part( 'components/cards/memory-page/card', null, [ 'id' => $page->ID ] );
		}
		?>
	</div>
</section>


<?php

/**
 * Profile Settings page template.
 * Memory pages plan part.
 *
 * @package WordPress
 * @subpackage inheart
 */

$author_id		= get_current_user_id();
$memory_pages	= get_posts( [
	'post_type'	=> 'memory_page',
	'author'	=> $author_id
] );

if( empty( $memory_pages ) ) return;
?>

<div class="profile-settings-plan">
	<div class="profile-settings-heading">
		<?php esc_html_e( 'Тарифний план', 'inheart' ) ?>
	</div>

	<div class="profile-settings-pages flex flex-wrap">
		<?php
		foreach( $memory_pages as $page )
			get_template_part( 'template-parts/profile-settings/page-preview', null, ['id' => $page->ID] );
		?>
	</div>
</div><!-- .profile-settings-plan -->


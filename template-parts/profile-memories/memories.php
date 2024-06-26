<?php

/**
 * Profile Memories page template.
 * Memories part.
 *
 * @package WordPress
 * @subpackage inheart
 */

$memory_pages = get_posts( [
	'post_type'     => 'memory_page',
    'post_status'   => 'publish',
	'numberposts'   => -1,
    'author__in'    => get_current_user_id()
] );

// No memory pages.
if( empty( $memory_pages ) ){
	get_template_part( 'components/profile/memories/no-memories', null, [ 'type' => 'others' ] );
}else{
	$memory_pages_ids = [];

	foreach( $memory_pages as $page ) $memory_pages_ids[] = $page->ID;

	$memories_query = new WP_Query( [
		'post_type' => 'memory',
		'post_status' => 'any',
		'posts_per_page' => -1,
		'meta_query' => [
			[
				'key' => 'memory_page',
				'value' => $memory_pages_ids,
				'compare' => 'IN'
			],
			[
				'key' => 'is_rejected',
				'compare' => 'NOT EXISTS'
			]
		]
	] );

	if( $memories_query->have_posts() ){
		echo '<section class="profile-memories-list">';

		while( $memories_query->have_posts() ){
			$memories_query->the_post();
			get_template_part( 'components/cards/memory/preview', null, [
				'id' => get_the_ID(),
				'type' => 'others'
			] );
		}
		wp_reset_query();

		echo '</section>';
	}
	else{
		get_template_part( 'components/profile/memories/no-memories', null, [ 'type' => 'others' ] );
	}
}

get_template_part(
	'components/popup/popup',
	null,
	[
		'text'		=> __( 'Дійсно видалити спогад?', 'inheart' ),
		'class'		=> 'delete',
		'label_yes'	=> __( 'Видалити', 'inheart' ),
		'label_no'	=> __( 'Ні', 'inheart' )
	]
);
get_template_part(
	'components/popup/popup',
	null,
	[
		'text'		=> __( 'Дійсно опубліковати спогад?', 'inheart' ),
		'class'		=> 'publish',
		'label_yes'	=> __( 'Опубліковати', 'inheart' ),
		'label_no'	=> __( 'Ні', 'inheart' )
	]
);


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
$memory_pages_ids = [];

foreach( $memory_pages as $page ) $memory_pages_ids[] = $page->ID;

$memories_query = new WP_Query( [
	'post_type'     => 'memory',
	'post_status'   => 'any',
	'posts_per_page'=> -1,
    'meta_query'    => [[
		'key'       => 'memory_page',
		'value'     => $memory_pages_ids,
		'compare'   => 'IN'
    ]]
] );

if( $memories_query->have_posts() ){
    echo '<section class="profile-memories-list">';

    while( $memories_query->have_posts() ){
        $memories_query->the_post();
        get_template_part(
			'template-parts/add-new-memories/preview',
			null,
			[
				'id'	=> get_the_ID(),
				'type'	=> 'others'
			]
		);
    }
    wp_reset_query();

    echo '</section>';
}else{
    get_template_part( 'components/profile/memories/no-memories', null, ['type' => 'others'] );
}


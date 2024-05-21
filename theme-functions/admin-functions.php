<?php

/**
 * Theme custom functions for the Admin Console.
 *
 * @package    WordPress
 * @subpackage inheart
 */

/**
 * Register custom column for the memory pages.
 */
add_filter( 'manage_memory_page_posts_columns', function( $defaults ){
	$defaults['attachments-size'] = 'Завантажено (Мб)';

	return $defaults;
} );
add_action( 'manage_memory_page_posts_custom_column', function( $column_name, $post_id ){
	if( $column_name == 'attachments-size' ){
		echo ih_admin_get_memory_page_attachments( $post_id );
	}
}, 10, 2 );

function ih_admin_get_memory_page_attachments( int $post_id ): float
{
	if( get_post_type( $post_id ) !== 'memory_page' ) return 0;

	$attachments = get_posts( [
		'post_type'      => 'attachment',
		'posts_per_page' => -1,
		'post_parent'    => $post_id
	] );

	if( empty( $attachments ) ) return 0;

	$size = 0;

	foreach( $attachments as $attachment ){
		$attach_id = $attachment->ID;

		// For example, attached video might have a poster image.
		if( has_post_thumbnail( $attach_id ) )
			$size += filesize( get_attached_file( get_post_thumbnail_id( $attach_id ) ) );

		$size += filesize( get_attached_file( $attach_id ) );
	}

	return bcdiv( $size, 1048576, 2 );
}


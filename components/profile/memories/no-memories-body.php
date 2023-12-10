<?php

/**
 * Profile Memories - no memories yet.
 *
 * @package WordPress
 * @subpackage inheart
 */

$id		= $args['id'] ?? get_the_ID();
$img	= get_field( 'no_memory_pages_img', $id );
$title	= ( ! isset( $args['type'] ) || $args['type'] === 'others' )
		? __( 'У вас ще немае спогадiв', 'inheart' )
		: __( 'Ви поки що не залишили спогадів', 'inheart' );

if( $img )
	echo '<div class="profile-memories-img">' . wp_get_attachment_image( $img['id'], 'ih-profile-media' ) . '</div>';

echo '<h1 class="profile-memories-title">' . esc_html( $title ) . '</h1>';


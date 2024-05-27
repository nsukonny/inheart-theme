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
	$defaults['is-expanded'] = 'Розширена';

	return $defaults;
} );
add_action( 'manage_memory_page_posts_custom_column', function( $column_name, $post_id ){
	if( $column_name === 'is-expanded' ) echo( get_field( 'is_expanded', $post_id ) ? 'Так' : 'Ні' );
}, 10, 2 );

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

/**
 * Get memory page attachments size.
 *
 * @param int $post_id
 * @return float
 */
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
		if( has_post_thumbnail( $attach_id ) ) $size += filesize( get_attached_file( get_post_thumbnail_id( $attach_id ) ) );

		$size += filesize( get_attached_file( $attach_id ) );
	}

	return bcdiv( $size, 1048576, 2 );
}

add_action('admin_enqueue_scripts', 'ih_admin_enqueue');
/**
 * Admin enqueue.
 *
 * @return void
 */
function ih_admin_enqueue(): void
{
	if( isset( $_GET['page'] ) && $_GET['page'] === 'email-addresses' )
		wp_enqueue_style( 'additional-features', THEME_URI . '/additional-features/admin/css/additional-features.css', [], THEME_VERSION );
}

add_action( 'admin_menu', function(){
	add_menu_page( 'Додаткові можливості', 'Додаткові можливості', 'manage_options', 'additional-features', 'ih_menu_email_addresses', '', 80 );
	add_submenu_page( 'additional-features', 'Email адреси', 'Email адреси', 'manage_options', 'email-addresses', 'ih_menu_email_addresses' );
	remove_submenu_page( 'additional-features', 'additional-features' );
} );
function ih_menu_email_addresses(): void
{
	?>
	<div class="wrap email-addresses-wrap">
		<h2 class="email-addresses-title"><?php echo get_admin_page_title() ?></h2>

		<?php get_template_part( 'additional-features/admin/templates/email-addresses' ) ?>
	</div>
	<?php
}

function ih_print_email_addresses_row( array $data ): void
{
	if( empty( $data ) ) return;

	echo '<div class="email-addresses-row">';

	foreach( $data as $col ) echo '<div class="email-addresses-col">', $col, '</div>';

	echo '</div>';
}


<?php

/**
 * Theme custom functions for the Admin Console.
 *
 * @package    WordPress
 * @subpackage inheart
 */

/**
 * Register custom column for the QR CPT.
 */
add_filter( 'manage_qr_posts_columns', function( $defaults ){
	$defaults['finished'] = 'Завершено';

	return $defaults;
} );
add_action( 'manage_qr_posts_custom_column', function( $column_name, $post_id ){
	if( $column_name === 'finished' ) echo( get_field( 'finished', $post_id ) ? 'Так' : 'Ні' );
}, 10, 2 );

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

add_action( 'admin_enqueue_scripts', 'ih_admin_enqueue' );
/**
 * Admin enqueue.
 *
 * @return void
 */
function ih_admin_enqueue(): void
{
	if( isset( $_GET['page'] ) && $_GET['page'] === 'email-addresses' ){
		wp_enqueue_style( 'additional-features', THEME_URI .
		                                         '/additional-features/admin/css/additional-features.css', [], THEME_VERSION );
	}
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

/**
 * Outputs html row layout for the table.
 *
 * @param array $data
 * @return void
 */
function ih_print_email_addresses_row( array $data ): void
{
	if( empty( $data ) ) return;

	echo '<div class="email-addresses-row">';

	foreach( $data as $col ) echo '<div class="email-addresses-col">', $col, '</div>';

	echo '</div>';
}

/**
 * Show browser download dialog for generated CSV file.
 *
 * @param $fileName
 * @param $assocDataArray
 * @return void
 */
function outputCsv( $fileName, $assocDataArray ): void
{
	ob_clean();
	header( 'Pragma: public' );
	header( 'Expires: 0' );
	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	header( 'Cache-Control: private', false );
	header( 'Content-Type: text/csv' );
	header( 'Content-Disposition: attachment;filename=' . $fileName );

	if( isset( $assocDataArray['0'] ) ){
		$fp = fopen( 'php://output', 'w' );

		foreach( $assocDataArray as $values ) fputcsv( $fp, $values, ';' );

		fclose( $fp );
	}

	ob_flush();
}

add_action( 'admin_init', 'ih_download_csv' );
/**
 * Create CSV file from the array of arrays and download from browser.
 *
 * @return void
 */
function ih_download_csv(): void
{
	if( ! isset( $_POST['download-csv'] ) ) return;

	$date_start     = isset( $_POST['csv-date-start'] ) && $_POST['csv-date-start'] ? $_POST['csv-date-start'] : '';
	$date_end       = isset( $_POST['csv-date-end'] ) && $_POST['csv-date-end'] ? $_POST['csv-date-end']
		: date( 'Y-m-d' );
	$mp_post_status = isset( $_POST['csv-mp-status'] ) && $_POST['csv-mp-status'] ? $_POST['csv-mp-status'] : '';
	$mp_is_expanded = isset( $_POST['csv-mp-is-expanded'] ) && $_POST['csv-mp-is-expanded'] ? $_POST['csv-mp-is-expanded'] : '';
	$users          = ( isset( $_POST['csv-user-ids'] ) && $_POST['csv-user-ids'] )
		? explode( ',', $_POST['csv-user-ids'] ) : [];

	if( ! empty( $users ) ){
		if( $date_start && $date_end ){
			// Reset dates if they are incorrect.
			if( strtotime( $date_start ) > strtotime( $date_end ) ){
				$users = get_users( ['include' => array_map( fn( $el ) => ( int ) $el, $users )] );
			}else{    // Dates are OK.
				$users = get_users( [
					'include'    => array_map( fn( $el ) => ( int ) $el, $users ),
					'date_query' => [
						[
							'after'     => "$date_start 00:00:00",
							'before'    => "$date_end 23:59:59",
							'inclusive' => true
						]
					]
				] );
			}
		}else{
			$users = get_users( ['include' => array_map( fn( $el ) => ( int ) $el, $users )] );
		}
	}else{
		$users = get_users();
	}

	if( empty( $users ) ) return;

	$data  = [
		[
			'index'       => '#',
			'email'       => 'Пошта',
			'registered'  => 'Зареєстрований',
			'name'        => "Ім'я",
			'memory_page' => "Сторінка пам'яті",
			'status'      => 'Статус'
		]
	];
	$index = 1;

	foreach( $users as $user ){
		$user_id      = $user->ID;
		$user_email   = $user->user_email;
		$user_name    = $user->display_name;
		$registered   = $user->user_registered;
		$mp_args    = [
			'post_type'   => 'memory_page',
			'numberposts' => -1,
			'post_status' => $mp_post_status ?: 'any',
			'author__in'  => $user_id
		];

		if( $mp_is_expanded === 'paid' ){
			$mp_args['meta_key']   = 'is_expanded';
			$mp_args['meta_value'] = true;
		}else if( $mp_is_expanded === 'free' ){
			$mp_args['meta_query'] = [
				[
					'key'     => 'is_expanded',
					'value'   => true,
					'compare' => '!='
				],
				[
					'key'     => 'is_expanded',
					'compare' => 'NOT EXISTS'
				],
				'relation' => 'OR'
			];
		}

		$memory_pages = get_posts( $mp_args );

		if( empty( $memory_pages ) ) continue;

		foreach( $memory_pages as $memory_page ){
			$mp_id     = $memory_page->ID;
			$mp_title  = $memory_page->post_title;
			$is_paid   = get_field( 'is_expanded', $mp_id ) ? 'Платна' : 'Безкоштовна';
			$mp_status = ih_ukr_post_status( get_post_status( $mp_id ) ) . ', ' . $is_paid;
			$data[]    = [
				'index'       => $index,
				'email'       => $user_email,
				'registered'  => date( 'd.m.Y', strtotime( $registered ) ),
				'name'        => $user_name,
				'memory_page' => $mp_title,
				'status'      => $mp_status
			];
			$index++;
		}
	}

	outputCsv( 'emails.csv', $data );
	exit;
}


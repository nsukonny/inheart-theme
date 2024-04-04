<?php

add_action( 'rest_api_init', 'ih_rest_api_init_mono' );
function ih_rest_api_init_mono(): void
{
	register_rest_route( 'mono/acquiring', '/status', [
		'methods'				=> 'POST',
		'permission_callback'	=> '__return_true',
		'callback'				=> 'ih_mono_handle_status'
	] );
}

/**
 * MonoBank webhook callback.
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Request
 */
function ih_mono_handle_status( WP_REST_Request $request ): WP_REST_Request
{
	date_default_timezone_set('UTC');
	$current_date = date( 'd.m.Y H:i:s' );

	$invoice_id = $request->get_param( 'invoiceId' ) ?? null;
	$status		= $request->get_param( 'status' ) ?? null;
	$modified	= $request->get_param( 'modifiedDate' ) ?? null;

	if( ! $invoice_id || ! $status || ! $modified ){
		file_put_contents(ABSPATH . '/orders.log',  "$current_date __ No data passed for invoice ID $invoice_id" . PHP_EOL, FILE_APPEND );
		return $request;
	}

	$order = get_posts( [
		'post_type'		=> 'expanded-page',
		'numberposts'	=> -1,
		'meta_query'	=> [ [
			'key'	=> 'invoice_id',
			'value'	=> $invoice_id
		] ]
	] );

	if( empty( $order ) ){
		file_put_contents(ABSPATH . '/orders.log',  "$current_date __ No order with invoice ID $invoice_id" . PHP_EOL, FILE_APPEND );
		return $request;
	}

	$order_id		= $order[0]->ID;
	$prev_modified	= get_field( 'status_modified_date', $order_id );
	$modified		= strtotime( $modified );

	if( $modified <= $prev_modified ){
		file_put_contents(ABSPATH . '/orders.log',  "$current_date __ Modified date is old" . PHP_EOL, FILE_APPEND );
		return $request;
	}

	update_field( 'status', $status, $order_id );
	update_field( 'status_modified_date', $modified, $order_id );
	file_put_contents(ABSPATH . '/orders.log',  "$current_date __ Order $order_id updated with status $status" . PHP_EOL, FILE_APPEND );

	ih_send_email_on_status_change( $status, $order_id );
	ih_check_and_update_order_status( $order_id, $status );

	return $request;
}

/**
 * Send emails depending on Order status.
 *
 * @param string $status
 * @param int    $order_id
 * @return bool
 */
function ih_send_email_on_status_change( string $status, int $order_id ): bool
{
	if( ! $status || ! $order_id || get_post_type( $order_id ) !== 'expanded-page' ) return false;

	$invoice_id	= get_field( 'invoice_id', $order_id );
	$firstname	= get_field( 'firstname', $order_id );
	$lastname	= get_field( 'lastname', $order_id );
	$fathername	= get_field( 'fathername', $order_id );
	$ordered	= get_field( 'ordered', $order_id );
	$email		= get_field( 'email', $order_id );
	$subject	= $body = '';

	switch( $status ){
		case 'created':
			$subject	= get_field( 'order_created_subject', 'option' );
			$body		= get_field( 'order_created_body', 'option' );
			break;

		case 'processing':
			$subject	= get_field( 'order_processing_subject', 'option' );
			$body		= get_field( 'order_processing_body', 'option' );
			break;

		case 'hold':
			$subject	= get_field( 'order_hold_subject', 'option' );
			$body		= get_field( 'order_hold_body', 'option' );
			break;

		case 'success':
			$subject	= get_field( 'order_success_subject', 'option' );
			$body		= get_field( 'order_success_body', 'option' );
			break;

		case 'failure':
			$subject	= get_field( 'order_failure_subject', 'option' );
			$body		= get_field( 'order_failure_body', 'option' );
			break;

		case 'reversed':
			$subject	= get_field( 'order_reversed_subject', 'option' );
			$body		= get_field( 'order_reversed_body', 'option' );
			break;

		case 'expired':
			$subject	= get_field( 'order_expired_subject', 'option' );
			$body		= get_field( 'order_expired_body', 'option' );
			break;
	}

	if( ! $subject || ! $body ) return false;

	$body = str_replace(
		['[invoice_id]', '[firstname]', '[lastname]', '[fathername]', '[ordered]'],
		[$invoice_id, $firstname, $lastname, $fathername, $ordered],
		$body
	);
	$body = str_replace(
		['https://https://', 'http://https://', 'http://http://'],
		['https://', 'https://', 'http://'],
		$body
	);

	add_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
	$send = wp_mail( $email, $subject, $body );
	remove_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );

	if( ! $send ) return false;

	return true;
}

add_action( 'wp_ajax_ih_ajax_load_cities', 'ih_ajax_load_cities' );
/**
 * Return cities list from Nova Poshta API.
 *
 * @return void
 */
function ih_ajax_load_cities(): void
{
	if( ! $city = ih_clean( $_POST['city'] ) ?? null )
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	if( ! $np_api_key = get_field( 'np_api_key', 'option' ) ?: null )
		wp_send_json_error( ['msg' => __( 'Невірний або відсутній API Key', 'inheart' )] );

	$all_cities	= ih_clean( $_POST['all'] );
	$body		= json_encode( [
		'apiKey'		=> $np_api_key,
		'modelName'		=> 'Address',
		'calledMethod'	=> 'getSettlements',
		'methodProperties'	=> [
			'FindByString'	=> $city,
			'Warehouse'		=> $all_cities ? 0 : 1
		]
	] );
	$res = wp_remote_post( 'https://api.novaposhta.ua/v2.0/json/', [
		'headers'		=> ['Content-Type' => 'application/json; charset=utf-8'],
		'data_format'	=> 'body',
		'body'			=> $body
	] );

	if( is_wp_error( $res ) || empty( $res ) ) wp_send_json_error( ['msg' => __( 'Немає даних', 'inheart' )] );

	$res_body = json_decode( $res['body'], true );

	if( $res_body['success'] === true ) wp_send_json_success( ['cities' => $res_body['data']] );

	wp_send_json_error( ['msg' => __( 'Помилка', 'inheart' )] );
}

add_action( 'wp_ajax_ih_ajax_load_departments', 'ih_ajax_load_departments' );
/**
 * Return city departments list from Nova Poshta API.
 *
 * @return void
 */
function ih_ajax_load_departments(): void
{
	if( ! $ref = ih_clean( $_POST['ref'] ) ?? null ) wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	if( ! $np_api_key = get_field( 'np_api_key', 'option' ) ?: null )
		wp_send_json_error( ['msg' => __( 'Невірний або відсутній API Key', 'inheart' )] );

	$body = json_encode( [
		'apiKey'		=> $np_api_key,
		'modelName'		=> 'Address',
		'calledMethod'	=> 'getWarehouses',
		'methodProperties'	=> [
			'SettlementRef' => $ref
		]
	] );
	$res = wp_remote_post( 'https://api.novaposhta.ua/v2.0/json/', [
		'headers'		=> ['Content-Type' => 'application/json; charset=utf-8'],
		'data_format'	=> 'body',
		'body'			=> $body
	] );

	if( is_wp_error( $res ) || empty( $res ) ) wp_send_json_error( ['msg' => __( 'Немає даних', 'inheart' )] );

	$res_body = json_decode( $res['body'], true );

	if( $res_body['success'] === true ) wp_send_json_success( ['departments' => $res_body['data']] );

	wp_send_json_error( ['msg' => __( 'Помилка', 'inheart' )] );
}

add_action( 'wp_ajax_ih_ajax_change_qty', 'ih_ajax_change_qty' );
/**
 * Change quantity of metal QR-codes.
 *
 * @return void
 */
function ih_ajax_change_qty(): void
{
	if( ! $count = ih_clean( $_POST['count'] ) ?? null )
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	if( ! $price = ih_get_expanded_page_order_price( $count ) )
		wp_send_json_error( ['msg' => __( 'Невірні дані товарів', 'inheart' )] );

	wp_send_json_success( ['price' => number_format( $price, 0, '', ' ' )] );
}

add_action( 'wp_ajax_ih_ajax_update_order_info', 'ih_ajax_update_order_info' );
/**
 * Update Order information on page expand.
 * Main changes depends on military or simple page type.
 *
 * @return void
 */
function ih_ajax_update_order_info(): void
{
	$page_id = ih_clean( $_POST['page'] );

	if( ! $page_id || get_post_type( $page_id ) !== 'memory_page' )
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	$page_theme = get_field( 'theme', $page_id );

	if( $page_theme === 'military' )
		wp_send_json_success( [
			'price' => number_format( ih_get_metal_qr_military_price(), 0, '', ' ' )
		] );

	wp_send_json_success( [
		'price' => number_format( ih_get_expanded_page_order_price(), 0, '', ' ' )
	] );
}

add_action( 'wp_ajax_ih_ajax_create_order', 'ih_ajax_create_order' );
/**
 * Create an Order.
 *
 * @return void
 */
function ih_ajax_create_order(): void
{
	$page_id		= ih_clean( $_POST['page'] );
	$email			= ih_clean( $_POST['email'] );
	$phone			= ih_clean( $_POST['phone'] );
	$city			= ih_clean( $_POST['city'] );
	$department		= ih_clean( $_POST['departments'] );
	$firstname		= ih_clean( $_POST['firstname'] );
	$lastname		= ih_clean( $_POST['lastname'] );
	$fathername		= ih_clean( $_POST['fathername'] );
	$qr_count		= ih_clean( $_POST['qr-count-qty'] );
	$customer_id	= get_current_user_id();

	if(
		! $page_id || ! $email || ! $phone || ! $city || ! $department ||
		! $firstname || ! $lastname || ! $fathername || ! $qr_count
	) wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	$page_theme = get_field( 'theme', $page_id );

	if( ! $price = ih_get_expanded_page_order_price( $qr_count, $page_theme === 'military' ) )
		wp_send_json_error( ['msg' => __( 'Невірні дані товарів', 'inheart' )] );

	if( ! $mono_token = get_field( 'mono_token', 'option' ) )
		wp_send_json_error( ['msg' => __( 'Невірний або відсутній токен', 'inheart' )] );

	// Make a request to MonoBank.
	$dest	= "Розширена сторінка пам'яті " . get_the_title( $page_id );
	$body	= json_encode( [
		'amount'			=> $price * 100,	// UAH kopecks.
		'redirectUrl'		=> get_the_permalink( pll_get_post( ih_get_order_created_page_id() ) ),
		'webHookUrl'		=> get_bloginfo( 'url' ) . '/wp-json/mono/acquiring/status',
		'paymentType'		=> 'debit',
		'merchantPaymInfo'	=> [
			'destination'	=> $dest,
			'comment'		=> $dest
		]
	] );
	$res = wp_remote_post( 'https://api.monobank.ua/api/merchant/invoice/create', [
		'headers'		=> [
			'Content-Type'	=> 'application/json; charset=utf-8',
			'X-Token'		=> $mono_token
		],
		'data_format'	=> 'body',
		'body'			=> $body
	] );

	if( is_wp_error( $res ) || empty( $res ) )
		wp_send_json_error( ['msg' => __( 'Немає відповіді від банку', 'inheart' )] );

	$res_body = json_decode( $res['body'], true );

	if( empty( $res_body ) )
		wp_send_json_error( ['msg' => __( 'Помилка під час створення запиту до оплати', 'inheart' )] );

	// Create new Order.
	$order_data = [
		'post_title'	=> "Замовлення від $lastname $firstname $fathername (ID: $customer_id, сторінка: $page_id)",
		'post_status'	=> 'publish',
		'post_type'		=> 'expanded-page'
	];
	$order_id = wp_insert_post( wp_slash( $order_data ) );

	if( is_wp_error( $order_id ) )
		wp_send_json_error( ['msg' => __( 'Не вдалося створити замовлення', 'inheart' )] );

	if( $page_theme === 'military' ){
		$ordered = "QR-код військового на металевій пластині - $qr_count шт. ($qr_count x " . ih_get_metal_qr_military_price() . " грн)\n" .
			"Загальна вартість: $price грн";
	}else{
		$ordered = "Розширена сторінка пам'яті - 1 шт. (" . ih_get_expanded_page_price() . " грн)\n" .
			"QR-код на металевій пластині - $qr_count шт. ($qr_count x " . ih_get_metal_qr_price() . " грн)\n" .
			"Загальна вартість: $price грн";
	}

	update_field( 'memory_page_id', $page_id, $order_id );
	update_field( 'firstname', $firstname, $order_id );
	update_field( 'lastname', $lastname, $order_id );
	update_field( 'fathername', $fathername, $order_id );
	update_field( 'email', $email, $order_id );
	update_field( 'phone', $phone, $order_id );
	update_field( 'customer_id', $customer_id, $order_id );
	update_field( 'city', $city, $order_id );
	update_field( 'department', $department, $order_id );
	update_field( 'invoice_id', $res_body['invoiceId'], $order_id );
	update_field( 'status_modified_date', 0, $order_id );
	update_field( 'ordered', $ordered, $order_id );

	// Update Customer's fathername if it's not exists yet.
	if( ! get_field( 'fathername', "user_$customer_id" ) )
		update_field( 'fathername', $fathername, "user_$customer_id" );

	// Update Customer's phone if it's not exists yet.
	if( ! get_field( 'phone', "user_$customer_id" ) )
		update_field( 'phone', $phone, "user_$customer_id" );

	update_field( 'city', $city, "user_$customer_id" );
	update_field( 'department', $department, "user_$customer_id" );

	wp_send_json_success( ['pageUrl' => $res_body['pageUrl']] );
}

function ih_get_invoice_status( int $order_id = 0 ): ?string
{
	// Invoice ID was not passed - get it from the latest Order of the current Customer.
	if( ! $order_id ) $order_id = ih_get_latest_invoice_id( get_current_user_id() );

	$invoice_id		= get_field( 'invoice_id', $order_id );
	$order_status	= get_field( 'status', $order_id );
	$prev_modified	= get_field( 'status_modified_date', $order_id );

	if( ! $mono_token = get_field( 'mono_token', 'option' ) ) return $order_status;

	$res = wp_remote_get( "https://api.monobank.ua/api/merchant/invoice/status?invoiceId=$invoice_id", [
		'headers' => ['X-Token' => $mono_token]
	] );

	if( is_wp_error( $res ) || empty( $res['body'] ) ) return $order_status;

	$res_body = json_decode( $res['body'], true );

	if( empty( $res_body['invoiceId'] ) || $res_body['invoiceId'] !== $invoice_id || empty( $res_body['status'] ) )
		return $order_status;

	$new_status	= $res_body['status'];
	$modified	= strtotime( $res_body['modifiedDate'] );

	if( $modified > $prev_modified ){
		$order_status = $new_status;
		update_field( 'status', $order_status, $order_id );
		update_field( 'status_modified_date', $modified, $order_id );
		ih_send_email_on_status_change( $order_status, $order_id );
		$order_status = get_field( 'status', $order_id );	// Get translated label instead of API Eng status.
		ih_check_and_update_order_status( $order_id, $new_status );
	}

	return $order_status;
}

function ih_get_latest_invoice_id( int $customer_id ): int
{
	$latest_invoice = get_posts( [
		'post_type'		=> 'expanded-page',
		'numberposts'	=> 1,
		'post_status'	=> 'publish',
		'meta_query' => [ [
			'key'	=> 'customer_id',
			'value'	=> $customer_id
		] ]
	] );

	if( empty( $latest_invoice ) ) return 0;

	return $latest_invoice[0]->ID;
}

/**
 * Check Order status and update necessary data.
 *
 * @param int    $order_id
 * @param string $new_status
 * @return bool
 */
function ih_check_and_update_order_status( int $order_id, string $new_status ): bool
{
	// If payment is success.
	if( $new_status === 'success' && ( $memory_page_id = get_field( 'memory_page_id', $order_id ) ) ){
		// Update Memory Page meta.
		update_field( 'is_expanded', 1, $memory_page_id );

		// Create new QR.
		$qr_data = [
			'post_type'		=> 'qr',
			'post_title'	=> "Замовлено для сторінки #$memory_page_id (" . get_the_title( $memory_page_id ) . ")",
			'post_status'	=> 'draft'
		];
		$qr_id = wp_insert_post( wp_slash( $qr_data ) );

		if( is_wp_error( $qr_id ) ) return false;

		wp_update_post( [
			'ID'			=> $qr_id,
			'post_name'		=> $qr_id,
			'post_status'	=> 'publish'
		] );

		$memory_page_url = get_the_permalink( $memory_page_id );
		update_field( 'memory_page_id', $memory_page_id, $qr_id );
		update_field( 'memory_page_url', $memory_page_url, $qr_id );

		/**
		 * Send email to Admin.
		 *
		 * @see Theme Settings -> Email Templates -> QR -> QR Created.
		 */
		$subject	= get_field( 'qr_created_subject', 'option' );
		$body		= get_field( 'qr_created_body', 'option' );

		if( $subject && $body ){
			$body = str_replace(
				['[qr_id]', '[qr_admin_url]', '[mp_id]', '[mp_admin_url]', '[mp_url]'],
				[$qr_id, get_edit_post_link( $qr_id ), $memory_page_id, get_edit_post_link( $memory_page_id ), $memory_page_url],
				$body
			);
			$body = str_replace(
				['https://https://', 'http://https://', 'http://http://'],
				['https://', 'https://', 'http://'],
				$body
			);

			add_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
			wp_mail( get_option( 'admin_email' ), $subject, $body );
			remove_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
		}

	}

	return true;
}


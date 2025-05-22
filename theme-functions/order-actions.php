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

	if( ! $req_body = $request->get_body() ?? null ){
		file_put_contents( ABSPATH . '/orders.log', "$current_date __: No request body provided." . PHP_EOL, FILE_APPEND );
		return $request;
	}

	$req_arr	= json_decode( $req_body, true );
	$invoice_id = $req_arr['invoiceId'] ?? null;
	$status		= $req_arr['status'] ?? null;
	$modified	= $req_arr['modifiedDate'] ?? null;

	if( ! $invoice_id || ! $status || ! $modified ){
		file_put_contents(ABSPATH . '/orders.log',  "$current_date __: No invoice data passed." . PHP_EOL, FILE_APPEND );
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
		file_put_contents(ABSPATH . '/orders.log',  "$current_date __: No order with provided invoice ID $invoice_id." . PHP_EOL, FILE_APPEND );
		return $request;
	}

	$order_id		= $order[0]->ID;
	$prev_modified	= get_field( 'status_modified_date', $order_id );
	$modified		= strtotime( $modified );

    if( 'success' !== $status && ( $modified <= $prev_modified ) ){
		file_put_contents(ABSPATH . '/orders.log',  "$current_date __: Invoice modified date is old." . PHP_EOL, FILE_APPEND );
		return $request;
	}

	update_field( 'status', $status, $order_id );
	update_field( 'status_modified_date', $modified, $order_id );
	file_put_contents(ABSPATH . '/orders.log',  "$current_date __: Order $order_id updated with new status $status." . PHP_EOL, FILE_APPEND );

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
	$count          = isset( $_POST['count'] ) ? ih_clean( $_POST['count'] ) : null;
	$memory_page_id = isset( $_POST['memoryPage'] ) ? ih_clean( $_POST['memoryPage'] ) : null;

	if( ! $count || ! $memory_page_id )
		wp_send_json_error( ['msg' => __( 'Невірні дані', 'inheart' )] );

	$page_theme = get_field( 'theme', $memory_page_id );

	if( ! $price = ih_get_expanded_page_order_price( $count, $page_theme === 'military' ) )
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
	$qr_count		= (int) ih_clean( $_POST['qr-count-qty'] );
	$customer_id	= get_current_user_id();
	$mp_author_id	= (int) get_post_field ( 'post_author', $page_id );	// Memory page author ID.

	// No Memory page ID provided or Memory page ID is incorrect.
	if( ! $page_id || get_post_type( $page_id ) !== 'memory_page' )
		wp_send_json_error( ['msg' => __( "Невірні дані сторінки пам'яті", 'inheart' )] );

	// Current User is not an author of this Memory page.
	if( $customer_id !== $mp_author_id )
		wp_send_json_error( ['msg' => __( "Ви не автор цієї сторінки пам'яті", 'inheart' )] );

	// No QR codes count provided.
	if( ! $qr_count )
		wp_send_json_error( ['msg' => __( 'Не вказана кількість QR кодів', 'inheart' )] );

	// Not all fields are filled.
	if(
		! $email || ! $phone || ! $city || ! $department ||
		! $firstname || ! $lastname || ! $fathername
	) wp_send_json_error( ['msg' => __( 'Заповніть всі поля', 'inheart' )] );

	$page_theme = get_field( 'theme', $page_id );

	if( ! $price = ih_get_expanded_page_order_price( $qr_count, $page_theme === 'military' ) )
		wp_send_json_error( ['msg' => __( 'Невірні дані товарів', 'inheart' )] );

	if( ! $mono_token = get_field( 'mono_token', 'option' ) )
		wp_send_json_error( ['msg' => __( 'Невірний або відсутній токен', 'inheart' )] );

	// Make a request to MonoBank.
    $dest   = "Розширена сторінка пам'яті ".get_the_title($page_id);
    $amount = $price * 100;
    $thumb  = has_post_thumbnail($page_id) ? get_the_post_thumbnail_url($page_id, 'thumbnail') : '';
    $body   = json_encode([
        'amount'           => $amount,    // UAH kopecks.
        'redirectUrl'      => get_the_permalink(pll_get_post(ih_get_order_created_page_id())),
        'webHookUrl'       => get_bloginfo('url').'/wp-json/mono/acquiring/status',
        'paymentType'      => 'debit',
        'merchantPaymInfo' => [
            'destination' => $dest,
            'comment'     => $dest,
            'basketOrder' => [
                [
                    'name'  => $dest,
                    'qty'   => $qr_count,
                    'sum'   => $amount / $qr_count,
                    'total' => $amount,
                    'unit'  => 'шт.',
                    'icon'  => $thumb,
                    'code'  => base64_encode($dest.'-'.time())
                ]
            ]
        ]
	] );
	$res  = wp_remote_post( 'https://api.monobank.ua/api/merchant/invoice/create', [
		'headers'     => [
			'Content-Type' => 'application/json; charset=utf-8',
			'X-Token'      => $mono_token
		],
		'data_format' => 'body',
		'body'        => $body
	] );

	if( is_wp_error( $res ) || empty( $res ) )
		wp_send_json_error( ['msg' => __( 'Немає відповіді від банку', 'inheart' )] );

	$res_body = json_decode( $res['body'], true );

	if( empty( $res_body ) )
		wp_send_json_error( ['msg' => __( 'Помилка під час створення запиту до оплати', 'inheart' )] );

	if( ! $invoice_id = $res_body['invoiceId'] ?? null )
		wp_send_json_error( ['msg' => __( 'Відповідь банку не містить ID рахунку', 'inheart' )] );

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
		$ordered = "QR-код на металевій пластині - $qr_count шт. ($qr_count x " . ih_get_metal_qr_price() . " грн)\n" .
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
	update_field( 'invoice_id', $invoice_id, $order_id );
	update_field( 'status_modified_date', 0, $order_id );
	update_field( 'ordered', $ordered, $order_id );
	update_field( 'status', 'created', $order_id );
	/**
	 * Send email to Admin.
	 *
	 * @see Theme Settings -> Email Templates -> Orders -> Order Created.
	 */
	$subject	= get_field( 'order_created_subject_admin', 'option' );
	$body		= get_field( 'order_created_body_admin', 'option' );

	if( $subject && $body ){
		$body = str_replace( ['https://[', 'http://['], '[', $body );
		$body = str_replace( [
			'[firstname]',
			'[lastname]',
			'[fathername]',
			'[invoice_id]',
			'[ordered]',
			'[order_admin_url]'
		], [
			$firstname,
			$lastname,
			$fathername,
			$invoice_id,
			$ordered,
			get_edit_post_link( $order_id )
		], $body );

		add_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
		wp_mail( ih_get_order_emails_array(), $subject, $body );
		remove_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
	}

	// Update Customer's fathername if it's not exists yet.
	if( ! get_field( 'fathername', "user_$customer_id" ) )
		update_field( 'fathername', $fathername, "user_$customer_id" );

	// Update Customer's phone if it's not exists yet.
	if( ! get_field( 'phone', "user_$customer_id" ) )
		update_field( 'phone', $phone, "user_$customer_id" );

	update_field( 'city', $city, "user_$customer_id" );
	update_field( 'department', $department, "user_$customer_id" );

	/**
	 * Send email to Customer.
	 *
	 * @see Theme Settings -> Email Templates -> Orders -> Order Created.
	 */
	$customer_subject = get_field( 'order_created_subject', 'option' );
	$customer_body    = get_field( 'order_created_body', 'option' );

	if( $customer_subject && $customer_body ){
		$customer_body = str_replace( ['https://[', 'http://['], '[', $customer_body );
		$customer_body = str_replace( [
			'[firstname]',
			'[lastname]',
			'[fathername]',
			'[invoice_id]',
			'[ordered]'
		], [
			$firstname,
			$lastname,
			$fathername,
			$invoice_id,
			$ordered
		], $customer_body );

		// Debug logging
		error_log('Attempting to send email to customer:');
		error_log('To: ' . $email);
		error_log('Subject: ' . $customer_subject);
		error_log('Body: ' . $customer_body);

		add_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
		$mail_sent = wp_mail( $email, $customer_subject, $customer_body );
		remove_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );

		// Log result
		error_log('Email send result: ' . ($mail_sent ? 'Success' : 'Failed'));
	} else {
		error_log('Email templates not found:');
		error_log('Subject template exists: ' . ($customer_subject ? 'Yes' : 'No'));
		error_log('Body template exists: ' . ($customer_body ? 'Yes' : 'No'));
	}

	wp_send_json_success( ['pageUrl' => $res_body['pageUrl']] );
}

add_action( 'wp_ajax_ih_ajax_create_payment_order', 'ih_ajax_create_payment_order' );
add_action( 'wp_ajax_nopriv_ih_ajax_create_payment_order', 'ih_ajax_create_payment_order' );
/**
 * Create a Payment Order without memory page and user authentication.
 *
 * @return void
 */
function ih_ajax_create_payment_order(): void
{
    $email          = ih_clean( $_POST['email'] );
    $phone          = ih_clean( $_POST['phone'] );
    $city           = ih_clean( $_POST['city'] );
    $department     = ih_clean( $_POST['warehouse'] );
    $firstname      = ih_clean( $_POST['name'] );
    $lastname       = ih_clean( $_POST['surname'] );
    $fathername     = ih_clean( $_POST['lastname'] );
    $qr_count       = (int) ih_clean( $_POST['qr-count-qty'] );

    // Not all fields are filled.
    if(
        ! $email || ! $phone || ! $city || ! $department ||
        ! $firstname || ! $lastname || ! $fathername
    ) wp_send_json_error( ['msg' => __( 'Заповніть всі поля', 'inheart' )] );

    // No QR codes count provided.
    if( ! $qr_count )
        wp_send_json_error( ['msg' => __( 'Не вказана кількість QR кодів', 'inheart' )] );

    if( ! $price = ih_get_expanded_page_order_price( $qr_count ) )
        wp_send_json_error( ['msg' => __( 'Невірні дані товарів', 'inheart' )] );

    if( ! $mono_token = get_field( 'mono_token', 'option' ) )
        wp_send_json_error( ['msg' => __( 'Невірний або відсутній токен', 'inheart' )] );

    // Make a request to MonoBank.
    $dest   = "QR-код на металевій пластині";
    $amount = $price * 100;
    $body   = json_encode([
        'amount'           => $amount,    // UAH kopecks.
        'redirectUrl'      => get_bloginfo('url').'/succesfull-payment',
        'webHookUrl'       => get_bloginfo('url').'/wp-json/mono/acquiring/status',
        'paymentType'      => 'debit',
        'merchantPaymInfo' => [
            'destination' => $dest,
            'comment'     => $dest,
            'basketOrder' => [
                [
                    'name'  => $dest,
                    'qty'   => $qr_count,
                    'sum'   => $amount / $qr_count,
                    'total' => $amount,
                    'unit'  => 'шт.',
                    'code'  => base64_encode($dest.'-'.time())
                ]
            ]
        ]
    ]);
    $res  = wp_remote_post( 'https://api.monobank.ua/api/merchant/invoice/create', [
        'headers'     => [
            'Content-Type' => 'application/json; charset=utf-8',
			'X-Token'      => $mono_token
        ],
        'data_format' => 'body',
        'body'        => $body
    ] );

    if( is_wp_error( $res ) || empty( $res ) )
        wp_send_json_error( ['msg' => __( 'Немає відповіді від банку', 'inheart' )] );

    $res_body = json_decode( $res['body'], true );

    if( empty( $res_body ) )
        wp_send_json_error( ['msg' => __( 'Помилка під час створення запиту до оплати', 'inheart' )] );

    if( ! $invoice_id = $res_body['invoiceId'] ?? null )
        wp_send_json_error( ['msg' => __( 'Відповідь банку не містить ID рахунку', 'inheart' )] );

    // Create new Order.
    $order_data = [
        'post_title'    => "Замовлення від $lastname $firstname $fathername",
        'post_status'   => 'publish',
        'post_type'     => 'expanded-page'
    ];
    $order_id = wp_insert_post( wp_slash( $order_data ) );

    if( is_wp_error( $order_id ) )
        wp_send_json_error( ['msg' => __( 'Не вдалося створити замовлення', 'inheart' )] );

    $ordered = "QR-код на металевій пластині - $qr_count шт. ($qr_count x " . ih_get_metal_qr_price() . " грн)\n" .
        "Загальна вартість: $price грн";

    update_field( 'firstname', $firstname, $order_id );
    update_field( 'lastname', $lastname, $order_id );
    update_field( 'fathername', $fathername, $order_id );
    update_field( 'email', $email, $order_id );
    update_field( 'phone', $phone, $order_id );
    update_field( 'city', $city, $order_id );
    update_field( 'department', $department, $order_id );
    update_field( 'invoice_id', $invoice_id, $order_id );
    update_field( 'status_modified_date', 0, $order_id );
    update_field( 'ordered', $ordered, $order_id );
    update_field( 'status', 'created', $order_id );

    /**
     * Send email to Admin.
     *
     * @see Theme Settings -> Email Templates -> Orders -> Order Created.
     */
    $subject    = get_field( 'order_created_subject_admin', 'option' );
    $body       = get_field( 'order_created_body_admin', 'option' );

    if( $subject && $body ){
        $body = str_replace( ['https://[', 'http://['], '[', $body );
        $body = str_replace( [
            '[firstname]',
            '[lastname]',
            '[fathername]',
            '[invoice_id]',
            '[ordered]',
            '[order_admin_url]'
        ], [
            $firstname,
            $lastname,
            $fathername,
            $invoice_id,
            $ordered,
            get_edit_post_link( $order_id )
        ], $body );

        add_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
        wp_mail( ih_get_order_emails_array(), $subject, $body );
        remove_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
    }


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
	// If payment is success
	if( $new_status === 'success' ) {
		$memory_page_id = get_field( 'memory_page_id', $order_id );
		
		// If there is a memory page connection
		if( $memory_page_id ) {
		update_field( 'is_expanded', 1, $memory_page_id );
		}

		// Create new QR for both types of orders
		$qr_data = [
			'post_type'		=> 'qr',
			'post_title'	=> $memory_page_id 
				? "Замовлено для сторінки  #$memory_page_id (" . get_the_title( $memory_page_id ) . ")"
				: "Замовлено QR-код від " . get_field( 'lastname', $order_id ) . " " . get_field( 'firstname', $order_id ),
			'post_status'	=> 'draft'
		];
		$qr_id = wp_insert_post( wp_slash( $qr_data ) );

		if( is_wp_error( $qr_id ) ) return false;

		wp_update_post( [
			'ID'			=> $qr_id,
			'post_name'		=> $qr_id,
			'post_status'	=> 'publish'
		] );

		// Save data depending on order type
		if( $memory_page_id ) {
		$memory_page_url = get_the_permalink( $memory_page_id );
		update_field( 'memory_page_id', $memory_page_id, $qr_id );
		update_field( 'memory_page_url', $memory_page_url, $qr_id );
		}

		// Common data for both order types
		update_field( 'order_id', $order_id, $qr_id );
		update_field( 'customer_name', get_field( 'firstname', $order_id ) . ' ' . get_field( 'lastname', $order_id ), $qr_id );
		update_field( 'customer_email', get_field( 'email', $order_id ), $qr_id );
		update_field( 'customer_phone', get_field( 'phone', $order_id ), $qr_id );
		update_field( 'delivery_city', get_field( 'city', $order_id ), $qr_id );
		update_field( 'delivery_warehouse', get_field( 'department', $order_id ), $qr_id );

		/**
		 * Send email to Admin.
		 *
		 * @see Theme Settings -> Email Templates -> QR -> QR Created.
		 */
		$subject	= get_field( 'qr_created_subject', 'option' );
		$body		= get_field( 'qr_created_body', 'option' );

		if( $subject && $body ){
			$firstname  = get_field( 'firstname', $order_id );
			$lastname   = get_field( 'lastname', $order_id );
			$fathername = get_field( 'fathername', $order_id );
			$email      = get_field( 'email', $order_id );
			$phone      = get_field( 'phone', $order_id );
			$city       = get_field( 'city', $order_id );
			$department = get_field( 'department', $order_id );
			$body       = str_replace( ['https://[', 'http://['], '[', $body );
			$body       = str_replace( [
				'[qr_id]',
				'[qr_admin_url]',
				'[mp_id]',
				'[mp_admin_url]',
				'[mp_url]',
				'[firstname]',
				'[lastname]',
				'[fathername]',
				'[email]',
				'[phone]',
				'[city]',
				'[department]'
			], [
				$qr_id,
				get_edit_post_link( $qr_id ),
				$memory_page_id ?: '—',
				$memory_page_id ? get_edit_post_link( $memory_page_id ) : '—',
				$memory_page_id ? get_the_permalink( $memory_page_id ) : '—',
				$firstname,
				$lastname,
				$fathername,
				$email,
				$phone,
				$city,
				$department
			], $body );

			add_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
			wp_mail( ih_get_order_emails_array(), $subject, $body );
			remove_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
		}
	}

	return true;
}












// Add new REST API endpoint for Mono Checkout
add_action('rest_api_init', 'ih_rest_api_init_mono_checkout');
function ih_rest_api_init_mono_checkout(): void {
    register_rest_route('mono/checkout', '/status', [
        'methods' => 'POST',
        'permission_callback' => '__return_true',
        'callback' => 'ih_mono_checkout_handle_status'
    ]);
}

/**
 * Handle Mono Checkout webhook callback
 * 
 * @param WP_REST_Request $request
 * @return WP_REST_Response
 */
function ih_mono_checkout_handle_status(WP_REST_Request $request): WP_REST_Response {
    date_default_timezone_set('UTC');
    $current_date = date('d.m.Y H:i:s');

    // Log the entire request
    file_put_contents(ABSPATH . '/mono-checkout.log', 
        $current_date . ": Callback Request Data:\n" . 
        "Method: " . $request->get_method() . "\n" .
        "Headers: " . print_r($request->get_headers(), true) . "\n" .
        "Body: " . $request->get_body() . "\n" .
        "Params: " . print_r($request->get_params(), true) . "\n" .
        "JSON: " . print_r($request->get_json_params(), true) . "\n" .
        "----------------------------------------\n", 
        FILE_APPEND
    );

    if (!$req_body = $request->get_body() ?? null) {
        file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: No request body provided." . PHP_EOL, FILE_APPEND);
        return new WP_REST_Response(['status' => 'error', 'message' => 'No request body'], 400);
    }

    $data = json_decode($req_body, true);
    
    // Check for required fields in the actual format
    if (!isset($data['orderId']) || !isset($data['generalStatus'])) {
        file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: Invalid request format. Data: " . print_r($data, true) . PHP_EOL, FILE_APPEND);
        return new WP_REST_Response(['status' => 'error', 'message' => 'Invalid request format'], 400);
    }

    // Log incoming data
    file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: Processing order {$data['orderId']} with status {$data['generalStatus']}" . PHP_EOL, FILE_APPEND);

    // Process the order with the actual data structure
    $order_processed = ih_process_mono_checkout_order($data);
    
    if (!$order_processed) {
        file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: Failed to process order {$data['orderId']}" . PHP_EOL, FILE_APPEND);
        return new WP_REST_Response(['status' => 'error', 'message' => 'Failed to process order'], 500);
    }

    return new WP_REST_Response(['status' => 'success'], 200);
}

/**
 * Process Mono Checkout order data and create/update order
 * 
 * @param array $order_data
 * @return bool
 */
function ih_process_mono_checkout_order(array $order_data): bool {
    // Check if order already exists
    $existing_order = get_posts([
        'post_type' => 'expanded-page',
        'numberposts' => 1,
        'meta_query' => [
            [
                'key' => 'invoice_id',
                'value' => $order_data['orderId']
            ]
        ]
    ]);

    $order_id = $order_data['orderId'];

    // Prepare order data
    $firstname = $order_data['mainClientInfo']['first_name'];
    $lastname = $order_data['mainClientInfo']['last_name'];
	$fathername = '';
    $email = $order_data['mainClientInfo']['email'];
    $phone = $order_data['mainClientInfo']['phoneNumber'];
    $city = $order_data['deliveryAddressInfo']['cityName'];
    $department = $order_data['delivery_branch_address'];
    $amount = $order_data['amount']; // Convert from kopecks to UAH
    $quantity = $order_data['quantity'];
    $status = $order_data['generalStatus'];

    // If order doesn't exist, create new one
    if (!$order_id) {
        $order_data = [
            'post_title' => "Замовлення від $lastname $firstname",
            'post_status' => 'publish',
            'post_type' => 'expanded-page'
        ];
        
        $order_id = wp_insert_post(wp_slash($order_data));
        
        if (is_wp_error($order_id)) {
            return false;
        }
		
		update_field('invoice_id', $order_id, $order_id);
		update_field('status', 'created', $order_id);
		update_field('qr-count-qty', 1, $order_id);
    }

	update_field( 'firstname', $firstname, $order_id );
    update_field( 'lastname', $lastname, $order_id );
    update_field( 'fathername', $fathername, $order_id );
    update_field( 'email', $email, $order_id );
    update_field( 'phone', $phone, $order_id );
    update_field( 'city', $city, $order_id );
    update_field( 'department', $department, $order_id );
    update_field( 'invoice_id', $order_id, $order_id );
    update_field('status', $status, $order_id);
    
    // Format ordered items description
    $ordered = "QR-код на металевій пластині - $quantity шт. ($quantity x " . ih_get_metal_qr_price() . " грн)\n" .
        "Загальна вартість: $amount грн";
    update_field('ordered', $ordered, $order_id);

    // If payment is successful, create QR code
    if ($status === 'success') {
        $qr_data = [
            'post_type' => 'qr',
            'post_title' => "Замовлено QR-код від $lastname $firstname",
            'post_status' => 'draft'
        ];
        
        $qr_id = wp_insert_post(wp_slash($qr_data));

        
        if (!is_wp_error($qr_id)) {
            wp_update_post([
                'ID' => $qr_id,
                'post_name' => $qr_id,
                'post_status' => 'publish'
            ]);

            // Save QR code data
            update_field('order_id', $order_id, $qr_id);
            update_field('customer_name', "$firstname $lastname", $qr_id);
            update_field('customer_email', $email, $qr_id);
            update_field('customer_phone', $phone, $qr_id);
            update_field('delivery_city', $city, $qr_id);
            update_field('delivery_warehouse', $department, $qr_id);


		/**
		 * Send email to Admin.
		 *
		 * @see Theme Settings -> Email Templates -> Orders -> Order Created.
		 */
		$subject    = get_field( 'order_created_subject_admin', 'option' );
		$body       = get_field( 'order_created_body_admin', 'option' );

		if( $subject && $body ){
			$body = str_replace( ['https://[', 'http://['], '[', $body );
			$body = str_replace( [
				'[firstname]',
				'[lastname]',
				'[fathername]',
				'[invoice_id]',
				'[ordered]',
				'[order_admin_url]'
			], [
				$firstname,
				$lastname,
				$fathername,
				$invoice_id,
				$ordered,
				get_edit_post_link( $order_id )
			], $body );

			add_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
			wp_mail( ih_get_order_emails_array(), $subject, $body );
			remove_filter( 'wp_mail_content_type', 'ih_set_html_content_type' );
		}
        }
    }

    // Send order status email
    ih_send_email_on_status_change($status, $order_id);

    return true;
}

add_action('wp_ajax_ih_ajax_create_mono_payment', 'ih_ajax_create_mono_payment');
add_action('wp_ajax_nopriv_ih_ajax_create_mono_payment', 'ih_ajax_create_mono_payment');
/**
 * Create a payment through Mono Checkout
 * 
 * @return void
 */
function ih_ajax_create_mono_payment(): void {
    date_default_timezone_set('UTC');
    $current_date = date('d.m.Y H:i:s');

    $qr_count = (int)ih_clean($_POST['qr-count-qty']);

    // Log incoming request
    file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: Incoming request - Email: $email, Phone: $phone, QR Count: $qr_count\n", FILE_APPEND);

    // Validate required fields
    if (!$qr_count) {
        wp_send_json_error(['msg' => __('Заповніть всі поля та прийміть умови', 'inheart')]);
    }

    // Calculate price
    if (!$price = ih_get_expanded_page_order_price($qr_count)) {
        wp_send_json_error(['msg' => __('Невірні дані товарів', 'inheart')]);
    }

    // Get Mono token from existing settings
    if (!$mono_token = get_field('mono_token', 'option')) {
        wp_send_json_error(['msg' => __('Невірний або відсутній токен', 'inheart')]);
    }

    // Generate unique order reference
    $order_ref = substr(md5(uniqid()), 0, 12);

    // Prepare request to Mono Checkout
    $request_data = [
        'order_ref' => $order_ref,
        'amount' => 10, // Сумма в гривнах $price
        'ccy' => 980, // UAH
        'count' => $qr_count,
        'products' => [
            [
                'name' => 'QR-код на металевій пластині',
                'product_img_src' => 'https://monobank.ua',
                'cnt' => $qr_count,
                'price' => 10,
                'code_product' => 1,
                'code_checkbox' => '',
                'uktzed' => '',
                'tax' => ''
            ]
        ],
        'dlv_method_list' => [
            'np_brnm',
            'np_box'
        ],
        'payment_method_list' => [
            'card'
        ],
        'dlv_pay_merchant' => false,
        'payments_number' => 3,
        'callback_url' => get_bloginfo('url') . '/wp-json/mono/checkout/status',
        'return_url' => get_bloginfo('url') . '/succesfull-payment',
        'fl_recall' => 'true',
        'acceptable_age' => '',
        'hold' => 'false',
        'destination' => 'QR-код на металевій пластині',
    ];

    // Remove empty values to avoid validation errors
    $request_data = array_filter($request_data, function($value) {
        if (is_array($value)) {
            return !empty($value);
        }
        return $value !== '' && $value !== null;
    });

    // Remove empty values from nested arrays
    if (isset($request_data['dlv_info_merchant'])) {
        $request_data['dlv_info_merchant'] = array_filter($request_data['dlv_info_merchant'], function($value) {
            return $value !== '' && $value !== null;
        });
    }

    $body = json_encode($request_data, JSON_UNESCAPED_UNICODE);

    // Log request data
    file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: Request to Mono API:\n" . print_r($request_data, true) . "\n", FILE_APPEND);

    // Make request to Mono Checkout API
    $res = wp_remote_post('https://api.monobank.ua/personal/checkout/order', [
        'headers' => [
            'Content-Type' => 'application/json; charset=utf-8',
            'X-Token' => $mono_token
        ],
        'data_format' => 'body',
        'body' => $body,
        'timeout' => 30
    ]);

    // Log response with more details
    if (is_wp_error($res)) {
        file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: WP Error: " . $res->get_error_message() . "\n", FILE_APPEND);
        wp_send_json_error(['msg' => __('Немає відповіді від банку', 'inheart')]);
    }

    // Log response status and headers
    $response_code = wp_remote_retrieve_response_code($res);
    $response_message = wp_remote_retrieve_response_message($res);
    $response_headers = wp_remote_retrieve_headers($res);
    
    file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: Response Code: $response_code\n", FILE_APPEND);
    file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: Response Message: $response_message\n", FILE_APPEND);
    file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: Response Headers:\n" . print_r($response_headers, true) . "\n", FILE_APPEND);

    if (empty($res)) {
        file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: Empty response from Mono API\n", FILE_APPEND);
        wp_send_json_error(['msg' => __('Немає відповіді від банку', 'inheart')]);
    }

    $res_body = json_decode($res['body'], true);
    file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: Raw Response Body:\n" . $res['body'] . "\n", FILE_APPEND);
    file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: Decoded Response Body:\n" . print_r($res_body, true) . "\n", FILE_APPEND);

    // Check for specific error responses
    if ($response_code !== 200) {
        $error_message = isset($res_body['errorDescription']) ? $res_body['errorDescription'] : __('Помилка під час створення запиту до оплати', 'inheart');
        file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: API Error: $error_message\n", FILE_APPEND);
        wp_send_json_error(['msg' => $error_message]);
    }

    if (empty($res_body)) {
        file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: Empty response body\n", FILE_APPEND);
        wp_send_json_error(['msg' => __('Помилка під час створення запиту до оплати', 'inheart')]);
    }

    // Check for result in response
    if (!isset($res_body['result'])) {
        file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: No result in response\n", FILE_APPEND);
        wp_send_json_error(['msg' => __('Невірний формат відповіді від банку', 'inheart')]);
    }

    $result = $res_body['result'];

    // Check for required fields in result
    if (!isset($result['order_id']) || !isset($result['redirect_url'])) {
        file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: Missing required fields in result\n", FILE_APPEND);
        wp_send_json_error(['msg' => __('Відповідь банку не містить необхідних даних', 'inheart')]);
    }

    // Create temporary order to track payment
    $order_data = [
        'post_title' => "Замовлення через Mono Checkout",
        'post_status' => 'publish',
        'post_type' => 'expanded-page'
    ];
    
    $order_id = wp_insert_post(wp_slash($order_data));
    
    if (is_wp_error($order_id)) {
        file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: Failed to create order: " . $order_id->get_error_message() . "\n", FILE_APPEND);
        wp_send_json_error(['msg' => __('Не вдалося створити замовлення', 'inheart')]);
    }

    // Save initial order data
    update_field('invoice_id', $result['order_id'], $order_id);
    update_field('status', 'created', $order_id);
    update_field('qr-count-qty', $qr_count, $order_id);

    file_put_contents(ABSPATH . '/mono-checkout.log', "$current_date: Order created successfully, ID: $order_id\n", FILE_APPEND);

    // Send success response with redirect URL
    wp_send_json_success([
        'checkoutUrl' => $result['redirect_url']
    ]);
}


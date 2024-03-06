<?php

/**
 * Theme custom functions.
 * Please place all your custom functions declarations inside this file.
 *
 * @package WordPress
 * @subpackage inheart
 */

add_action( 'init', 'ih_register_session' );
/**
 * Enable sessions.
 *
 * @return void
 */
function ih_register_session(): void
{
	if( ! session_id() ) session_start();
}

/**
 * Clean incoming value from trash.
 *
 * @param	mixed	$value	Some value to clean.
 * @return	string
 */
function ih_clean( $value ): string
{
	$value	= wp_unslash( $value );
	$value	= trim( $value );
	$value	= stripslashes( $value );
	$value	= strip_tags( $value );

	return htmlspecialchars( $value );
}

add_filter( 'wp_nav_menu_objects', 'ih_add_acf_data_to_nav_menu', 10, 2 );
/**
 * Add ACF fields' data to WP nav menus.
 *
 * @param $items
 * @param $args
 * @return array
 */
function ih_add_acf_data_to_nav_menu( $items, $args ): array
{
	foreach( $items as &$item ) {
		if( ! $icon = get_field( 'icon', $item ) ?: null ) continue;

		$item->title = wp_get_attachment_image( $icon, 'ih-icon', false, ['class' => 'style-svg'] ) . $item->title;
	}

	return $items;
}

/**
 * Send new User creation error.
 *
 * @param WP_Error $new_user_id
 * @return void
 */
function ih_check_user_creation_errors( WP_Error $new_user_id ): void
{
	switch( $new_user_id->get_error_code() ){
		case 'existing_user_email':
			wp_send_json_error(
				['errors' => [ ['field' => 'email'] ], 'msg' => esc_html__( 'Ця пошта вже використовується', 'inheart' )] );
			break;

		case 'existing_user_login':
			wp_send_json_error( ['errors' => [ ['field' => 'email'] ], 'msg' => esc_html__( 'Цей логін вже використовується', 'inheart' )] );
			break;

		case 'empty_user_login':
			wp_send_json_error( ['errors' => [ ['field' => 'email'] ], 'msg' => esc_html__( 'Невірні символи у логіні', 'inheart' )] );
			break;

		default:
			wp_send_json_error( ['msg' => $new_user_id->get_error_message()] );
	}
}

/**
 * Function checks if value length is between min and max parameters.
 *
 * @param   string	$value	Any value.
 * @param   int     $min  	Minimum symbols value length.
 * @param   int     $max  	Maximum symbols value length.
 * @return  bool          	True if OK, false if value length is too small or large.
 */
function ih_check_length( string $value, int $min, int $max ): bool
{
	return ! ( mb_strlen( $value ) < $min || mb_strlen( $value ) > $max );
}

/**
 * Check if name has incorrect symbols.
 *
 * @param string $name
 * @return bool
 */
function ih_check_name( string $name ): bool
{
	return preg_match( '/^[a-zа-яіїє]+[a-zа-яіїє \-\'`]+[a-zа-яіїє]$/iu', $name );
}

/**
 * Email filters.
 */
add_filter( 'wp_mail_from_name', function( $from_name ){ return 'Inheart'; } );
add_filter( 'wp_mail_from', function( $email_address ){ return 'no-reply@' . $_SERVER['SERVER_NAME']; } );
/**
 * Function sets HTML content type in E-mails.
 *
 * @return string
 */
function ih_set_html_content_type(): string{ return 'text/html'; }

/**
 * Helps to get template part into a variable.
 *
 * @param string		$template_name	Template file name.
 * @param string|null	$part_name		Template part file name.
 * @param array			$args			Arguments to pass.
 * @return string						HTML content from template.
 */
function ih_load_template_part( string $template_name, string $part_name = null, array $args = [] ): string
{
	ob_start();
	get_template_part( $template_name, $part_name, $args );
	$tp = ob_get_contents();
	ob_end_clean();

	return $tp;
}

/**
 * Check if account activation link is expired.
 *
 * @param int $user_id
 * @return bool|null    true - already expired, false - still could be used.
 */
function ih_is_activation_link_expired( int $user_id ): ?bool
{
	if( ! $user_id ) return null;

	date_default_timezone_set( 'UTC' );
	$lifetime   = get_field( 'registration_link_lifetime', 'option' ) ?? 5;
	$old_date   = ( int ) get_user_meta( $user_id, 'registration_date', true );
	$new_date   = time();

	return ( $new_date - $old_date > $lifetime * 60 );
}

/**
 * Delete folder with contents.
 *
 * @param string $path
 * @return bool
 */
function ih_delete_folder( string $path ): bool
{
	$files = array_diff( scandir( $path ), ['.', '..'] );

	foreach( $files as $file )
		( is_dir( "$path/$file" ) ) ? ih_delete_folder( "$path/$file" ) : unlink( "$path/$file" );

	return rmdir( $path );
}

/**
 * Replace symbols in filename.
 *
 * @param string $filename
 * @return string
 */
function ih_modify_filename( string $filename = '' ): string
{
	return str_replace( ' ', '_', $filename );
}

/**
 * Return shorter filename with extension.
 *
 * @param string $filename
 * @return string
 */
function ih_get_shorter_filename( string $filename ): string
{
	$ext	= pathinfo( $filename, PATHINFO_EXTENSION );
	$name	= substr( $filename, 0, strlen( $filename ) - strlen( $ext ) - 1 );

	return ( strlen( $filename ) - strlen( $ext ) > 17 ) ? substr( $name, 0, 10 ) . '...' . $ext : $filename;
}

/**
 * Turn seconds into pretty duration string.
 *
 * @param int $seconds
 * @return string
 */
function ih_prettify_duration( int $seconds ): string
{
	if( $seconds < 10 ) $seconds = "0$seconds";

	if( $seconds < 60 ) return "00:00:$seconds";

	$hours		= floor( $seconds / ( 60 * 60 ) );
	$hours		= $hours < 10 ? "0$hours" : $hours;
	$minutes	= floor( ( $seconds / 60 ) % 60 );
	$minutes	= $minutes < 10 ? "0$minutes" : $minutes;
	$seconds	= floor( $seconds % 60 );
	$seconds	= $seconds < 10 ? "0$seconds" : $seconds;

	return "$hours:$minutes:$seconds";
}

/**
 * Delete all attachments of deleted Memory page.
 */
add_action( 'before_delete_post', function( $id ){
	if( get_post_type( $id ) !== 'memory_page' ) return;

	$attachments = get_attached_media( '', $id );

	if( isset( $_SESSION['memory_page_id'] ) && $_SESSION['memory_page_id'] === $id )
		unset( $_SESSION['memory_page_id'] );

	foreach( $attachments as $attachment ) wp_delete_attachment( $attachment->ID, 'true' );
} );

/**
 * Convert input[type="date"] value to necessary format.
 *
 * @param string $date
 * @param string $format	'letters' | 'dots' | 'lang'
 * @param int $memory_page	Memory page ID.
 * @return string
 */
function ih_convert_input_date( string $date, string $format = 'letters', int $memory_page = 0 ): string
{
	if( ! $date ) return '';

	switch( $format ){
		case 'letters':
			return date( 'jS M Y', strtotime( str_replace( '/', '-', $date ) ) );

		case 'lang':
			$lang		= get_field( 'language', $memory_page );
			$ua_months	= ['січня', 'лютого', 'березня', 'квітня', 'травня', 'червня', 'липня', 'серпня', 'вересня', 'жовтня', 'листопада', 'грудня'];
			$ru_months	= ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
			$en_months	= ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

			if( $lang === 'uk' ){
				$date = date( 'j F Y', strtotime( str_replace( '/', '-', $date ) ) );
				return str_replace( $en_months, $ua_months, $date );
			}else if( $lang === 'ru-RU' ){
				$date = date( 'j F Y', strtotime( str_replace( '/', '-', $date ) ) );
				return str_replace( $en_months, $ru_months, $date );
			}else{
				return date( 'jS M Y', strtotime( str_replace( '/', '-', $date ) ) );
			}

		default:
			return date( 'd.m.Y', strtotime( str_replace( '/', '-', $date ) ) );
	}
}

/**
 * Prepare ACF Date Picker field value for frontend input[type="date"].
 *
 * @param $date
 * @return string
 */
function ih_convert_date_from_admin_for_input( $date ): string
{
	if( ! $date ) return '';

	$date_arr = array_reverse( explode( '/', $date ) );

	return implode( '-', $date_arr );
}

/**
 * Get person lifetime numbers.
 *
 * @param int $id
 * @return array
 */
function ih_get_lifetime_numbers( int $id = 0 ): array
{
	if( ! $id ) return ['years' => 0, 'months' => 0, 'weeks' => 0, 'days' => 0, 'hours' => 0];

	$born_at	= ih_convert_input_date( get_field( 'born_at', $id ) );
	$died_at	= ih_convert_input_date( get_field( 'died_at', $id ) );
	$diff		= abs( strtotime( $died_at ) - strtotime( $born_at ) );

	return [
		'born'	=> $born_at,
		'died'	=> $died_at,
		'years'	=> floor( $diff /  (365 * 60 * 60 * 24 ) ),
		'months'=> floor( $diff / ( 30 * 60 * 60 * 24 ) ),
		'weeks'	=> floor( $diff / ( 60 * 60 * 24 * 7 ) ),
		'days'	=> floor( $diff / ( 60 * 60 * 24 ) ),
		'hours'	=> floor( $diff / ( 60 * 60 ) )
	];
}

/**
 * Return Memory page full name.
 *
 * @param int $page_id
 * @return string
 */
function ih_get_memory_page_name( int $page_id = 0 ): string
{
	if( ! $page_id ) return '';

	$first_name		= get_field( 'first_name', $page_id );
	$middle_name	= get_field( 'middle_name', $page_id );
	$last_name		= get_field( 'last_name', $page_id );

	return "<div class='memory-page-name'><div>$first_name $middle_name</div><div>$last_name</div></div>";
}

/**
 * Return social icons layout.
 *
 * @param string $class
 * @return string
 */
function ih_get_socials( string $class = '' ): string
{
	if( ! $socials = get_field( 'social_icons', 'option' ) ?: null ) return '';

	$res = '<div class="flex align-center justify-center icons-list ' . esc_attr( $class ) . '">';

	foreach( $socials as $soc ){
		$icon	= $soc['icon'];
		$url	= $soc['url'];

		if( ! $icon || ! $url ) continue;

		$res .= '<a href="' . esc_url( $url ) . '" class="flex justify-center align-center" target="_blank">'
			. wp_get_attachment_image( $icon['id'], 'icon', false, ['loading' => 'lazy', 'class' => 'style-svg'] ) .
		'</a>';
	}

	return "$res</div>";
}

/**
 * Returns the expanded memory page price.
 *
 * @return int
 */
function ih_get_expanded_page_price(): int
{
	if( ! $expanded_id = get_field( 'expanded_memory_page', 'option' ) ) return 0;

	return get_field( 'price', $expanded_id );
}

/**
 * Returns the metal QR-code price.
 *
 * @return int
 */
function ih_get_metal_qr_price(): int
{
	if( ! $qr_id = get_field( 'qr_code_metal', 'option' ) ) return 0;

	return get_field( 'price', $qr_id );
}

/**
 * Returns the total price of the expanded memory page and metal QRs.
 *
 * @param int $qr_count	Count of metal QRs
 * @return int
 */
function ih_get_expanded_page_order_price( int $qr_count = 1 ): int
{
	return ih_get_expanded_page_price() + ( ih_get_metal_qr_price() * $qr_count );
}

/**
 * Below - helpers to get theme pages IDs.
 */
function ih_get_login_page_id(): int
{
	return get_field( 'login_page_id', 'option' ) ?: 0;
}

function ih_get_registration_page_id(): int
{
	return get_field( 'registration_page_id', 'option' ) ?: 0;
}

function ih_get_activation_page_id(): int
{
	return get_field( 'activation_page_id', 'option' ) ?: 0;
}

function ih_get_forgot_pass_page_id(): int
{
	return get_field( 'forgot_pass_page_id', 'option' ) ?: 0;
}

function ih_get_profile_page_id(): int
{
	return get_field( 'profile_page_id', 'option' ) ?: 0;
}

function ih_get_profile_memories_page_id(): int
{
	return get_field( 'profile_memories_page_id', 'option' ) ?: 0;
}

function ih_get_memory_creation_page_id(): int
{
	return get_field( 'memory_creation_page_id', 'option' ) ?: 0;
}

function ih_get_order_created_page_id(): int
{
	return get_field( 'order_created_page_id', 'option' ) ?: 0;
}

/**
 * Check if military section fields are set.
 *
 * @param array $section	Group field from some memory page.
 * @return bool				True if data is set, false if not.
 */
function ih_is_set_military_section( array $section = [] ): bool
{
	return isset( $section['text'] ) && $section['text'];
}

/**
 * Check if last fight fields are set.
 *
 * @param array $last_fight	Group field from some memory page.
 * @return bool				True if data is set, false if not.
 */
function ih_is_set_last_fight( array $last_fight = [] ): bool
{
	return isset( $last_fight['location'] ) && $last_fight['location'] &&
		isset( $last_fight['text'] ) && $last_fight['text'];
}


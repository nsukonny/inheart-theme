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


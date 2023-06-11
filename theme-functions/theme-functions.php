<?php

/**
 * Theme custom functions.
 * Please place all your custom functions declarations inside this file.
 *
 * @package WordPress
 * @subpackage inheart
 */

/**
 * Clean incoming value from trash.
 *
 * @param	mixed	$value	Some value to clean.
 * @return	string
 */
function ih_clean( mixed $value ): string
{
	$value	= wp_unslash( $value );
	$value	= trim( $value );
	$value	= stripslashes( $value );
	$value	= strip_tags( $value );

	return htmlspecialchars( $value );
}

/**
 * Check data for specific errors and add them to an array.
 *
 * @param array  $errors
 * @param string $field_data
 * @param string $field
 * @param string $error_msg
 * @param bool   $specific_check
 * @return array
 */
function ih_collect_errors( array &$errors, string $field_data, string $field, string $error_msg, bool $specific_check = true ): array
{
	if( ! $field_data )
		$errors[] = [
			'field'	=> $field,
			'error'	=> sprintf( esc_html__( '%s', 'inheart' ), $error_msg )
		];

	if( ! $specific_check )
		$errors[] = [
			'field'	=> $field,
			'error'	=> sprintf( esc_html__( '%s', 'inheart' ), $error_msg )
		];

	return $errors;
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
			wp_send_json_error( [
				'errors'	=> [ [
					'field'	=> 'form-register-email',
					'error'	=> esc_html__( 'User with this E-mail already exists', 'inheart' )
				] ]
			] );
			break;

		case 'existing_user_login':
			wp_send_json_error( [
				'errors'	=> [ [
					'field'	=> 'form-register-email',
					'error'	=> esc_html__( 'User with this login already exists', 'inheart' )
				] ]
			] );
			break;

		case 'empty_user_login':
			wp_send_json_error( [
				'errors'	=> [ [
					'field'	=> 'form-register-email',
					'error'	=> esc_html__( 'Please enter only latin letters for login', 'inheart' )
				] ]
			] );
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
 * Function checks phone symbols.
 *
 * @param   string  $phone  Some phone number.
 * @return  bool            True if OK, false if string has bad symbols.
 */
function ih_check_phone( string $phone ): bool
{
	return preg_match('/^[0-9()+\-\s]+$/iu', $phone );
}

/**
 * Email filters.
 */
add_filter( 'wp_mail_from_name', function( $from_name ){ return 'Inheart'; } );
add_filter( 'wp_mail_from', function( $email_address ){ return 'no-reply@inheart.memorial'; } );
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
 * Generate form field.
 *
 * @param array $args	name, class, value, label, type, label_class, choices, data_value.
 * @return null|string
 */
function ih_generate_form_field( array $args ): ?string
{
	if( empty( $args ) || ! $args['name'] || ! $args['label'] ) return null;

	$settings = [
		'class'			=> 'input-dark',
		'type'			=> 'text',
		'label_class'	=> 'label-animated',
		'choices'		=> [],
		'value'			=> '',
		'data_value'	=> ''
	];
	$settings = array_merge( $settings, $args );

	switch( $settings['type'] ){
		case 'checkbox':
			return '<input
				id="' . esc_attr( $settings['name'] ) . '"
				name="' . esc_attr( $settings['name'] ) . '"
				class="' . esc_attr( $settings['class'] ) . '"
				type="' . esc_attr( $settings['type'] ) . '"
			/>
			<label for="' . esc_attr( $settings['name'] ) . '" class="' . esc_attr( $settings['label_class'] ) . '">'
				. sprintf( esc_html__( '%s', 'inheart' ), $settings['label'] ) .
				'</label>';

		case 'select':
			$options = '';

			if( ! empty( $settings['choices'] ) ){
				foreach( $settings['choices'] as $key => $choice ){
					$selected = $choice === $settings['value'] ? ' data-selected="1"' : '';
					$options .= '<span data-value="' . esc_attr( $key ) . '"' . $selected . '>' . esc_html( $choice ) . '</span>';
				}
			}

			return '<label class="' . esc_attr( $settings['label_class'] ) . '" data-for="' . esc_attr( $settings['name'] ) . '">
				<input
					type="text"
					id="' . esc_attr( $settings['name'] ) . '"
					name="' . esc_attr( $settings['name'] ) . '"
					class="' . esc_attr( $settings['class'] ) . '"
					value="' . esc_attr( $settings['value'] ) . '"
					data-value="' . esc_attr( $settings['data_value'] ?: $settings['value'] ) . '"
					disabled
				/>
				<span class="label-text">'
				. sprintf( esc_html__( '%s', 'inheart' ), $settings['label'] ) .
				'</span>
				<span class="select-options">
					<span data-value="">' . sprintf( esc_html__( '%s', 'inheart' ), $settings['label'] ) . '</span>'
				. $options .
				'</span>
				<svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M1 1L5 5L9 1" stroke="#CBCBCB"/>
				</svg>
				<span class="field-error hidden"></span>
			</label>';

		case 'date':
			return '<label for="' . esc_attr( $settings['name'] ) . '" class="' . esc_attr( $settings['label_class'] ) . '">
				<input
					type="' . esc_attr( $settings['type'] ) . '"
					id="' . esc_attr( $settings['name'] ) . '"
					name="' . esc_attr( $settings['name'] ) . '"
					class="' . esc_attr( $settings['class'] ) . '"
					min="' . date( 'Y-m-d' ) . '"
					value="' . esc_attr( $settings['value'] ) . '"
				/>
				<span class="label-text">'
				. sprintf( esc_html__( '%s', 'inheart' ), $settings['label'] ) .
				'</span>
				<span class="field-error hidden"></span>
			</label>';

		default:
			return '<label for="' . esc_attr( $settings['name'] ) . '" class="' . esc_attr( $settings['label_class'] ) . '">
				<input
					type="' . esc_attr( $settings['type'] ) . '"
					id="' . esc_attr( $settings['name'] ) . '"
					name="' . esc_attr( $settings['name'] ) . '"
					class="' . esc_attr( $settings['class'] ) . '"
					value="' . esc_attr( $settings['value'] ) . '"
				/>
				<span class="label-text">' . sprintf( esc_html__( '%s', 'inheart' ), $settings['label'] ) . '</span>
				<span class="field-error hidden"></span>
			</label>';
	}
}


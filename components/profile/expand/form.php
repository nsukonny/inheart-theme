<?php

/**
 * Profile expand memory page to full.
 * Form part.
 *
 * @package WordPress
 * @subpackage inheart
 */

$user_id	= get_current_user_id();
$meta		= get_user_meta( $user_id );
$data		= get_userdata( $user_id );
$first_name	= $meta['first_name'][0] ?? '';
$last_name	= $meta['last_name'][0] ?? '';
$fathername	= get_field( 'fathername', "user_{$user_id}" ) ?: '';
$phone		= get_field( 'phone', "user_{$user_id}" ) ?: '';
$email		= $data->user_email;
?>

<form class="expand-page-form">
	<fieldset class="flex flex-wrap">
		<legend><?php _e( 'Ваші контактні дані для доставки', 'inheart' ) ?></legend>

		<?php
		get_template_part( 'components/inputs/default', null, [
			'name'			=> 'email',
			'label'			=> __( 'Електронна пошта', 'inheart' ),
			'label_class'	=> 'full',
			'type'			=> 'email',
			'placeholder'	=> __( 'Ваша електронна пошта', 'inheart' ),
			'value'			=> $email,
			'autocomplete'	=> 'email',
			'required'		=> 1
		] );
		get_template_part( 'components/inputs/default', null, [
			'name'			=> 'phone',
			'label'			=> __( 'Номер телефону', 'inheart' ),
			'label_class'	=> 'full',
			'placeholder'	=> __( 'Ваш телефон', 'inheart' ),
			'value'			=> $phone,
			'autocomplete'	=> 'phone',
			'required'		=> 1
		] );
		?>
	</fieldset>

	<fieldset class="flex flex-wrap">
		<legend><?php _e( 'Отримати у відділенні Нової пошти', 'inheart' ) ?></legend>

		<?php
		get_template_part( 'components/inputs/default', null, [
			'name'			=> 'firstname',
			'label'			=> __( "Ваше ім'я", 'inheart' ),
			'label_class'	=> 'full',
			'placeholder'	=> __( "Ваше ім'я", 'inheart' ),
			'value'			=> $first_name,
			'autocomplete'	=> 'given-name',
			'required'		=> 1
		] );
		get_template_part( 'components/inputs/default', null, [
			'name'			=> 'lastname',
			'label'			=> __( 'Ваше прізвище', 'inheart' ),
			'label_class'	=> 'full',
			'placeholder'	=> __( 'Ваше прізвище', 'inheart' ),
			'value'			=> $last_name,
			'autocomplete'	=> 'family-name',
			'required'		=> 1
		] );
		get_template_part( 'components/inputs/default', null, [
			'name'			=> 'fathername',
			'label'			=> __( 'По батькові', 'inheart' ),
			'label_class'	=> 'full',
			'placeholder'	=> __( 'По батькові', 'inheart' ),
			'value'			=> $fathername,
			'autocomplete'	=> 'additional-name',
			'required'		=> 1
		] );
		?>
	</fieldset>
</form><!-- .profile-settings-form -->


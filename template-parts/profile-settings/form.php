<?php

/**
 * Profile Settings page template.
 * Form part.
 *
 * @package WordPress
 * @subpackage inheart
 */

$author_id	= get_current_user_id();
$meta		= get_user_meta( $author_id );
$data		= get_userdata( $author_id );
$first_name	= $meta['first_name'][0] ?? '';
$last_name	= $meta['last_name'][0] ?? '';
$email		= $data->user_email;
?>

<form class="profile-settings-form">
	<fieldset class="flex flex-wrap">
		<legend><?php esc_html_e( 'Головна інформація', 'inheart' ) ?></legend>

		<?php
		get_template_part( 'components/inputs/default', null, [
			'name'			=> 'lastname',
			'label'			=> __( 'Ваше прізвище', 'inheart' ),
			'placeholder'	=> __( 'Ваше прізвище', 'inheart' ),
			'value'			=> $last_name,
			'autocomplete'	=> 'family-name',
			'required'		=> 1
		] );
		get_template_part( 'components/inputs/default', null, [
			'name'			=> 'firstname',
			'label'			=> __( "Ваше ім'я", 'inheart' ),
			'label_class'	=> 'half end',
			'placeholder'	=> __( "Ваше ім'я", 'inheart' ),
			'value'			=> $first_name,
			'autocomplete'	=> 'given-name',
			'required'		=> 1
		] );
		get_template_part( 'components/inputs/default', null, [
			'name'			=> 'email',
			'label'			=> __( 'Email', 'inheart' ),
			'type'			=> 'email',
			'placeholder'	=> __( 'Email', 'inheart' ),
			'value'			=> $email,
			'autocomplete'	=> 'email',
			'required'		=> 1
		] );
		?>
	</fieldset>

	<fieldset class="flex flex-wrap">
		<legend><?php esc_html_e( 'Пароль', 'inheart' ) ?></legend>

		<?php
		get_template_part( 'components/inputs/password', null, [
			'name'			=> 'pass',
			'label'			=> __( 'Поточний пароль', 'inheart' ),
			'placeholder'	=> __( 'Поточний пароль', 'inheart' )
		] );
		?>
		<div style="width: 100%; height: 0"></div>
		<?php
		get_template_part( 'components/inputs/password', null, [
			'name'			=> 'new-pass',
			'label'			=> __( 'Новий пароль', 'inheart' ),
			'placeholder'	=> __( 'Новий пароль', 'inheart' ),
			'autocomplete'	=> 'new-password'
		] );
		get_template_part( 'components/inputs/password', null, [
			'name'			=> 'confirm-pass',
			'label'			=> __( 'Підтвердьте пароль', 'inheart' ),
			'label_class'	=> 'half end',
			'placeholder'	=> __( 'Підтвердьте пароль', 'inheart' ),
			'autocomplete'	=> 'new-password'
		] );
		?>
	</fieldset>
</form><!-- .profile-settings-form -->


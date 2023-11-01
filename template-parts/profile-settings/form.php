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
		<label for="lastname" class="label dark half">
			<span class="label-text"><?php esc_html_e( 'Ваше прізвище', 'inheart' ) ?></span>
			<input
				id="lastname"
				name="lastname"
				type="text"
				placeholder="<?php esc_attr_e( 'Ваше прізвище', 'inheart' ) ?>"
				value="<?php echo esc_attr( $last_name ) ?>"
				required
			/>
		</label>
		<label for="firstname" class="label dark half end">
			<span class="label-text"><?php esc_html_e( "Ваше ім'я", 'inheart' ) ?></span>
			<input
				id="firstname"
				name="firstname"
				type="text"
				placeholder="<?php esc_attr_e( "Ваше ім'я", 'inheart' ) ?>"
				value="<?php echo esc_attr( $first_name ) ?>"
				required
			/>
		</label>
		<label for="email" class="label dark half">
			<span class="label-text"><?php esc_html_e( 'Email', 'inheart' ) ?></span>
			<input
				id="email"
				name="email"
				type="email"
				placeholder="<?php esc_attr_e( 'Email', 'inheart' ) ?>"
				value="<?php echo esc_attr( $email ) ?>"
				required
			/>
		</label>
	</fieldset>

	<fieldset class="flex flex-wrap">
		<legend><?php esc_html_e( 'Пароль', 'inheart' ) ?></legend>
		<label for="pass" class="label dark half">
			<span class="label-text"><?php esc_html_e( 'Поточний пароль', 'inheart' ) ?></span>
			<span class="pass-wrapper">
				<input id="pass" name="pass" type="password" placeholder="<?php esc_attr_e( 'Поточний пароль', 'inheart' ) ?>" />
				<img class="pass-toggle" src="<?php echo THEME_URI . '/static/img/eye-gray.svg' ?>" alt="" />
			</span>
		</label>
		<div style="width: 100%; height: 0"></div>
		<label for="new-pass" class="label dark half">
			<span class="label-text"><?php esc_html_e( 'Новий пароль', 'inheart' ) ?></span>
			<span class="pass-wrapper">
				<input id="new-pass" name="new-pass" type="password" placeholder="<?php esc_attr_e( 'Новий пароль', 'inheart' ) ?>" />
				<img class="pass-toggle" src="<?php echo THEME_URI . '/static/img/eye-gray.svg' ?>" alt="" />
			</span>
		</label>
		<label for="confirm-pass" class="label dark half end">
			<span class="label-text"><?php esc_html_e( 'Підтвердьте пароль', 'inheart' ) ?></span>
			<span class="pass-wrapper">
				<input id="confirm-pass" name="confirm-pass" type="password" placeholder="<?php esc_attr_e( 'Підтвердьте пароль', 'inheart' ) ?>" />
				<img class="pass-toggle" src="<?php echo THEME_URI . '/static/img/eye-gray.svg' ?>" alt="" />
			</span>
		</label>
	</fieldset>
</form><!-- .profile-settings-form -->


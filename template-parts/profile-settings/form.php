<?php

/**
 * Profile Settings page template.
 * Form part.
 *
 * @package WordPress
 * @subpackage inheart
 */

$author_id = get_current_user_id();
?>

<form class="profile-settings-form">
	<fieldset class="flex flex-wrap">
		<legend><?php esc_html_e( 'Головна інформація', 'inheart' ) ?></legend>
		<label for="lastname" class="label dark half">
			<span class="label-text"><?php esc_html_e( 'Ваше прізвище', 'inheart' ) ?></span>
			<span class="input-date-wrapper">
				<input
					id="lastname"
					name="lastname"
					type="text"
					value="<?php echo esc_attr( '' ) ?>"
					required
				/>
			</span>
		</label>
		<label for="firstname" class="label dark half end">
			<span class="label-text"><?php esc_html_e( "Ваше ім'я", 'inheart' ) ?></span>
			<span class="input-date-wrapper">
				<input
					id="firstname"
					name="firstname"
					type="text"
					value="<?php echo esc_attr( '' ) ?>"
					required
				/>
			</span>
		</label>
		<label for="middlename" class="label dark half">
			<span class="label-text"><?php esc_html_e( "По-батькові", 'inheart' ) ?></span>
			<span class="input-date-wrapper">
				<input
					id="middlename"
					name="middlename"
					type="text"
					value="<?php echo esc_attr( '' ) ?>"
					required
				/>
			</span>
		</label>
		<label for="email" class="label dark half end">
			<span class="label-text"><?php esc_html_e( 'Email', 'inheart' ) ?></span>
			<span class="input-date-wrapper">
				<input
					id="email"
					name="email"
					type="email"
					value="<?php echo esc_attr( '' ) ?>"
					required
				/>
			</span>
		</label>
	</fieldset>

	<fieldset class="flex flex-wrap">
		<legend><?php esc_html_e( 'Змiнити пароль', 'inheart' ) ?></legend>
		<label for="pass" class="label dark half">
			<span class="label-text"><?php esc_html_e( 'Поточний пароль', 'inheart' ) ?></span>
			<span class="pass-wrapper">
				<input id="pass" name="pass" type="password" />
				<img class="pass-toggle" src="<?php echo THEME_URI . '/static/img/eye-gray.svg' ?>" alt="" />
			</span>
		</label>
		<div style="width: 100%; height: 0"></div>
		<label for="new-pass" class="label dark half">
			<span class="label-text"><?php esc_html_e( 'Новий пароль', 'inheart' ) ?></span>
			<span class="pass-wrapper">
				<input id="new-pass" name="new-pass" type="password" />
				<img class="pass-toggle" src="<?php echo THEME_URI . '/static/img/eye-gray.svg' ?>" alt="" />
			</span>
		</label>
		<label for="confirm-pass" class="label dark half end">
			<span class="label-text"><?php esc_html_e( 'Підтвердьте пароль', 'inheart' ) ?></span>
			<span class="pass-wrapper">
				<input id="confirm-pass" name="confirm-pass" type="password" />
				<img class="pass-toggle" src="<?php echo THEME_URI . '/static/img/eye-gray.svg' ?>" alt="" />
			</span>
		</label>
	</fieldset>
</form><!-- .profile-settings-form -->


<?php

/**
 * Profile expand memory page to full.
 * Form part.
 *
 * @package WordPress
 * @subpackage inheart
 */

$page_to_expand	= $args['expand'] ?? '';
$user_id		= get_current_user_id();
$meta			= get_user_meta( $user_id );
$data			= get_userdata( $user_id );
$first_name		= $meta['first_name'][0] ?? '';
$last_name		= $meta['last_name'][0] ?? '';
$fathername		= get_field( 'fathername', "user_$user_id" ) ?: '';
$phone			= get_field( 'phone', "user_$user_id" ) ?: '';
$city			= get_field( 'city', "user_$user_id" ) ?: '';
$department		= get_field( 'department', "user_$user_id" ) ?: '';
$email			= $data->user_email;
?>

<form class="expand-page-form flex" data-page="<?php echo esc_attr( $page_to_expand ) ?>">
	<div class="expand-page-form-left">
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
				'required'		=> 0
			] );
			get_template_part( 'components/inputs/default', null, [
				'name'			=> 'phone',
				'label'			=> __( 'Номер телефону', 'inheart' ),
				'label_class'	=> 'full',
				'placeholder'	=> __( 'Ваш телефон', 'inheart' ),
				'value'			=> $phone,
				'autocomplete'	=> 'phone',
				'required'		=> 0
			] );
			?>
		</fieldset>

		<fieldset class="flex flex-wrap">
			<p class="legend">
				<?php _e( 'Отримати у відділенні Нової пошти', 'inheart' ) ?>
				<a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
					<g clip-path="url(#clip0_1070_53792)">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M24.0485 8.76098C24.1392 8.73244 24.2587 8.78951 24.3781 8.94646C24.3781 8.94646 24.3781 8.94645 29.7759 14.2064C30.0912 14.5203 30.0912 14.9959 29.7759 15.229C29.7759 15.229 29.7759 15.229 24.3781 20.5698C24.2587 20.7267 24.1392 20.7648 24.0485 20.7172C23.9577 20.6697 23.9004 20.5317 23.9004 20.332V9.1034C23.9004 8.90841 23.9577 8.78951 24.0485 8.76098Z" fill="#ED1C24"/>
						<path fill-rule="evenodd" clip-rule="evenodd" d="M14.815 -0.00390625H15.1972L15.565 0.148282C15.565 0.148282 15.565 0.148282 21.1204 5.64606C21.3593 5.95995 21.2781 6.19774 20.8816 6.19774C20.8816 6.19774 20.8816 6.19774 18.5792 6.19774C18.1827 6.19774 17.8674 6.51163 17.8674 6.90637C17.8674 6.90637 17.8674 6.90637 17.8674 10.9869C17.8674 11.3816 17.5474 11.6955 17.0697 11.6955C17.0697 11.6955 17.0697 11.6955 13.0237 11.6955C12.6272 11.6955 12.3072 11.3816 12.3072 10.9869C12.3072 10.9869 12.3072 10.9869 12.3072 6.90637C12.3072 6.51163 11.9919 6.19774 11.5907 6.19774H9.13058C8.73411 6.19774 8.6529 5.95995 8.89174 5.64606C8.89174 5.64606 8.89174 5.64606 14.452 0.148282L14.815 -0.00390625Z" fill="#ED1C24"/>
						<path fill-rule="evenodd" clip-rule="evenodd" d="M6.11196 8.64416C6.21227 8.69171 6.27437 8.82963 6.27437 9.02938V20.491C6.27437 20.6908 6.21227 20.8097 6.11196 20.8477C6.01642 20.8858 5.87789 20.8477 5.71548 20.7288C5.71548 20.7288 5.71548 20.7288 0.236453 15.231C-0.0788177 14.998 -0.0788177 14.5224 0.236453 14.2085C0.236453 14.2085 0.236454 14.2085 5.71548 8.79159C5.87789 8.63464 6.01642 8.5966 6.11196 8.64416Z" fill="#ED1C24"/>
						<path fill-rule="evenodd" clip-rule="evenodd" d="M13.0235 17.6641C13.0235 17.6641 13.0235 17.6641 17.0695 17.6641C17.5472 17.6641 17.8672 17.9779 17.8672 18.3727C17.8672 18.3727 17.8672 18.3727 17.8672 22.691C17.8672 23.1618 18.1825 23.4757 18.579 23.4757H20.7238C21.1203 23.4757 21.2779 23.7088 20.9626 23.9466C20.9626 23.9466 20.9626 23.9466 15.5648 29.3635C15.4024 29.5204 15.2065 29.6013 15.0059 29.6013C14.8101 29.6013 14.6094 29.5204 14.4518 29.3635C14.4518 29.3635 14.4518 29.3635 9.05398 23.9466C8.73393 23.7088 8.89157 23.4757 9.28804 23.4757C9.28804 23.4757 9.28804 23.4757 11.5905 23.4757C11.9917 23.4757 12.307 23.1618 12.307 22.691C12.307 22.691 12.307 22.691 12.307 18.3727C12.307 17.9779 12.627 17.6641 13.0235 17.6641Z" fill="#ED1C24"/>
					</g>
					<defs>
						<clipPath id="clip0_1070_53792">
							<rect width="30" height="29.6053" fill="white"/>
						</clipPath>
					</defs>
					</svg></a>
			</p>

			<?php
			get_template_part( 'components/inputs/cities', null, [
				'name'			=> 'city',
				'label'			=> __( 'Місто', 'inheart' ),
				'label_class'	=> 'full',
				'placeholder'	=> __( 'Почніть вводити назву', 'inheart' ),
				'value'			=> $city,
				'required'		=> 0
			] );
			get_template_part( 'components/inputs/departments', null, [
				'name'			=> 'departments',
				'label'			=> __( 'Номер відділення', 'inheart' ),
				'label_class'	=> 'full',
				'placeholder'	=> __( 'Номер відділення', 'inheart' ),
				'value'			=> $department,
				'required'		=> 0
			] );
			get_template_part( 'components/inputs/default', null, [
				'name'			=> 'firstname',
				'label'			=> __( "Ваше ім'я", 'inheart' ),
				'label_class'	=> 'full',
				'placeholder'	=> __( "Ваше ім'я", 'inheart' ),
				'value'			=> $first_name,
				'autocomplete'	=> 'given-name',
				'required'		=> 0
			] );
			get_template_part( 'components/inputs/default', null, [
				'name'			=> 'lastname',
				'label'			=> __( 'Ваше прізвище', 'inheart' ),
				'label_class'	=> 'full',
				'placeholder'	=> __( 'Ваше прізвище', 'inheart' ),
				'value'			=> $last_name,
				'autocomplete'	=> 'family-name',
				'required'		=> 0
			] );
			get_template_part( 'components/inputs/default', null, [
				'name'			=> 'fathername',
				'label'			=> __( 'По батькові', 'inheart' ),
				'label_class'	=> 'full',
				'placeholder'	=> __( 'По батькові', 'inheart' ),
				'value'			=> $fathername,
				'autocomplete'	=> 'additional-name',
				'required'		=> 0
			] );
			?>
		</fieldset>
	</div>

	<div class="expand-page-form-right">
		<?php get_template_part( 'components/profile/expand/form-right', null, ['expand' => $page_to_expand] ) ?>
	</div>
</form><!-- .profile-settings-form -->


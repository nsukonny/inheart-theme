<?php

/**
 * Add New Memories. Form part.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $memory_page = $args['memory_page'] ?? null ) return;

$current_user	= get_user_meta( get_current_user_id() );
$full_name		= "{$current_user['first_name'][0]} {$current_user['last_name'][0]}";
$memory_created	= get_field( 'memory_created' );
?>

<form id="add-new-memory-form" class="add-new-memory-form" enctype="multipart/form-data" data-page="<?php echo esc_attr( $memory_page ) ?>">
	<fieldset class="flex flex-wrap">
		<?php
		get_template_part( 'components/inputs/default', null, [
			'name'			=> 'fullname',
			'label'			=> __( "Ваше ім’я", 'inheart' ),
			'label_class'	=> 'full',
			'placeholder'	=> __( "Повне ім’я", 'inheart' ),
			'autocomplete'	=> 'name',
			'required'		=> 1
		] );
		get_template_part( 'components/inputs/default', null, [
			'name'			=> 'role',
			'label'			=> __( 'Ким ви припадаєте покійному', 'inheart' ),
			'label_class'	=> 'full',
			'placeholder'	=> __( 'Ваша роль', 'inheart' ),
			'required'		=> 1
		] );
		get_template_part( 'components/inputs/textarea', null, [
			'name'			=> 'memory',
			'label'			=> __( 'Ваш текст спогаду', 'inheart' ),
			'label_class'	=> 'full',
			'placeholder'	=> __( 'Ваш спогад', 'inheart' ),
			'required'		=> 1
		] );
		get_template_part( 'components/inputs/file', null, [
			'name'			=> 'photo',
			'label'			=> __( 'Додати фотографію', 'inheart' ),
			'label_class'	=> 'full',
			'icon_lead'		=> 'add-photo.svg'
		] );
		?>

		<div class="checkbox-wrapper">
			<input id="agreement" name="agreement" type="checkbox" required />
			<label for="agreement" class="label-checkbox">
				<?php
				printf(
					__( 'Погоджуюсь з умовами %sположення про обробку і захист персональних даних%s та %sофертою%s', 'inheart' ),
					'<a href="/">', '</a>', '<a href="/">', '</a>',
				);
				?>
			</label>
		</div>
	</fieldset>

	<?php
	if( $memory_created ){
		?>
		<div class="memory-created hidden">
			<div class="memory-created-text">
				<?php echo $memory_created?>
			</div>

			<a href="<?php echo esc_url( basename( $_SERVER['REQUEST_URI'] ) ) ?>" class="button lg outlined button-icon-lead">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
					<path d="M7.33301 7.83203V4.4987C7.33301 4.13051 7.63148 3.83203 7.99967 3.83203V3.83203C8.36786 3.83203 8.66634 4.13051 8.66634 4.4987V7.83203H11.9997C12.3679 7.83203 12.6663 8.13051 12.6663 8.4987V8.4987C12.6663 8.86689 12.3679 9.16536 11.9997 9.16536H8.66634V12.4987C8.66634 12.8669 8.36786 13.1654 7.99967 13.1654V13.1654C7.63148 13.1654 7.33301 12.8669 7.33301 12.4987V9.16536H3.99967C3.63148 9.16536 3.33301 8.86689 3.33301 8.4987V8.4987C3.33301 8.13051 3.63148 7.83203 3.99967 7.83203H7.33301Z" fill="currentColor"/>
				</svg>
				<span><?php _e( 'Додати ще один спогад', 'inheart' ) ?></span>
			</a>
		</div>
		<?php
	}
	?>
</form>


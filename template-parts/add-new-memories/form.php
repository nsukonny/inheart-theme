<?php

/**
 * Template name: Add New Memories
 * Form part.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $memory_page = $args['memory_page'] ?? null ) return;
?>

<form class="add-new-memory-form" enctype="multipart/form-data">
	<fieldset class="flex flex-wrap">
		<legend><?php esc_html_e( 'Створення спогаду', 'inheart' ) ?></legend>

		<label for="fullname" class="label dark">
			<span class="label-text"><?php _e( "Ваше ім’я", 'inheart' ) ?></span>
			<input
				id="fullname"
				name="fullname"
				type="text"
				placeholder="<?php _e( "Повне ім’я", 'inheart' ) ?>"
				autocomplete="name"
				required
			/>
			<span class="hint"></span>
		</label>
		<label for="role" class="label dark">
			<span class="label-text"><?php _e( 'Ким ви припадаєте покійному', 'inheart' ) ?></span>
			<input
				id="role"
				name="role"
				type="text"
				placeholder="<?php esc_html_e( 'Ким ви припадаєте покійному', 'inheart' ) ?>"
				required
			/>
			<span class="hint"></span>
		</label>
		<label for="memory" class="label dark">
			<span class="label-text"><?php _e( 'Ваш текст спогаду', 'inheart' ) ?></span>
			<textarea
				id="memory"
				name="memory"
				placeholder="<?php esc_html_e( 'Ваш текст спогаду', 'inheart' ) ?>"
				required
			></textarea>
			<span class="hint"></span>
		</label>
		<label for="photo" class="label label-file dark flex-wrap align-center">
			<span class="label-text flex-wrap align-center">
				<?php _e( 'Додати фото', 'inheart' ) ?>
			</span>
			<input id="photo" name="photo" type="file" autocomplete="photo" />
			<span class="hint"></span>
		</label>
	</fieldset>

	<button class="btn lg primary" type="submit"><?php _e( 'Далі', 'inheart' ) ?></button>
</form>

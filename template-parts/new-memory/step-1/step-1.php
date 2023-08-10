<?php

/**
 * New Memory page template.
 * Step 1.
 *
 * @see Page Template: New Memory -> Step 1
 *
 * @package WordPress
 * @subpackage inheart
 */

$title	= get_field( 'title_1' );
$desc	= get_field( 'desc_1' );

if( $memory_page_id = $_SESSION['memory_page_id'] ?? null ){
	$language		= get_field( 'language', $memory_page_id );
	$first_name		= get_field( 'first_name', $memory_page_id );
	$last_name		= get_field( 'last_name', $memory_page_id );
	$middle_name	= get_field( 'middle_name', $memory_page_id );
	$born_at		= get_field( 'born_at', $memory_page_id );
	$died_at		= get_field( 'died_at', $memory_page_id );
	$thumb_title	= has_post_thumbnail( $memory_page_id )
					? ih_get_shorter_filename( basename( get_the_post_thumbnail_url( $memory_page_id, 'full' ) ) ) : '';
}else{
	$language = $first_name = $last_name = $middle_name = $born_at = $died_at = $thumb_title = '';
}
?>

<section id="new-memory-step-1" class="new-memory-step new-memory-step-1 direction-column">
	<div class="container direction-column align-start">
		<div class="new-memory-step-suptitle">
			<?php esc_html_e( 'Крок 1', 'inheart' ) ?>
		</div>

		<?php
		if( $title ){
			?>
			<div class="new-memory-step-title">
				<?php echo $title ?>
			</div>
			<?php
		}

		if( $desc ){
			?>
			<div class="new-memory-step-desc">
				<?php echo $desc ?>
			</div>
			<?php
		}

		get_template_part( 'template-parts/new-memory/step-1/languages', null, ['language' => $language] );
		?>

		<form class="form-white new-memory-main-info" enctype="multipart/form-data">
			<fieldset class="flex flex-wrap">
				<label for="lastname" class="label dark">
					<span class="label-text"><?php esc_html_e( 'Прізвище', 'inheart' ) ?></span>
					<input
						id="lastname"
						name="lastname"
						type="text"
						placeholder="<?php esc_html_e( 'Прізвище Померлого', 'inheart' ) ?>"
						value="<?php echo esc_attr( $last_name ) ?>"
						required
					/>
				</label>
				<label for="firstname" class="label dark">
					<span class="label-text"><?php esc_html_e( "Ім'я", 'inheart' ) ?></span>
					<input
						id="firstname"
						name="firstname"
						type="text"
						placeholder="<?php esc_html_e( "Ім'я Померлого", 'inheart' ) ?>"
						value="<?php echo esc_attr( $first_name ) ?>"
						required
					/>
				</label>
				<label for="fathername" class="label dark">
					<span class="label-text"><?php esc_html_e( 'По батькові', 'inheart' ) ?></span>
					<input
						id="fathername"
						name="fathername"
						type="text"
						placeholder="<?php esc_html_e( 'По батькові Померлого', 'inheart' ) ?>"
						value="<?php echo esc_attr( $middle_name ) ?>"
						required
					/>
				</label>
				<label for="date-of-birth" class="label dark half">
					<span class="label-text"><?php esc_html_e( 'Дата народження', 'inheart' ) ?></span>
					<span class="input-date-wrapper">
						<input
							id="date-of-birth"
							name="date-of-birth"
							type="text"
							onfocus="this.type='date';this.showPicker()"
							onblur="(this.value === '' ? this.type='text' : this.type='date')"
							placeholder="__.__.____"
							value="<?php echo esc_attr( $born_at ) ?>"
							required
						/>
					</span>
				</label>
				<label for="date-of-death" class="label dark half end">
					<span class="label-text"><?php esc_html_e( 'Дата смерті', 'inheart' ) ?></span>
					<span class="input-date-wrapper">
						<input
							id="date-of-death"
							name="date-of-death"
							type="text"
							onfocus="this.type='date';this.showPicker()"
							onblur="(this.value === '' ? this.type='text' : this.type='date')"
							placeholder="__.__.____"
							value="<?php echo esc_attr( $died_at ) ?>"
							required
						/>
					</span>
				</label>
				<label for="photo" class="label label-file dark flex-wrap align-center<?php echo ( $thumb_title ? ' added' : '' ) ?>">
					<span class="label-text flex-wrap align-center">
						<?php esc_html_e( "Додати пам'ятну фотографію", 'inheart' ) ?>
					</span>
					<span class="label-text flex-wrap align-center added">
						<?php esc_html_e( "Змінити пам'ятну фотографію", 'inheart' ) ?>
					</span>
					<input id="photo" name="photo" type="file"<?php echo ( $thumb_title ? '' : ' required' ) ?> />
					<span class="filename"><?php echo esc_html( $thumb_title ) ?></span>
				</label>
			</fieldset>
		</form>
	</div>

	<div id="photo-popup" class="popup photo-popup hidden">
		<div class="popup-inner">
			<div class="popup-photo-wrapper flex align-center justify-center">
				<img class="popup-photo" src="" alt="" />
			</div>
			<div class="popup-buttons flex flex-wrap align-center justify-center">
				<button class="btn simple min-width popup-discard-photo">
					<?php esc_html_e( 'Скасувати', 'inheart' ) ?>
				</button>
				<button class="btn lg primary min-width popup-save-photo">
					<?php esc_html_e( 'Зберігти', 'inheart' ) ?>
				</button>
			</div>
		</div>
	</div>
</section><!-- #new-memory-step-1 -->


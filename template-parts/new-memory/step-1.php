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

$is_active	= ( isset( $args['is_active'] ) && $args['is_active'] == 'true' ) ? ' active' : '';
$title		= get_field( 'title_1' );
$desc		= get_field( 'desc_1' );
?>

<section id="new-memory-step-1" class="new-memory-step new-memory-step-1 direction-column<?php echo esc_attr( $is_active ) ?>">
	<div class="container direction-column">
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
		?>

		<div class="new-memory-langs inline-flex flex-wrap">
			<button class="new-memory-lang active" data-lang="uk">
				<?php esc_html_e( 'Українська', 'inheart' ) ?>
			</button>
			<button class="new-memory-lang" data-lang="ru-RU">
				<?php esc_html_e( 'Російська', 'inheart' ) ?>
			</button>
			<button class="new-memory-lang" data-lang="en-US">
				<?php esc_html_e( 'Англійська', 'inheart' ) ?>
			</button>
			<div class="new-memory-lang-bg"></div>
		</div>

		<form class="form-white new-memory-main-info" enctype="multipart/form-data">
			<fieldset class="flex flex-wrap">
				<label for="lastname" class="label dark">
					<span class="label-text"><?php esc_html_e( 'Прізвище', 'inheart' ) ?></span>
					<input id="lastname" name="lastname" type="text" placeholder="<?php esc_html_e( 'Прізвище Померлого', 'inheart' ) ?>" required />
				</label>
				<label for="firstname" class="label dark">
					<span class="label-text"><?php esc_html_e( "Ім'я", 'inheart' ) ?></span>
					<input id="firstname" name="firstname" type="text" placeholder="<?php esc_html_e( "Ім'я Померлого", 'inheart' ) ?>" required />
				</label>
				<label for="fathername" class="label dark">
					<span class="label-text"><?php esc_html_e( 'По батькові', 'inheart' ) ?></span>
					<input id="fathername" name="fathername" type="text" placeholder="<?php esc_html_e( 'По батькові Померлого', 'inheart' ) ?>" required />
				</label>
				<label for="date-of-birth" class="label dark half">
					<span class="label-text"><?php esc_html_e( 'Дата народження', 'inheart' ) ?></span>
					<span class="input-date-wrapper">
						<input id="date-of-birth" name="date-of-birth" type="text" onfocus="this.type='date';this.showPicker()" onblur="(this.value == '' ? this.type='text' : this.type='date')" placeholder="__.__.____" required />
					</span>
				</label>
				<label for="date-of-death" class="label dark half end">
					<span class="label-text"><?php esc_html_e( 'Дата смерті', 'inheart' ) ?></span>
					<span class="input-date-wrapper">
						<input id="date-of-death" name="date-of-death" type="text" onfocus="this.type='date';this.showPicker()" onblur="(this.value == '' ? this.type='text' : this.type='date')" placeholder="__.__.____" required />
					</span>
				</label>
				<label for="photo" class="label label-file dark flex-wrap align-center">
					<span class="label-text flex-wrap align-center">
						<?php esc_html_e( "Додати пам'ятну фотографію", 'inheart' ) ?>
					</span>
					<span class="label-text flex-wrap align-center added">
						<?php esc_html_e( "Змінити пам'ятну фотографію", 'inheart' ) ?>
					</span>
					<input id="photo" name="photo" type="file" required />
					<span class="filename"></span>
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
				<button class="btn lg primary min-width popup-discard-photo">
					<?php esc_html_e( 'Скасувати', 'inheart' ) ?>
				</button>
				<button class="btn lg primary min-width popup-save-photo">
					<?php esc_html_e( 'Зберігти', 'inheart' ) ?>
				</button>
			</div>
		</div>
	</div>
</section><!-- #new-memory-step-1 -->


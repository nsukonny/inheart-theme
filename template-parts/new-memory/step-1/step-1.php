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

$class	= isset( $args['active'] ) && $args['active'] ? ' active' : '';
$title	= get_field( 'title_1' );
$desc	= get_field( 'desc_1' );

if( $memory_page_id = $_SESSION['memory_page_id'] ?? null ){
	$first_name		= get_field( 'first_name', $memory_page_id );
	$last_name		= get_field( 'last_name', $memory_page_id );
	$middle_name	= get_field( 'middle_name', $memory_page_id );
	$born_at		= ih_convert_date_from_admin_for_input( get_field( 'born_at', $memory_page_id ) );
	$died_at		= ih_convert_date_from_admin_for_input( get_field( 'died_at', $memory_page_id ) );
	$thumb_title	= has_post_thumbnail( $memory_page_id )
					? ih_get_shorter_filename( basename( get_the_post_thumbnail_url( $memory_page_id, 'full' ) ) ) : '';
}else{
	$first_name = $last_name = $middle_name = $born_at = $died_at = $thumb_title = '';
}
?>

<section id="new-memory-step-1" class="new-memory-step new-memory-step-1 direction-column<?php echo esc_attr( $class ) ?>">
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

		get_template_part( 'template-parts/new-memory/step-1/languages' );
		?>

		<form class="form-white new-memory-main-info" enctype="multipart/form-data">
			<fieldset class="flex flex-wrap">
				<?php
				get_template_part( 'components/inputs/default', null, [
					'name'			=> 'lastname',
					'label'			=> __( 'Прізвище', 'inheart' ),
					'label_class'	=> 'full',
					'placeholder'	=> __( 'Прізвище Померлого', 'inheart' ),
					'value'			=> $last_name,
					'autocomplete'	=> 'family-name',
					'required'		=> 1
				] );
				get_template_part( 'components/inputs/default', null, [
					'name'			=> 'firstname',
					'label'			=> __( "Ім'я", 'inheart' ),
					'label_class'	=> 'full',
					'placeholder'	=> __( "Ім'я Померлого", 'inheart' ),
					'value'			=> $first_name,
					'autocomplete'	=> 'given-name',
					'required'		=> 1
				] );
				get_template_part( 'components/inputs/default', null, [
					'name'			=> 'fathername',
					'label'			=> __( 'По батькові', 'inheart' ),
					'label_class'	=> 'full',
					'placeholder'	=> __( 'По батькові Померлого', 'inheart' ),
					'value'			=> $middle_name,
					'autocomplete'	=> 'additional-name',
					'required'		=> 1
				] );
				get_template_part( 'components/inputs/date', null, [
					'name'			=> 'date-of-birth',
					'label'			=> __( 'Дата народження', 'inheart' ),
					'label_class'	=> 'half',
					'placeholder'	=> '__.__.____',
					'value'			=> $born_at,
					'autocomplete'	=> 'bday',
					'required'		=> 1
				] );
				get_template_part( 'components/inputs/date', null, [
					'name'			=> 'date-of-death',
					'label'			=> __( 'Дата смерті', 'inheart' ),
					'label_class'	=> 'half end',
					'placeholder'	=> '__.__.____',
					'value'			=> $died_at,
					'required'		=> 1
				] );
				?>

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


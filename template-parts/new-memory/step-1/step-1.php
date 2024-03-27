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
	$thumb			= $thumb_title ? get_the_post_thumbnail_url( $memory_page_id, 'full' ) : '';
}else{
	$first_name = $last_name = $middle_name = $born_at = $died_at = $thumb_title = $thumb = '';
}
?>

<section id="new-memory-step-1" class="new-memory-step new-memory-step-1 direction-column<?php echo esc_attr( $class ) ?>">
	<div class="container direction-column align-start">
		<div class="new-memory-step-suptitle">
			<?php _e( 'Крок 1', 'inheart' ) ?>
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
					<span class="button lg outlined button-icon-lead">
						<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
							<mask id="mask0_2655_45263" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="2" y="2" width="20" height="20">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M20.52 5.76562H18.5V3.74563C18.5 3.20562 18.06 2.76562 17.52 2.76562H17.49C16.94 2.76562 16.5 3.20562 16.5 3.74563V5.76562H14.49C13.95 5.76562 13.51 6.20563 13.5 6.74563V6.77563C13.5 7.32563 13.94 7.76562 14.49 7.76562H16.5V9.77563C16.5 10.3156 16.94 10.7656 17.49 10.7556H17.52C18.06 10.7556 18.5 10.3156 18.5 9.77563V7.76562H20.52C21.06 7.76562 21.5 7.32562 21.5 6.78562V6.74563C21.5 6.20563 21.06 5.76562 20.52 5.76562ZM15.5 9.77563V8.76562H14.49C13.96 8.76562 13.46 8.55562 13.08 8.18563C12.71 7.80562 12.5 7.30562 12.5 6.74563C12.5 6.38562 12.6 6.05562 12.77 5.76562H4.5C3.4 5.76562 2.5 6.66563 2.5 7.76562V19.7656C2.5 20.8656 3.4 21.7656 4.5 21.7656H16.5C17.6 21.7656 18.5 20.8656 18.5 19.7656V11.4856C18.2 11.6556 17.86 11.7656 17.48 11.7656C16.39 11.7556 15.5 10.8656 15.5 9.77563ZM5.5 19.7656H15.46C15.88 19.7656 16.11 19.2856 15.85 18.9556L12.9 15.2756C12.7 15.0156 12.31 15.0256 12.11 15.2856L9.5 18.7656L7.9 16.3556C7.7 16.0756 7.29 16.0556 7.08 16.3356L5.1 18.9656C4.85 19.2956 5.09 19.7656 5.5 19.7656Z" fill="black"/>
							</mask>
							<g mask="url(#mask0_2655_45263)">
								<rect y="0.265625" width="24" height="24" fill="currentColor"/>
							</g>
						</svg>
						<?php esc_html_e( "Додати пам'ятну фотографію", 'inheart' ) ?>
					</span>
					<span class="button lg outlined button-icon-lead added">
						<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
							<mask id="mask0_2655_45264" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="2" y="2" width="20" height="20">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M20.52 5.76562H18.5V3.74563C18.5 3.20562 18.06 2.76562 17.52 2.76562H17.49C16.94 2.76562 16.5 3.20562 16.5 3.74563V5.76562H14.49C13.95 5.76562 13.51 6.20563 13.5 6.74563V6.77563C13.5 7.32563 13.94 7.76562 14.49 7.76562H16.5V9.77563C16.5 10.3156 16.94 10.7656 17.49 10.7556H17.52C18.06 10.7556 18.5 10.3156 18.5 9.77563V7.76562H20.52C21.06 7.76562 21.5 7.32562 21.5 6.78562V6.74563C21.5 6.20563 21.06 5.76562 20.52 5.76562ZM15.5 9.77563V8.76562H14.49C13.96 8.76562 13.46 8.55562 13.08 8.18563C12.71 7.80562 12.5 7.30562 12.5 6.74563C12.5 6.38562 12.6 6.05562 12.77 5.76562H4.5C3.4 5.76562 2.5 6.66563 2.5 7.76562V19.7656C2.5 20.8656 3.4 21.7656 4.5 21.7656H16.5C17.6 21.7656 18.5 20.8656 18.5 19.7656V11.4856C18.2 11.6556 17.86 11.7656 17.48 11.7656C16.39 11.7556 15.5 10.8656 15.5 9.77563ZM5.5 19.7656H15.46C15.88 19.7656 16.11 19.2856 15.85 18.9556L12.9 15.2756C12.7 15.0156 12.31 15.0256 12.11 15.2856L9.5 18.7656L7.9 16.3556C7.7 16.0756 7.29 16.0556 7.08 16.3356L5.1 18.9656C4.85 19.2956 5.09 19.7656 5.5 19.7656Z" fill="black"/>
							</mask>
							<g mask="url(#mask0_2655_45264)">
								<rect y="0.265625" width="24" height="24" fill="currentColor"/>
							</g>
						</svg>
						<?php esc_html_e( "Змінити пам'ятну фотографію", 'inheart' ) ?>
					</span>
					<input
						id="photo"
						name="photo"
						type="file"
						<?php echo ( $thumb_title ? '' : ' required' ) ?>
						data-cropped="<?php echo esc_url( $thumb ) ?>"
					/>
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


<?php

/**
 * New Memory page template.
 * Step 4.
 *
 * @see Page Template: New Memory -> Step 4
 *
 * @package WordPress
 * @subpackage inheart
 */

$is_active		= ( isset( $args['is_active'] ) && $args['is_active'] == 'true' ) ? ' active' : '';
$title			= get_field( 'title_4' );
$desc			= get_field( 'desc_4' );
$photo_title	= get_field( 'photo_title' );
$photo_tip		= get_field( 'photo_tip' );
$video_title	= get_field( 'video_title' );
$video_tip		= get_field( 'video_tip' );
?>

<section id="new-memory-step-4" class="new-memory-step new-memory-step-4 step-media direction-column<?php echo esc_attr( $is_active ) ?>">
	<div class="container direction-column">
		<div class="new-memory-step-suptitle">
			<?php esc_html_e( 'Крок 4', 'inheart' ) ?>
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

		<form class="form-white media-form" enctype="multipart/form-data">
			<fieldset>
				<legend class="flex flex-wrap align-end">
					<span class="legend-title"><?php echo esc_html( $photo_title ) ?></span>
					<span class="legend-subtitle"><?php echo esc_html( $photo_tip ) ?></span>
				</legend>

				<?php get_template_part( 'template-parts/new-memory/step-4/droparea', 'photo' ) ?>
			</fieldset>

			<fieldset>
				<legend class="flex flex-wrap align-end">
					<span class="legend-title"><?php echo esc_html( $video_title ) ?></span>
					<span class="legend-subtitle"><?php echo esc_html( $video_tip ) ?></span>
				</legend>
				<div class="droparea droparea-video flex align-end justify-center">
					<div class="droparea-inner flex flex-wrap justify-center">
						<div class="droparea-icon flex justify-center">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<mask id="mask0_4210_3787" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="3" y="6" width="18" height="12">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M17 7V10.5L19.29 8.2C19.92 7.57 21 8.02 21 8.91V15.08C21 15.97 19.92 16.42 19.29 15.79L17 13.5V17C17 17.55 16.55 18 16 18H4C3.45 18 3 17.55 3 17V7C3 6.45 3.45 6 4 6H16C16.55 6 17 6.45 17 7ZM11 13H13C13.55 13 14 12.55 14 12C14 11.45 13.55 11 13 11H11V9C11 8.45 10.55 8 10 8C9.45 8 9 8.45 9 9V11H7C6.45 11 6 11.45 6 12C6 12.55 6.45 13 7 13H9V15C9 15.55 9.45 16 10 16C10.55 16 11 15.55 11 15V13Z" fill="black"/>
							</mask>
							<g mask="url(#mask0_4210_3787)">
								<rect width="24" height="24" fill="#C69A39"/>
							</g>
						</svg>
					</div>
						<div class="droparea-title">
							<?php esc_html_e( 'Перетягніть відеофайл сюди', 'inheart' ) ?>
						</div>
						<div class="droparea-desc">
							<p><?php esc_html_e( 'AVI, MP4, або MPEG до 1 гб', 'inheart' ) ?></p>
						</div>
						<button class="btn sm gray br-24"><?php esc_html_e( 'Завантажити з пристрою', 'inheart' ) ?></button>
					</div>
				</div>
			</fieldset>
		</form>
	</div><!-- .container -->
</section><!-- #new-memory-step-4 -->


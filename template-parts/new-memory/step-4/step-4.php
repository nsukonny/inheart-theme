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

$is_expanded    = isset( $_SESSION['memory_page_id'] ) ? get_field( 'is_expanded', $_SESSION['memory_page_id'] ) : '';
$selected_theme = isset( $_SESSION['memory_page_id'] ) ? get_field( 'theme', $_SESSION['memory_page_id'] ) : '';
$title          = get_field( 'title_4' );
$desc           = get_field( 'desc_4' );
$photo_title    = get_field( 'photo_title' );
$photo_tip      = get_field( 'photo_tip' );
$video_title    = get_field( 'video_title' );
$video_tip      = get_field( 'video_tip' );
?>

<section id="new-memory-step-4" class="new-memory-step new-memory-step-4 step-media direction-column">
	<div class="container direction-column">
		<div class="new-memory-step-suptitle">
			<span><?php _e( 'Крок 4', 'inheart' ) ?></span>
			<span class="hidden"><?php _e( 'Крок 6', 'inheart' ) ?></span>
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

			<?php
			// Videos and links are not available in the simple page type.
			if( $is_expanded || $selected_theme === 'military' ){
				?>
				<fieldset>
					<legend class="flex flex-wrap align-end">
						<span class="legend-title"><?php echo esc_html( $video_title ) ?></span>
						<span class="legend-subtitle"><?php echo esc_html( $video_tip ) ?></span>
					</legend>

					<?php get_template_part( 'template-parts/new-memory/step-4/droparea', 'video' ) ?>
				</fieldset>

				<?php
				get_template_part( 'template-parts/new-memory/step-4/links' );
			}
			?>
		</form>
	</div><!-- .container -->
</section><!-- #new-memory-step-4 -->


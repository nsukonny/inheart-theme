<?php

/**
 * New Memory page template.
 * Step 0.
 *
 * @see Page Template: New Memory -> Step 0
 *
 * @package WordPress
 * @subpackage inheart
 */

$is_active		= ( isset( $args['is_active'] ) && $args['is_active'] == 'true' ) ? ' active' : '';
$title			= get_field( 'title_0' );
$desc			= get_field( 'desc_0' );
$themes_desc	= get_field( 'themes_desc' );
$selected		= ( isset( $_SESSION['memory_page_id'] ) && isset( $_SESSION['step0']['ready'] ) )
				? get_field( 'theme', $_SESSION['memory_page_id'] ) : null;
?>

<section id="new-memory-step-0" class="new-memory-step new-memory-step-0 direction-column<?php echo esc_attr( $is_active ) ?>">
	<div class="container direction-column">
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

		<div class="new-memory-themes-wrapper">
			<?php
			if( $desc ){
				?>
				<div class="new-memory-themes-desc">
					<?php echo $themes_desc ?>
				</div>
				<?php
			}

			if( have_rows( 'themes' ) ){
				?>
				<div class="new-memory-themes flex flex-wrap">
					<?php
					while( have_rows( 'themes' ) ){
						the_row();
						$theme_image	= get_sub_field( 'theme_image' );
						$theme_value	= get_sub_field( 'theme_value' );
						$is_selected	= $selected == $theme_value ? ' active' : '';

						if( ! $theme_image || ! $theme_value ) continue;
						?>
						<button
							class="new-memory-theme<?php echo esc_attr( $is_selected ) ?>"
							data-value="<?php echo esc_attr( $theme_value ) ?>"
						>
							<?php echo wp_get_attachment_image( $theme_image['id'], 'ih-theme' ) ?>
						</button>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
		</div><!-- .new-memory-themes-wrapper -->
	</div><!-- .container -->
</section><!-- #new-memory-step-0 -->


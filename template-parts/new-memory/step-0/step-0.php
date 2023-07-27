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

						if( ! $theme_image = get_sub_field( 'theme_image' ) ) continue;
						?>
						<button class="new-memory-theme">
							<?php echo wp_get_attachment_image( $theme_image['id'], 'ih-theme' ) ?>
						</button>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</section><!-- #new-memory-step-0 -->


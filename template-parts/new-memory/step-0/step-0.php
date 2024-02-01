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

$class			= isset( $args['hidden'] ) && $args['hidden'] ? '' : ' active';
$title			= get_field( 'title_0' );
$desc			= get_field( 'desc_0' );
$themes_desc	= get_field( 'themes_desc' );
$theme_military	= get_field( 'theme_military_thumb' );
$selected		= isset( $_SESSION['memory_page_id'] ) ? get_field( 'theme', $_SESSION['memory_page_id'] ) : '';
?>

<section id="new-memory-step-0" class="new-memory-step new-memory-step-0 direction-column<?php echo esc_attr( $class ) ?>">
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

		<div class="new-memory-themes-wrapper flex flex-wrap">
			<div class="new-memory-themes-left">
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
			</div><!-- .new-memory-themes-left -->

			<?php
			if( $theme_military ){
				?>
				<div class="new-memory-themes-right">
					<div class="new-memory-themes-desc flex flex-wrap">
						<svg width="16" height="22" viewBox="0 0 16 22" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M8.00056 21.615C7.15776 20.6109 6.50812 19.5065 6.06478 18.2838C6.0195 18.1591 5.96583 18.1172 5.83137 18.1175C3.94479 18.1225 2.05793 18.1211 0.171355 18.1211C0.116566 18.1211 0.0620567 18.1211 0 18.1211V2.73021C0.275901 2.88452 0.545931 3.0173 0.797512 3.17915C1.94947 3.91935 2.65642 4.99081 3.06845 6.27499C3.30522 7.01268 3.42821 7.7719 3.4939 8.54314C3.57637 9.51144 3.56183 10.4798 3.48244 11.4455C3.41507 12.2654 3.30913 13.0822 3.218 13.9001C3.19089 14.1445 3.15567 14.3882 3.1224 14.6445H6.22412C6.22412 14.441 6.22859 14.2339 6.22328 14.0268C6.197 12.9933 6.17716 11.9596 6.13802 10.9264C6.09609 9.81446 6.04018 8.70275 5.98148 7.5916C5.94486 6.89919 5.87554 6.20818 5.84842 5.5155C5.79335 4.10525 6.12768 2.78864 6.8458 1.57154C7.15664 1.04462 7.52144 0.557672 7.93068 0.103707C7.9522 0.0799469 7.96953 0.052273 8.00867 0C8.26668 0.321744 8.5191 0.613578 8.74608 0.924141C9.3904 1.80635 9.83654 2.77857 10.0426 3.85618C10.1709 4.52762 10.1991 5.20354 10.1499 5.88504C10.1021 6.54894 10.061 7.21339 10.0258 7.87812C9.97687 8.79388 9.92571 9.70991 9.89413 10.6265C9.84968 11.9202 9.82229 13.2147 9.78762 14.5087C9.7865 14.5542 9.78762 14.5995 9.78762 14.6546H12.8706C12.8139 14.199 12.7521 13.7531 12.704 13.3056C12.6372 12.6861 12.5603 12.0667 12.5243 11.4453C12.4835 10.7392 12.4552 10.0303 12.4709 9.32332C12.4983 8.07603 12.671 6.85055 13.1585 5.68713C13.7047 4.38338 14.6023 3.41368 15.8859 2.80932C15.9108 2.79758 15.9365 2.78668 15.9625 2.77718C15.9706 2.7741 15.9807 2.77662 16 2.77662V18.1275C15.9474 18.1275 15.8977 18.1275 15.8482 18.1275C13.9477 18.1275 12.0468 18.1292 10.1463 18.1256C10.0272 18.1256 9.97994 18.1639 9.94221 18.274C9.5584 19.4011 8.70499 20.8656 8.00056 21.615Z" fill="#E69636"/>
						</svg>
						<span><?php _e( "Сторінка пам'яті військовослужбовця", 'inheart' ) ?></span>
					</div>

					<button class="new-memory-theme<?php echo ( $selected === 'military' ? ' active' : '' ) ?>" data-value="military">
						<?php echo wp_get_attachment_image( $theme_military, 'ih-theme' ) ?>
					</button>
				</div>
				<?php
			}
			?>
		</div><!-- .new-memory-themes-wrapper -->
	</div><!-- .container -->
</section><!-- #new-memory-step-0 -->


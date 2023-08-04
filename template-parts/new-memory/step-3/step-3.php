<?php

/**
 * New Memory page template.
 * Step 3.
 *
 * @see Page Template: New Memory -> Step 3
 *
 * @package WordPress
 * @subpackage inheart
 */

$title		= get_field( 'title_3' );
$desc		= get_field( 'desc_3' );
$init_text	= get_field( 'epitaph_init_text' );
$max_length	= get_field( 'epitaph_max_length' ) ?: 500;
?>

<section id="new-memory-step-3" class="new-memory-step new-memory-step-3 direction-column">
	<div class="container direction-column">
		<div class="epitaph-wrapper flex direction-column">
			<div class="new-memory-step-suptitle">
				<?php esc_html_e( 'Крок 3', 'inheart' ) ?>
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

			<form class="flex form-white epitaph">
				<fieldset class="flex direction-column">
					<legend class="flex flex-wrap align-end">
						<span class="legend-title"><?php esc_html_e( 'Епітафія', 'inheart' ) ?></span>
						<span class="legend-subtitle">
							<span class="symbols-count-typed"><?php echo esc_html( mb_strlen( $init_text, 'UTF-8' ) ) ?></span>/<span class="symbols-count-allowed"><?php echo esc_html( $max_length ) ?></span>
						</span>
					</legend>
					<textarea class="epitaph-text" name="epitaph-text" placeholder="<?php esc_attr_e( 'Напишіть якомога детальну біографію', 'inheart' ) ?>"><?php echo esc_html( $init_text ) ?></textarea>
				</fieldset>
			</form>
		</div><!-- .step-3-inner -->
	</div><!-- .container -->
</section><!-- #new-memory-step-3 -->


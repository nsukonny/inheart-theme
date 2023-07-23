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

$is_active	= ( isset( $args['is_active'] ) && $args['is_active'] == 'true' ) ? ' active' : '';
$title		= get_field( 'title_4' );
$desc		= get_field( 'desc_4' );
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

		<form class="form-white media-form">
			<fieldset></fieldset>
		</form>
	</div><!-- .container -->
</section><!-- #new-memory-step-4 -->


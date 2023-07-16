<?php

/**
 * New Memory page template.
 * Step 2.
 *
 * @see Page Template: New Memory -> Step 2
 *
 * @package WordPress
 * @subpackage inheart
 */

$is_active		= ( isset( $args['is_active'] ) && $args['is_active'] == 'true' ) ? ' active' : '';
$title			= get_field( 'title_2' );
$desc			= get_field( 'desc_2' );
?>

<section id="new-memory-step-2" class="new-memory-step new-memory-step-2<?php echo esc_attr( $is_active ) ?>">
	<div class="container">
		<div class="new-memory-step-suptitle">
			<?php esc_html_e( 'Крок 2', 'inheart' ) ?>
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
</section><!-- #new-memory-step-2 -->


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

$title				= get_field( 'title_3' );
$desc				= get_field( 'desc_3' );
$saved_text			= get_field( 'epitaphy', $_SESSION['memory_page_id'] );
$max_length			= get_field( 'epitaph_max_length' ) ?: 500;
$epitaph			= ( isset( $_SESSION['memory_page_id'] ) && $saved_text ) ? $saved_text : '';
$epitaph_lastname	= get_field( 'epitaph_lastname', $_SESSION['memory_page_id'] );
$epitaph_firstname	= get_field( 'epitaph_firstname', $_SESSION['memory_page_id'] );
$epitaph_role		= get_field( 'epitaph_role', $_SESSION['memory_page_id'] );
?>

<section id="new-memory-step-3" class="new-memory-step new-memory-step-3 direction-column">
	<div class="container direction-column">
		<div class="epitaph-wrapper flex direction-column">
			<div class="new-memory-step-suptitle">
				<span><?php _e( 'Крок 3', 'inheart' ) ?></span>
				<span class="hidden"><?php _e( 'Крок 5', 'inheart' ) ?></span>
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
				<fieldset class="flex flex-wrap">
					<legend class="flex flex-wrap align-end">
						<span class="legend-title"><?php esc_html_e( 'Від кого', 'inheart' ) ?></span>
					</legend>
					<?php
					get_template_part( 'components/inputs/default', null, [
						'name'			=> 'epitaph-lastname',
						'label'			=> __( 'Прізвище', 'inheart' ),
						'label_class'	=> 'half',
						'placeholder'	=> __( 'Прізвище', 'inheart' ),
						'value'			=> $epitaph_lastname,
						'autocomplete'	=> 'family-name'
					] );
					get_template_part( 'components/inputs/default', null, [
						'name'			=> 'epitaph-firstname',
						'label'			=> __( "Ім'я", 'inheart' ),
						'label_class'	=> 'half end',
						'placeholder'	=> __( "Ім'я", 'inheart' ),
						'value'			=> $epitaph_firstname,
						'autocomplete'	=> 'given-name'
					] );
					get_template_part( 'components/inputs/default', null, [
						'name'			=> 'epitaph-role',
						'label'			=> __( 'Ким доводилися померлому', 'inheart' ),
						'label_class'	=> 'full',
						'placeholder'	=> __( 'Ким доводилися померлому', 'inheart' ),
						'value'			=> $epitaph_role
					] );
					?>
				</fieldset>
				<fieldset class="flex direction-column">
					<legend class="flex flex-wrap align-end">
						<span class="legend-title"><?php esc_html_e( 'Епітафія', 'inheart' ) ?></span>
						<span class="legend-subtitle">
							<span class="symbols-count-typed"><?php echo esc_html( mb_strlen( $epitaph, 'UTF-8' ) ) ?></span>/<span class="symbols-count-allowed"><?php echo esc_html( $max_length ) ?></span>
						</span>
					</legend>
					<textarea
						class="epitaph-text"
						name="epitaph-text"
						placeholder="<?php esc_attr_e( 'Напишіть детальну біографію', 'inheart' ) ?>"
					><?php echo esc_html( $epitaph ) ?></textarea>
				</fieldset>
			</form>
		</div><!-- .epitaph-wrapper -->
	</div><!-- .container -->
</section><!-- #new-memory-step-3 -->


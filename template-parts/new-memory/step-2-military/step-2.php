<?php

/**
 * New Memory page template.
 * Step 2 (Military).
 *
 * @see Page Template: New Memory -> Step 2 (Military)
 *
 * @package WordPress
 * @subpackage inheart
 */

$title				= get_field( 'title_2_military' );
$memory_page_id		= $_SESSION['memory_page_id'];
$army_type			= get_field( 'army_type', $memory_page_id );
$brigade_type		= get_field( 'brigade_type', $memory_page_id );
$military_position	= get_field( 'military_position', $memory_page_id );
$call_sign			= get_field( 'call_sign', $memory_page_id );
?>

<section id="new-memory-step-2-military" class="new-memory-step new-memory-step-2-military direction-column">
	<div class="container direction-column">
		<div class="new-memory-step-suptitle">
			<?php esc_html_e( 'Крок 1-1 (Військовослужбовець)', 'inheart' ) ?>
		</div>

		<?php
		if( $title ){
			?>
			<div class="new-memory-step-title">
				<?php echo $title ?>
			</div>
			<?php
		}
		?>

		<form class="form-white">
			<fieldset>
				<?php
				get_template_part( 'components/inputs/army-type', null, [
					'name'			=> 'army',
					'label'			=> __( 'Рід військ', 'inheart' ),
					'label_class'	=> 'full label-army',
					'placeholder'	=> __( 'Наприклад "Сухопутні війська"', 'inheart' ),
					'value'			=> $army_type,
					'required'		=> 1,
					'icon_tail'		=> 'arrow-down-s-line.svg'
				] );
				get_template_part( 'components/inputs/army', null, [
					'name'			=> 'brigade',
					'label'			=> __( 'Бригада чи підрозділ', 'inheart' ),
					'label_class'	=> 'full',
					'placeholder'	=> __( 'Наприклад "45-й окремий мотопіхотний б..."', 'inheart' ),
					'value'			=> $brigade_type,
					'required'		=> 1,
					'icon_tail'		=> 'arrow-down-s-line.svg',
					'types'			=> get_field( 'brigades', 'option' )
				] );
				get_template_part( 'components/inputs/ranks', null, ['army_type' => $army_type] );
				get_template_part( 'components/inputs/default', null, [
					'name'			=> 'position',
					'label'			=> __( 'Посада', 'inheart' ),
					'label_class'	=> 'full',
					'placeholder'	=> __( 'Наприклад "Кулеметник"', 'inheart' ),
					'value'			=> $military_position
				] );
				get_template_part( 'components/inputs/default', null, [
					'name'			=> 'call-sign',
					'label'			=> __( 'Позивний', 'inheart' ),
					'label_class'	=> 'full',
					'placeholder'	=> __( 'Позивний', 'inheart' ),
					'value'			=> $call_sign
				] );
				?>
			</fieldset>
		</form><!-- .sections-wrapper -->
</section><!-- #new-memory-step-2-military -->


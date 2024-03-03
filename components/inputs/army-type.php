<?php

/**
 * Input for army type layout.
 *
 * @package WordPress
 * @subpackage inheart
 */

$army_types = get_posts( [
	'post_type'		=> 'army',
	'numberposts'	=> -1,
	'post_status'	=> 'publish',
	'orderby'		=> 'title',
	'order'			=> 'ASC'
] );

if( empty( $army_types ) ) return;

$name			= $args['name'] ?? '';
$label			= $args['label'] ?? '';
$label_class	= $args['label_class'] ?? 'half';
$type			= $args['type'] ?? 'text';
$placeholder	= $args['placeholder'] ?? '';
$army_type_id	= $args['value'] ?? null;
$value			= $army_type_id ? get_the_title( $army_type_id ) : '';
$required		= $args['required'] ?? '';
$icon_tail		= $args['icon_tail'] ?? '';
$wrap_class		= ' select';
$wrap_class		.= $icon_tail ? ' icon-tail' : '';
?>

<label for="<?php echo esc_attr( $name ) ?>" class="label <?php echo esc_attr( $label_class ) ?>">
	<?php
	echo ( $label ? '<span class="label-text">' . $label . '</span>' : '' );

	if( $icon_tail ) echo '<span class="input-wrapper ' . esc_attr( $type ) . esc_attr( $wrap_class ) . '">';
	?>

	<input
		id="<?php echo esc_attr( $name ) ?>"
		name="<?php echo esc_attr( $name ) ?>"
		type="text"
		placeholder="<?php echo esc_attr( $placeholder ) ?>"
		value="<?php echo esc_attr( $value ) ?>"
		data-id="<?php echo esc_attr( $army_type_id ) ?>"
		<?php echo ( $required ? 'required' : '' ) ?>
	/>

	<?php
	if( $icon_tail ){
		echo '<span class="input-icon tail">';
			get_template_part( 'components/svg/svg', null, ['url' => THEME_URI . '/static/img/' . esc_attr( $icon_tail )] );
		echo '</span>';
	}

	if( $icon_tail ) echo '</span>';

	echo '<span class="options direction-column">';

	foreach( $army_types as $army ){
		$army_id = $army->ID;
		echo '<span class="option" data-id="', esc_attr( $army_id ), '">',
			esc_html( get_the_title( $army_id ) ),
		'</span>';
	}

	echo '</span>';
	?>

	<span class="hint"></span>
</label>


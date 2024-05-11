<?php

/**
 * Input for army ranks list.
 *
 * @package WordPress
 * @subpackage inheart
 */

$army_ranks		= get_field( 'army_ranks', 'option' );
$sea_ranks		= get_field( 'sea_army_ranks', 'option' );
$police_ranks	= get_field( 'police_ranks', 'option' );

if( ! $army_ranks && ! $sea_ranks && ! $police_ranks ) return;

$army_type		= $args['army_type'] ?? '';
$ranks_type		= $army_type ? get_field( 'ranks_type', $army_type ) : '';
$military_title	= get_field( 'military_title', $_SESSION['memory_page_id'] );
?>

<label for="title" class="label full label-<?php echo esc_attr( $ranks_type ) ?>-ranks">
	<span class="label-text"><?php _e( 'Військове звання', 'inheart' ) ?></span>

	<span class="input-wrapper text select icon-tail">
		<input
			id="title"
			name="title"
			type="text"
			placeholder="<?php esc_attr_e( 'Наприклад "Молодший лейтенант"', 'inheart' ) ?>"
			value="<?php echo esc_attr( $military_title ) ?>"
		/>
		<span class="input-icon tail">
			<?php get_template_part( 'components/svg/svg', null, ['url' => THEME_URI . '/static/img/arrow-down-s-line.svg'] ) ?>
		</span>
	</span>

	<?php
	if( $army_ranks ){
		echo '<span class="options direction-column army">';

		foreach( $army_ranks as $type )
			echo '<span class="option">', esc_html( $type['rank'] ), '</span>';

		echo '</span>';
	}

	if( $sea_ranks ){
		echo '<span class="options direction-column sea">';

		foreach( $sea_ranks as $type )
			echo '<span class="option">', esc_html( $type['rank'] ), '</span>';

		echo '</span>';
	}

	if( $police_ranks ){
		echo '<span class="options direction-column police">';

		foreach( $police_ranks as $type )
			echo '<span class="option">', esc_html( $type['rank'] ), '</span>';

		echo '</span>';
	}
	?>

	<span class="hint"></span>
</label>


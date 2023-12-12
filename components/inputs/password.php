<?php

/**
 * Input layout. Type password.
 *
 * @package WordPress
 * @subpackage inheart
 */

$name			= $args['name'] ?? '';
$label			= $args['label'] ?? '';
$label_class	= $args['label_class'] ?? 'half';
$placeholder	= $args['placeholder'] ?? '';
$autocomplete	= $args['autocomplete'] ?? 'current-password';
$required		= $args['required'] ?? '';
$lost_pass		= $args['lost_pass'] ?? '';
?>

<label for="<?php echo esc_attr( $name ) ?>" class="label <?php echo esc_attr( $label_class ) ?>">
	<?php echo ( $label ? '<span class="label-text">' . $label . '</span>' : '' ) ?>
	<span class="input-wrapper password icon-tail">
		<input
			id="<?php echo esc_attr( $name ) ?>"
			name="<?php echo esc_attr( $name ) ?>"
			type="password"
			placeholder="<?php echo esc_attr( $placeholder ) ?>"
			autocomplete="<?php echo esc_attr( $autocomplete ) ?>"
		/>

		<?php
		echo '<span class="input-icon tail pass-toggle">';
		get_template_part( 'components/svg/svg', null, ['url' => THEME_URI . '/static/img/eye-gray.svg'] );
		echo '</span>';
		echo '<span class="input-icon tail pass-toggle crossed">';
		get_template_part( 'components/svg/svg', null, ['url' => THEME_URI . '/static/img/eye-gray-crossed.svg'] );
		echo '</span>';
		?>
	</span>
	<span class="hint"></span>

	<?php
	if( $lost_pass ){
		?>
		<span class="lostpass-wrapper">
			<a class="link bright-yellow" href="<?php echo get_the_permalink( pll_get_post( 14 ) ) ?>">
				<?php esc_html_e( "Не пам'ятаю пароль", 'inheart' ) ?>
			</a>
		</span>
		<?php
	}
	?>
</label>


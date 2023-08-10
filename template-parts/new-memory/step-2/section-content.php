<?php

/**
 * New Memory page template.
 * Step 2. Section content.
 *
 * @see Page Template: New Memory -> Step 2
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $section = $args['section'] ?? null ) return;

$sections	= $args['sections'] ?? null;
$title		= $section['category'];
$text		= $section['text'] ?? '';
$custom		= ( isset( $section['own_title'] ) && $section['own_title'] ) ? ' custom' : '';
$index		= $section['index'] ?? 0;
$thumb		= ( $custom && isset( $sections[$index] ) ) ? $sections[$index]['thumb'] : '';

if( ! $title ) return;
?>

<div class="section-content<?php echo esc_attr( $custom ) ?>" data-id="<?php echo esc_attr( $index ) ?>">
	<div class="section-content-title">
		<?php
		if( $custom ){
			?>
			<input
				class="section-content-title-input"
				placeholder="<?php esc_html_e( 'Придумайте заголовок', 'inheart' ) ?>"
				value="<?php echo esc_attr( $title ) ?>"
			/>
			<?php
		}else{
			echo esc_html( $title );
		}
		?>
	</div>
	<textarea
		class="section-content-text"
		placeholder="<?php esc_attr_e( 'Напишіть якомога детальну біографію', 'inheart' ) ?>"
	><?php echo esc_html( $text ) ?></textarea>

	<button
		class="section-drag"
		aria-label="<?php esc_attr_e( 'Перетягнути секцію', 'inheart' ) ?>"
		title="<?php esc_attr_e( 'Перетягнути секцію', 'inheart' ) ?>"
	>
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<mask id="content-drag-<?php echo esc_attr( $index ) ?>" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="7" y="4" width="10" height="16">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M9 4C7.9 4 7 4.9 7 6C7 7.1 7.9 8 9 8C10.1 8 11 7.1 11 6C11 4.9 10.1 4 9 4ZM7 12C7 10.9 7.9 10 9 10C10.1 10 11 10.9 11 12C11 13.1 10.1 14 9 14C7.9 14 7 13.1 7 12ZM9 20C10.1 20 11 19.1 11 18C11 16.9 10.1 16 9 16C7.9 16 7 16.9 7 18C7 19.1 7.9 20 9 20ZM17 6C17 7.1 16.1 8 15 8C13.9 8 13 7.1 13 6C13 4.9 13.9 4 15 4C16.1 4 17 4.9 17 6ZM15 10C13.9 10 13 10.9 13 12C13 13.1 13.9 14 15 14C16.1 14 17 13.1 17 12C17 10.9 16.1 10 15 10ZM13 18C13 16.9 13.9 16 15 16C16.1 16 17 16.9 17 18C17 19.1 16.1 20 15 20C13.9 20 13 19.1 13 18Z" fill="black"/>
			</mask>
			<g mask="url(#content-drag-<?php echo esc_attr( $index ) ?>)">
				<rect width="24" height="24" fill="currentColor"/>
			</g>
		</svg>
	</button>
	<button
		class="section-remove"
		aria-label="<?php esc_attr_e( 'Видалити секцію', 'inheart' ) ?>"
		title="<?php esc_attr_e( 'Видалити секцію', 'inheart' ) ?>"
	>
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<mask id="content-remove-<?php echo esc_attr( $index ) ?>" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="2" y="2" width="20" height="20">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM7 12C7 12.55 7.45 13 8 13H16C16.55 13 17 12.55 17 12C17 11.45 16.55 11 16 11H8C7.45 11 7 11.45 7 12ZM4 12C4 16.41 7.59 20 12 20C16.41 20 20 16.41 20 12C20 7.59 16.41 4 12 4C7.59 4 4 7.59 4 12Z" fill="black"/>
			</mask>
			<g mask="url(#content-remove-<?php echo esc_attr( $index ) ?>)">
				<rect width="24" height="24" fill="currentColor"/>
			</g>
		</svg>
	</button>

	<?php if( $custom ) echo '<img class="section-content-thumb" src="' . esc_url( $thumb ) . '" alt="" />' ?>
</div><!-- .section-content -->


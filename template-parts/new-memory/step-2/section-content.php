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
$photos		= $section['photos'] ?? null;
$class		= $args['class'] ?? '';

if( ! $title ) return;
?>

<div
	class="section-content<?php echo esc_attr( $custom ), ' ', esc_attr( $class ) ?>"
	data-id="<?php echo esc_attr( $index ) ?>"
>
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

	<form enctype="multipart/form-data" class="section-content-form">
		<fieldset>
			<label class="button tetriary button-icon-lead section-content-form-add">
				<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
					<mask id="mask_add_photo_<?php echo esc_attr( $index ) ?>" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="2" y="2" width="20" height="20">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M20.52 5.76562H18.5V3.74563C18.5 3.20562 18.06 2.76562 17.52 2.76562H17.49C16.94 2.76562 16.5 3.20562 16.5 3.74563V5.76562H14.49C13.95 5.76562 13.51 6.20563 13.5 6.74563V6.77563C13.5 7.32563 13.94 7.76562 14.49 7.76562H16.5V9.77563C16.5 10.3156 16.94 10.7656 17.49 10.7556H17.52C18.06 10.7556 18.5 10.3156 18.5 9.77563V7.76562H20.52C21.06 7.76562 21.5 7.32562 21.5 6.78562V6.74563C21.5 6.20563 21.06 5.76562 20.52 5.76562ZM15.5 9.77563V8.76562H14.49C13.96 8.76562 13.46 8.55562 13.08 8.18563C12.71 7.80562 12.5 7.30562 12.5 6.74563C12.5 6.38562 12.6 6.05562 12.77 5.76562H4.5C3.4 5.76562 2.5 6.66563 2.5 7.76562V19.7656C2.5 20.8656 3.4 21.7656 4.5 21.7656H16.5C17.6 21.7656 18.5 20.8656 18.5 19.7656V11.4856C18.2 11.6556 17.86 11.7656 17.48 11.7656C16.39 11.7556 15.5 10.8656 15.5 9.77563ZM5.5 19.7656H15.46C15.88 19.7656 16.11 19.2856 15.85 18.9556L12.9 15.2756C12.7 15.0156 12.31 15.0256 12.11 15.2856L9.5 18.7656L7.9 16.3556C7.7 16.0756 7.29 16.0556 7.08 16.3356L5.1 18.9656C4.85 19.2956 5.09 19.7656 5.5 19.7656Z" fill="black"/>
					</mask>
					<g mask="url(#mask_add_photo_<?php echo esc_attr( $index ) ?>)">
						<rect y="0.265625" width="24" height="24" fill="currentColor"/>
					</g>
				</svg>
				<span class="label-button-text">
					<?php _e( 'Додати фото', 'inheart' ) ?>
				</span>
				<input class="section-add-photo" type="file" name="section-add-photo" />
			</label>
		</fieldset>
	</form>

	<div class="section-content-photos flex flex-wrap">
		<?php
		if( ! empty( $photos ) ){
			foreach( $photos as $photo )
				get_template_part( 'components/memory-page/bio-section-image', null, ['photo' => $photo] );
		}
		?>
	</div>

	<?php
	get_template_part( 'components/popup/popup', null, [
		'text'		=> __( 'Дійсно видалити фото?', 'inheart' ),
		'class'		=> 'delete',
		'label_yes'	=> __( 'Видалити', 'inheart' ),
		'label_no'	=> __( 'Залишити', 'inheart' )
	] );
	?>
</div><!-- .section-content -->


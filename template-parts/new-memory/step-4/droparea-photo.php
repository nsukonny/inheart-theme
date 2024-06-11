<?php

/**
 * New Memory page template.
 * Step 4, photo droparea.
 *
 * @see Page Template: New Memory -> Step 4
 *
 * @package WordPress
 * @subpackage inheart
 */

$photos = ( isset( $_SESSION['memory_page_id'] ) && get_field( 'photo', $_SESSION['memory_page_id'] ) )
		? get_field( 'photo', $_SESSION['memory_page_id'] ) : null;
?>

<div class="droparea droparea-photo flex align-end justify-center">
	<div class="droparea-inner flex flex-wrap justify-center<?php echo ( ! empty( $photos ) ? ' hidden' : '' ) ?>">
		<div class="droparea-icon flex justify-center">
			<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
				<mask id="mask0_2655_45265" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="2" y="2" width="20" height="20">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M20.52 5.76562H18.5V3.74563C18.5 3.20562 18.06 2.76562 17.52 2.76562H17.49C16.94 2.76562 16.5 3.20562 16.5 3.74563V5.76562H14.49C13.95 5.76562 13.51 6.20563 13.5 6.74563V6.77563C13.5 7.32563 13.94 7.76562 14.49 7.76562H16.5V9.77563C16.5 10.3156 16.94 10.7656 17.49 10.7556H17.52C18.06 10.7556 18.5 10.3156 18.5 9.77563V7.76562H20.52C21.06 7.76562 21.5 7.32562 21.5 6.78562V6.74563C21.5 6.20563 21.06 5.76562 20.52 5.76562ZM15.5 9.77563V8.76562H14.49C13.96 8.76562 13.46 8.55562 13.08 8.18563C12.71 7.80562 12.5 7.30562 12.5 6.74563C12.5 6.38562 12.6 6.05562 12.77 5.76562H4.5C3.4 5.76562 2.5 6.66563 2.5 7.76562V19.7656C2.5 20.8656 3.4 21.7656 4.5 21.7656H16.5C17.6 21.7656 18.5 20.8656 18.5 19.7656V11.4856C18.2 11.6556 17.86 11.7656 17.48 11.7656C16.39 11.7556 15.5 10.8656 15.5 9.77563ZM5.5 19.7656H15.46C15.88 19.7656 16.11 19.2856 15.85 18.9556L12.9 15.2756C12.7 15.0156 12.31 15.0256 12.11 15.2856L9.5 18.7656L7.9 16.3556C7.7 16.0756 7.29 16.0556 7.08 16.3356L5.1 18.9656C4.85 19.2956 5.09 19.7656 5.5 19.7656Z" fill="black"/>
				</mask>
				<g mask="url(#mask0_2655_45265)">
					<rect y="0.265625" width="24" height="24" fill="#C69A39"/>
				</g>
			</svg>
		</div>
		<div class="droparea-title">
			<?php _e( 'Перетягніть фотокартки сюди', 'inheart' ) ?>
		</div>
		<div class="droparea-desc">
			<p><?php _e( 'Потрібна щонайменше 1 фотографія', 'inheart' ) ?></p>
			<p><?php _e( 'PNG, JPG, або JPEG до 10 мб', 'inheart' ) ?></p>
		</div>
		<label for="file-photo" class="label-button btn sm gray br-24">
			<span class="label-button-text">
				<?php esc_html_e( 'Завантажити з пристрою', 'inheart' ) ?>
			</span>
			<input id="file-photo" class="file-photo" type="file" name="file-photo" multiple />
		</label>
	</div>

	<div class="droparea-images flex flex-wrap<?php echo ( empty( $photos ) ? ' hidden' : '' ) ?>">
		<?php
		if( ! empty( $photos ) ){
			foreach( $photos as $photo ){
				?>
				<div class="droparea-img-loaded">
					<?php echo wp_get_attachment_image( $photo, 'ih-profile-media' ) ?>

					<div class="droparea-img-delete flex align-center justify-center" data-id="<?php echo esc_attr( $photo ) ?>">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<mask id="mask_<?php echo esc_attr( $photo ) ?>" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="5" y="3" width="14" height="18">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M14.79 3.29L15.5 4H18C18.55 4 19 4.45 19 5C19 5.55 18.55 6 18 6H6C5.45 6 5 5.55 5 5C5 4.45 5.45 4 6 4H8.5L9.21 3.29C9.39 3.11 9.65 3 9.91 3H14.09C14.35 3 14.61 3.11 14.79 3.29ZM6 19C6 20.1 6.9 21 8 21H16C17.1 21 18 20.1 18 19V9C18 7.9 17.1 7 16 7H8C6.9 7 6 7.9 6 9V19ZM9 9H15C15.55 9 16 9.45 16 10V18C16 18.55 15.55 19 15 19H9C8.45 19 8 18.55 8 18V10C8 9.45 8.45 9 9 9Z" fill="black"/>
							</mask>
							<g mask="url(#mask_<?php echo esc_attr( $photo ) ?>)">
								<rect width="24" height="24" fill="currentColor"/>
							</g>
						</svg>
					</div>
				</div>
				<?php
			}
		}
		?>

		<div class="droparea-images-load flex direction-column align-center">
			<label for="file-photo-more">
				<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
					<mask id="mask0_2655_4526" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="2" y="2" width="20" height="20">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M20.52 5.76562H18.5V3.74563C18.5 3.20562 18.06 2.76562 17.52 2.76562H17.49C16.94 2.76562 16.5 3.20562 16.5 3.74563V5.76562H14.49C13.95 5.76562 13.51 6.20563 13.5 6.74563V6.77563C13.5 7.32563 13.94 7.76562 14.49 7.76562H16.5V9.77563C16.5 10.3156 16.94 10.7656 17.49 10.7556H17.52C18.06 10.7556 18.5 10.3156 18.5 9.77563V7.76562H20.52C21.06 7.76562 21.5 7.32562 21.5 6.78562V6.74563C21.5 6.20563 21.06 5.76562 20.52 5.76562ZM15.5 9.77563V8.76562H14.49C13.96 8.76562 13.46 8.55562 13.08 8.18563C12.71 7.80562 12.5 7.30562 12.5 6.74563C12.5 6.38562 12.6 6.05562 12.77 5.76562H4.5C3.4 5.76562 2.5 6.66563 2.5 7.76562V19.7656C2.5 20.8656 3.4 21.7656 4.5 21.7656H16.5C17.6 21.7656 18.5 20.8656 18.5 19.7656V11.4856C18.2 11.6556 17.86 11.7656 17.48 11.7656C16.39 11.7556 15.5 10.8656 15.5 9.77563ZM5.5 19.7656H15.46C15.88 19.7656 16.11 19.2856 15.85 18.9556L12.9 15.2756C12.7 15.0156 12.31 15.0256 12.11 15.2856L9.5 18.7656L7.9 16.3556C7.7 16.0756 7.29 16.0556 7.08 16.3356L5.1 18.9656C4.85 19.2956 5.09 19.7656 5.5 19.7656Z" fill="black"/>
					</mask>
					<g mask="url(#mask0_2655_4526)">
						<rect y="0.265625" width="24" height="24" fill="#C69A39"/>
					</g>
				</svg>
				<span class="label-button btn sm gray br-24 label-button-text">
					<?php _e( 'Завантажити ще', 'inheart' ) ?>
				</span>
				<input id="file-photo-more" class="file-photo" type="file" name="file-photo-more" multiple />
			</label>
		</div>
	</div>

	<div class="droparea-loader flex direction-column align-center justify-end hidden">
		<div class="droparea-loader-percents">
			<span>0</span>%
		</div>
		<div class="droparea-loader-note">
			<?php _e( 'Завантаження фотокартки...', 'inheart' ) ?>
		</div>
		<progress value="0" max="100"></progress>
		<button class="btn md gray droparea-loader-cancel" type="button">
			<?php _e( 'Скасувати', 'inheart' ) ?>
		</button>
	</div>
</div><!-- .droparea -->


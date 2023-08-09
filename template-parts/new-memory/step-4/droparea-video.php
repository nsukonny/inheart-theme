<?php

/**
 * New Memory page template.
 * Step 4, video droparea.
 *
 * @see Page Template: New Memory -> Step 4
 *
 * @package WordPress
 * @subpackage inheart
 */

$videos	= ( isset( $_SESSION['memory_page_id'] ) && get_field( 'video', $_SESSION['memory_page_id'] ) )
		? get_field( 'video', $_SESSION['memory_page_id'] ) : null;
?>

<div class="droparea droparea-video flex align-end justify-center">
	<div class="droparea-inner flex flex-wrap justify-center<?php echo ( ! empty( $videos ) ? ' hidden' : '' ) ?>">
		<div class="droparea-icon flex justify-center">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<mask id="mask0_4210_3787" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="3" y="6" width="18" height="12">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M17 7V10.5L19.29 8.2C19.92 7.57 21 8.02 21 8.91V15.08C21 15.97 19.92 16.42 19.29 15.79L17 13.5V17C17 17.55 16.55 18 16 18H4C3.45 18 3 17.55 3 17V7C3 6.45 3.45 6 4 6H16C16.55 6 17 6.45 17 7ZM11 13H13C13.55 13 14 12.55 14 12C14 11.45 13.55 11 13 11H11V9C11 8.45 10.55 8 10 8C9.45 8 9 8.45 9 9V11H7C6.45 11 6 11.45 6 12C6 12.55 6.45 13 7 13H9V15C9 15.55 9.45 16 10 16C10.55 16 11 15.55 11 15V13Z" fill="black"/>
				</mask>
				<g mask="url(#mask0_4210_3787)">
					<rect width="24" height="24" fill="#C69A39"/>
				</g>
			</svg>
		</div>
		<div class="droparea-title">
			<?php esc_html_e( 'Перетягніть відеофайл сюди', 'inheart' ) ?>
		</div>
		<div class="droparea-desc">
			<p><?php esc_html_e( 'AVI, MP4, або MPEG до 1 гб', 'inheart' ) ?></p>
		</div>
		<label for="file-video" class="label-button btn sm gray br-24">
			<span class="label-button-text">
				<?php esc_html_e( 'Завантажити з пристрою', 'inheart' ) ?>
			</span>
			<input id="file-video" class="file-video" type="file" name="file-video" accept="video/mp4, video/mpeg, video/x-msvideo, video/avi" />
		</label>
	</div><!-- .droparea-inner -->

	<div class="droparea-videos flex flex-wrap<?php echo ( empty( $videos ) ? ' hidden' : '' ) ?>">
		<?php
		if( ! empty( $videos ) ){
			foreach( $videos as $key => $video )
				get_template_part( 'template-parts/new-memory/step-4/video', null, ['key' => $key, 'video' => $video] );
		}
		?>

		<div class="droparea-videos-load flex direction-column align-center justify-end">
			<div class="droparea-videos-load-inner flex direction-column align-center justify-end">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<mask id="mask0_4210_378" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="3" y="6" width="18" height="12">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M17 7V10.5L19.29 8.2C19.92 7.57 21 8.02 21 8.91V15.08C21 15.97 19.92 16.42 19.29 15.79L17 13.5V17C17 17.55 16.55 18 16 18H4C3.45 18 3 17.55 3 17V7C3 6.45 3.45 6 4 6H16C16.55 6 17 6.45 17 7ZM11 13H13C13.55 13 14 12.55 14 12C14 11.45 13.55 11 13 11H11V9C11 8.45 10.55 8 10 8C9.45 8 9 8.45 9 9V11H7C6.45 11 6 11.45 6 12C6 12.55 6.45 13 7 13H9V15C9 15.55 9.45 16 10 16C10.55 16 11 15.55 11 15V13Z" fill="black"/>
					</mask>
					<g mask="url(#mask0_4210_378)">
						<rect width="24" height="24" fill="#C69A39"/>
					</g>
				</svg>
				<label for="file-video-more" class="label-button btn sm gray br-24">
				<span class="label-button-text">
					<?php esc_html_e( 'Завантажити ще', 'inheart' ) ?>
				</span>
					<input id="file-video-more" class="file-video" type="file" name="file-video-more" />
				</label>
			</div>
		</div>
	</div><!-- .droparea-videos -->

	<div class="droparea-loader flex direction-column align-center justify-end hidden">
		<div class="droparea-loader-percents">
			<span>0</span>%
		</div>
		<div class="droparea-loader-note">
			<?php esc_html_e( 'Завантаження відеофайла...', 'inheart' ) ?>
		</div>
		<progress value="0" max="100"></progress>
		<button class="btn md gray droparea-loader-cancel" type="button">
			<?php esc_html_e( 'Відмінити', 'inheart' ) ?>
		</button>
	</div><!-- .droparea-loader -->

	<div class="droparea-thumbs flex direction-column align-center justify-end hidden">
		<div class="droparea-thumbs-text">
			<?php esc_html_e( 'Виберіть обкладинку для відео', 'inheart' ) ?>
		</div>
		<button class="btn md gray droparea-thumbs-save" type="button">
			<?php esc_html_e( 'Зберегти', 'inheart' ) ?>
		</button>

		<div class="droparea-thumbs-list flex flex-wrap">
			<label class="droparea-thumb-load flex direction-column align-center justify-center" for="file-load-poster">
				<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
					<mask id="mask0_2655" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="2" y="2" width="20" height="20">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M20.52 5.76562H18.5V3.74563C18.5 3.20562 18.06 2.76562 17.52 2.76562H17.49C16.94 2.76562 16.5 3.20562 16.5 3.74563V5.76562H14.49C13.95 5.76562 13.51 6.20563 13.5 6.74563V6.77563C13.5 7.32563 13.94 7.76562 14.49 7.76562H16.5V9.77563C16.5 10.3156 16.94 10.7656 17.49 10.7556H17.52C18.06 10.7556 18.5 10.3156 18.5 9.77563V7.76562H20.52C21.06 7.76562 21.5 7.32562 21.5 6.78562V6.74563C21.5 6.20563 21.06 5.76562 20.52 5.76562ZM15.5 9.77563V8.76562H14.49C13.96 8.76562 13.46 8.55562 13.08 8.18563C12.71 7.80562 12.5 7.30562 12.5 6.74563C12.5 6.38562 12.6 6.05562 12.77 5.76562H4.5C3.4 5.76562 2.5 6.66563 2.5 7.76562V19.7656C2.5 20.8656 3.4 21.7656 4.5 21.7656H16.5C17.6 21.7656 18.5 20.8656 18.5 19.7656V11.4856C18.2 11.6556 17.86 11.7656 17.48 11.7656C16.39 11.7556 15.5 10.8656 15.5 9.77563ZM5.5 19.7656H15.46C15.88 19.7656 16.11 19.2856 15.85 18.9556L12.9 15.2756C12.7 15.0156 12.31 15.0256 12.11 15.2856L9.5 18.7656L7.9 16.3556C7.7 16.0756 7.29 16.0556 7.08 16.3356L5.1 18.9656C4.85 19.2956 5.09 19.7656 5.5 19.7656Z" fill="black"/>
					</mask>
					<g mask="url(#mask0_2655)">
						<rect y="0.265625" width="24" height="24" fill="#C69A39"/>
					</g>
				</svg>
				<span class="droparea-thumb-load-text">
					<?php esc_html_e( 'Завантажити обкладинку', 'inheart' ) ?>
				</span>
				<input id="file-load-poster" class="file-load-poster" type="file" name="file-load-poster" accept="image/jpeg, image/png" />
			</label>
		</div>
	</div><!-- .droparea-thumbs -->
</div><!-- .droparea -->


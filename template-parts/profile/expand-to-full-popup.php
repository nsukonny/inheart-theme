<?php

/**
 * Profile memory expand to full popup.
 *
 * @package WordPress
 * @subpackage inheart
 */
?>

<div id="expand-to-full-popup" class="popup expand-to-full-popup hidden">
	<div class="popup-inner">
		<button class="popup-close" aria-label="<?php esc_attr_e( 'Закрити', 'inheart' ) ?>">
			<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M2.58859 2.71569L2.64645 2.64645C2.82001 2.47288 3.08944 2.4536 3.28431 2.58859L3.35355 2.64645L8 7.293L12.6464 2.64645C12.8417 2.45118 13.1583 2.45118 13.3536 2.64645C13.5488 2.84171 13.5488 3.15829 13.3536 3.35355L8.707 8L13.3536 12.6464C13.5271 12.82 13.5464 13.0894 13.4114 13.2843L13.3536 13.3536C13.18 13.5271 12.9106 13.5464 12.7157 13.4114L12.6464 13.3536L8 8.707L3.35355 13.3536C3.15829 13.5488 2.84171 13.5488 2.64645 13.3536C2.45118 13.1583 2.45118 12.8417 2.64645 12.6464L7.293 8L2.64645 3.35355C2.47288 3.17999 2.4536 2.91056 2.58859 2.71569L2.64645 2.64645L2.58859 2.71569Z" fill="#011C1A"/>
			</svg>
		</button>

		<div class="popup-header flex flex-wrap align-center">
			<div class="popup-header-thumb"></div>
			<div class="popup-header-info">
				<div class="popup-name">
					<div class="popup-name-first"></div>
					<div class="popup-name-last"></div>
				</div>
				<div class="popup-dates flex align-center"></div>
			</div>
		</div>

		<div class="popup-features">
			<div class="popup-features-title">
				<?php esc_html_e( "Повна сторінка пам'яті", 'inheart' ) ?>
			</div>
			<ul class="popup-features-list flex flex-wrap">
				<li class="popup-feature"><?php esc_html_e( 'Головна фотографія', 'inheart' ) ?></li>
				<li class="popup-feature"><?php esc_html_e( 'Епітафія', 'inheart' ) ?></li>
				<li class="popup-feature"><?php esc_html_e( 'Біографія', 'inheart' ) ?></li>
				<li class="popup-feature highlighted"><?php esc_html_e( 'Фотогалерея', 'inheart' ) ?></li>
				<li class="popup-feature"><?php esc_html_e( 'Спогади близьких та друзів', 'inheart' ) ?></li>
				<li class="popup-feature highlighted"><?php esc_html_e( 'Відео та аудіозаписи', 'inheart' ) ?></li>
				<li class="popup-feature highlighted"><?php esc_html_e( 'Посилання на сайти чи соцмережі', 'inheart' ) ?></li>
				<li class="popup-feature highlighted"><?php esc_html_e( 'Точні координати поховання', 'inheart' ) ?></li>
				<li class="popup-feature"><?php esc_html_e( 'QR-код для друку', 'inheart' ) ?></li>
				<li class="popup-feature"><?php esc_html_e( 'Вічне зберігання даних', 'inheart' ) ?></li>
			</ul>
		</div>

		<form class="popup-promo flex flex-wrap align-center">
			<label for="promo" class="label dark">
				<span class="label-text"><?php esc_html_e( 'Промо-код', 'inheart' ) ?></span>
				<input id="promo" name="promo" type="text" placeholder="<?php esc_attr_e( 'Ваш промо-код (якщо маєте)', 'inheart' ) ?>" />
			</label>
			<button class="btn lg secondary" type="submit"><?php esc_html_e( 'Застосувати', 'inheart' ) ?></button>
		</form>

		<div class="popup-total">
			<div class="popup-total-row flex flex-wrap align-center page-price">
				<div class="popup-total-text">
					<?php esc_html_e( "Повна сторінка пам'яті", 'inheart' ) ?>
				</div>
				<div class="popup-total-price">
					<span>3 400</span> <?php esc_html_e( 'грн / рік', 'inheart' ) ?>
				</div>
			</div>
			<div class="popup-total-row flex flex-wrap align-center discount">
				<div class="popup-total-text">
					<?php esc_html_e( 'Знижка', 'inheart' ) ?>
				</div>
				<div class="popup-total-price">
					-<span>550</span> <?php esc_html_e( 'грн', 'inheart' ) ?>
				</div>
			</div>
			<div class="popup-total-row flex flex-wrap align-center final">
				<div class="popup-total-text">
					<?php esc_html_e( 'Усього', 'inheart' ) ?>
				</div>
				<div class="popup-total-price">
					-<span>3 390</span> <?php esc_html_e( 'грн', 'inheart' ) ?>
				</div>
			</div>
		</div>

		<a href="#" class="btn lg primary full">
			<?php esc_html_e( 'До сплати', 'inheart' ) ?>
		</a>
	</div>
</div><!-- #expand-to-full-popup -->


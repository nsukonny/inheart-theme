<?php

/**
 * New Memory page template.
 * Step 6 Military.
 *
 * @see        Page Template: New Memory -> Step 6 (Military)
 *
 * @package    WordPress
 * @subpackage inheart
 */

if( ! $memory_page_id = $_SESSION['memory_page_id'] ?? null ) return;

$is_edit        = isset( $_GET['edit'] ) && $_GET['edit'] == 1;
$image          = get_field( 'page_created_img' );
$text           = get_field( 'page_created_text' );
$text_updated   = get_field( 'page_updated_text' );
$instagram_note = get_field( 'instagram_note' );
$army_type      = get_field( 'army_type', $memory_page_id );
$brigade_type   = get_field( 'brigade_type', $memory_page_id );
$full_price     = ih_get_expanded_page_order_price( 1, true );
?>

<section
	id="new-memory-step-6-military"
	class="new-memory-step new-memory-step-6-military military-created direction-column justify-center"
>
	<div class="container direction-column align-center">
		<h2 class="military-created-title flex justify-center align-center">
			<?php
			if( $is_edit ) _e( "Сторінка пам’яті оновлена", 'inheart' );else _e( "Сторінка пам’яті створена", 'inheart' );
			?>
		</h2>

		<p class="military-created-desc">
			<?php _e( "Тепер ви можете замовити металевий QR-код, через який можна швидко перейти до Сторінки памʼяті. Зазвичай його кріплять на памʼятник", 'inheart' ) ?>
		</p>

		<div class="military-created-qr flex justify-center">
			<a href="/" class="button primary lg button-icon-lead">
				<svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path
						d="M10.6667 11.8333V11.1667H8.66667V9.16667H10.6667V10.5H12V11.8333H11.3333V13.1667H10V14.5H8.66667V12.5H10V11.8333H10.6667ZM14 14.5H11.3333V13.1667H12.6667V11.8333H14V14.5ZM2 2.5H7.33333V7.83333H2V2.5ZM3.33333 3.83333V6.5H6V3.83333H3.33333ZM8.66667 2.5H14V7.83333H8.66667V2.5ZM10 3.83333V6.5H12.6667V3.83333H10ZM2 9.16667H7.33333V14.5H2V9.16667ZM3.33333 10.5V13.1667H6V10.5H3.33333ZM12 9.16667H14V10.5H12V9.16667ZM4 4.5H5.33333V5.83333H4V4.5ZM4 11.1667H5.33333V12.5H4V11.1667ZM10.6667 4.5H12V5.83333H10.6667V4.5Z"
						fill="#08091C"
					/>
				</svg>
				<span class="button-text">
					<?php _e( "Замовити qr-код за $full_price грн", 'inheart' ) ?>
				</span> </a>
		</div>

		<div class="military-created-inner flex direction-column align-center">
			<div class="military-created-info flex direction-column">
				<div class="military-created-top flex flex-wrap justify-between">
					<div class="military-created-thumb">
						<?php
						if( has_post_thumbnail( $memory_page_id ) ) echo get_the_post_thumbnail( $memory_page_id, 'full', ['loading' => 'lazy'] );
						?>
					</div>

					<div class="military-created-dates flex direction-column">
						<div
							class="military-created-date military-created-date-born flex direction-column align-center"
						>
							<div class="military-created-date-decor">❋</div>
							<div class="military-created-date-inner flex align-center">
								<div class="military-created-date-left flex direction-column align-center">
									<span class="military-created-date-day"></span> <span
										class="military-created-date-month"
									></span>
								</div>
								<div class="military-created-date-year"></div>
							</div>
						</div>
						<div
							class="military-created-date military-created-date-died flex direction-column align-center"
						>
							<div class="military-created-date-decor">✢</div>
							<div class="military-created-date-inner flex align-center">
								<div class="military-created-date-left flex direction-column align-center">
									<span class="military-created-date-day"></span> <span
										class="military-created-date-month"
									></span>
								</div>
								<div class="military-created-date-year"></div>
							</div>
						</div>
					</div>
				</div>

				<div class="military-created-fullname">
					<div class="military-created-lastname"></div>
					<div class="military-created-firstname"></div>
					<div class="military-created-fathername"></div>
				</div>

				<div class="military-created-brigade"></div>

				<div class="military-created-army flex flex-wrap align-center">
					<p></p>
				</div>
			</div>

			<a href="/" class="button outlined lg button-icon-lead military-created-share-button" target="_blank">
				<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path
						d="M8.4987 6.50016C7.96827 6.50016 7.45956 6.71088 7.08448 7.08595C6.70941 7.46102 6.4987 7.96973 6.4987 8.50016C6.4987 9.0306 6.70941 9.5393 7.08448 9.91438C7.45956 10.2894 7.96827 10.5002 8.4987 10.5002C9.02913 10.5002 9.53784 10.2894 9.91291 9.91438C10.288 9.5393 10.4987 9.0306 10.4987 8.50016C10.4987 7.96973 10.288 7.46102 9.91291 7.08595C9.53784 6.71088 9.02913 6.50016 8.4987 6.50016ZM8.4987 5.16683C9.38275 5.16683 10.2306 5.51802 10.8557 6.14314C11.4808 6.76826 11.832 7.61611 11.832 8.50016C11.832 9.38422 11.4808 10.2321 10.8557 10.8572C10.2306 11.4823 9.38275 11.8335 8.4987 11.8335C7.61464 11.8335 6.7668 11.4823 6.14168 10.8572C5.51655 10.2321 5.16536 9.38422 5.16536 8.50016C5.16536 7.61611 5.51655 6.76826 6.14168 6.14314C6.7668 5.51802 7.61464 5.16683 8.4987 5.16683V5.16683ZM12.832 5.00016C12.832 5.22118 12.7442 5.43314 12.588 5.58942C12.4317 5.7457 12.2197 5.8335 11.9987 5.8335C11.7777 5.8335 11.5657 5.7457 11.4094 5.58942C11.2532 5.43314 11.1654 5.22118 11.1654 5.00016C11.1654 4.77915 11.2532 4.56719 11.4094 4.41091C11.5657 4.25463 11.7777 4.16683 11.9987 4.16683C12.2197 4.16683 12.4317 4.25463 12.588 4.41091C12.7442 4.56719 12.832 4.77915 12.832 5.00016V5.00016ZM8.4987 3.16683C6.84936 3.16683 6.58003 3.1715 5.8127 3.2055C5.29003 3.23016 4.93936 3.30016 4.61403 3.42683C4.3247 3.53883 4.11603 3.67283 3.89403 3.8955C3.68537 4.0971 3.52493 4.34322 3.4247 4.6155C3.29803 4.94216 3.22803 5.29216 3.20403 5.81416C3.16936 6.55016 3.16536 6.8075 3.16536 8.50016C3.16536 10.1495 3.17003 10.4188 3.20403 11.1862C3.2287 11.7082 3.2987 12.0595 3.4247 12.3842C3.53803 12.6742 3.67136 12.8828 3.8927 13.1042C4.11736 13.3282 4.32603 13.4622 4.6127 13.5728C4.94203 13.7002 5.2927 13.7708 5.8127 13.7948C6.5487 13.8295 6.80603 13.8335 8.4987 13.8335C10.148 13.8335 10.4174 13.8288 11.1847 13.7948C11.706 13.7702 12.0574 13.7002 12.3827 13.5742C12.6714 13.4615 12.8814 13.3275 13.1027 13.1062C13.3274 12.8815 13.4614 12.6728 13.572 12.3862C13.6987 12.0575 13.7694 11.7062 13.7934 11.1862C13.828 10.4502 13.832 10.1928 13.832 8.50016C13.832 6.85083 13.8274 6.5815 13.7934 5.81416C13.7687 5.29283 13.6987 4.94083 13.572 4.6155C13.4716 4.3435 13.3114 4.09747 13.1034 3.8955C12.9019 3.68673 12.6557 3.52627 12.3834 3.42616C12.0567 3.2995 11.706 3.2295 11.1847 3.2055C10.4487 3.17083 10.1914 3.16683 8.4987 3.16683ZM8.4987 1.8335C10.31 1.8335 10.536 1.84016 11.2467 1.8735C11.9567 1.90683 12.44 2.01816 12.8654 2.1835C13.3054 2.35283 13.676 2.58216 14.0467 2.95216C14.3857 3.28543 14.648 3.68856 14.8154 4.1335C14.98 4.55816 15.092 5.04216 15.1254 5.75216C15.1567 6.46283 15.1654 6.68883 15.1654 8.50016C15.1654 10.3115 15.1587 10.5375 15.1254 11.2482C15.092 11.9582 14.98 12.4415 14.8154 12.8668C14.6485 13.312 14.3861 13.7152 14.0467 14.0482C13.7133 14.387 13.3102 14.6493 12.8654 14.8168C12.4407 14.9815 11.9567 15.0935 11.2467 15.1268C10.536 15.1582 10.31 15.1668 8.4987 15.1668C6.68736 15.1668 6.46136 15.1602 5.7507 15.1268C5.0407 15.0935 4.55736 14.9815 4.13203 14.8168C3.68692 14.6498 3.28372 14.3875 2.9507 14.0482C2.61164 13.715 2.34932 13.3118 2.18203 12.8668C2.0167 12.4422 1.90536 11.9582 1.87203 11.2482C1.8407 10.5375 1.83203 10.3115 1.83203 8.50016C1.83203 6.68883 1.8387 6.46283 1.87203 5.75216C1.90536 5.0415 2.0167 4.55883 2.18203 4.1335C2.34886 3.68828 2.61123 3.28504 2.9507 2.95216C3.28381 2.61298 3.68698 2.35065 4.13203 2.1835C4.55736 2.01816 5.04003 1.90683 5.7507 1.8735C6.46136 1.84216 6.68736 1.8335 8.4987 1.8335Z"
						fill="#08091C"
					/>
				</svg>
				<span class="button-text"><?php _e( 'Поділитись сторінкою в Instagram', 'inheart' ) ?></span> </a>

			<div class="military-created-link">
				<p class="military-created-link-desc"><?php _e( 'Посилання скопійоване в буфер обміну', 'inheart' ) ?></p>
				<a class="military-created-link-url" href="/"></a>
			</div>
		</div>
	</div>

	<?php
	if( $instagram_note ){
		?>
		<div id="instagram-popup" class="popup instagram-popup hidden">
			<div class="popup-inner flex flex-wrap">
				<div class="coords-popup-text">
					<?php echo $instagram_note ?>
				</div>
				<button class="coords-popup-close" type="button" aria-label="<?php _e( 'Закрити', 'inheart' ) ?>">
					<?php _e( 'Закрити', 'inheart' ) ?>
				</button>
			</div>
		</div>
		<?php
	}
	?>
</section><!-- #new-memory-step-6-military -->


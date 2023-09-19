<?php

/**
 * Tariff page template.
 * Single plan.
 *
 * @package WordPress
 * @subpackage inheart
 */

$price_year			= get_field( 'price_per_year' );
$price_ten_years	= get_field( 'price_per_ten_years' );
?>

<button
	class="tariff-plan"
	type="button"
	data-price="<?php echo esc_attr( number_format( $price_year, 0, '', ' ' ) ) ?>"
	data-price-ten="<?php echo esc_attr( number_format( $price_ten_years, 0, '', ' ' ) ) ?>"
	data-qr-metal-price="<?php echo esc_attr( get_field( 'qr_metal_price' ) ) ?>"
>
	<span class="tariff-plan-header flex flex-wrap align-end">
		<span class="tariff-plan-title">
			<?php the_title() ?>
		</span>

		<?php
		if( get_field( 'is_optimal' ) )
			echo '<span class="tariff-plan-optimal">' . esc_html__( 'Оптимальна', 'inheart' ) . '</span>';
		?>

		<span class="tariff-plan-price">
			<span class="tariff-plan-price-amount"><?php echo number_format( $price_year, 0, '', ' ' ) ?></span>
			<span class="tariff-plan-price-currency"><?php esc_html_e( 'грн / рік', 'inheart' ) ?></span>
		</span>
	</span>

	<span class="tariff-plan-features flex flex-wrap">
		<?php
		if( get_field( 'main_image' ) )
			echo '<span class="tariff-plan-feature main-image">' . __( 'Головна фотографія', 'inheart' ) . '</span>';

		if( get_field( 'epitaph' ) )
			echo '<span class="tariff-plan-feature epitaph">' . __( 'Епітафія', 'inheart' ) . '</span>';

		if( get_field( 'biography' ) )
			echo '<span class="tariff-plan-feature biography">' . __( 'Біографія', 'inheart' ) . '</span>';

		if( ! get_field( 'full_photogallery' ) ){
			echo '<span class="tariff-plan-feature available-images">'
				. sprintf( __( '%d додаткових фотокартки', 'inheart' ), ( get_field( 'available_images_count' ) ?: 3 ) ) .
			'</span>';
		}else{
			echo '<span class="tariff-plan-feature highlighted full-photogallery">' . __( 'Фотогалерея', 'inheart' ) . '</span>';
		}

		if( get_field( 'memories' ) )
			echo '<span class="tariff-plan-feature memories">' . __( 'Спогади близьких та друзів', 'inheart' ) . '</span>';

		if( get_field( 'video_audio' ) )
			echo '<span class="tariff-plan-feature highlighted video-audio">' . __( 'Відео та аудіозаписи', 'inheart' ) . '</span>';

		if( get_field( 'links' ) )
			echo '<span class="tariff-plan-feature highlighted links">' . __( 'Посилання на сайти чи соцмережі', 'inheart' ) . '</span>';

		if( get_field( 'exact_coordinates' ) )
			echo '<span class="tariff-plan-feature highlighted coordinates">' . __( 'Точні координати поховання', 'inheart' ) . '</span>';

		if( get_field( 'qr_code_metal' ) ){
			echo '<span class="tariff-plan-feature highlighted qr-metal">' . __( 'QR-код на металевій пластині', 'inheart' ) . '</span>';
		}else{
			if( get_field( 'qr_code' ) )
				echo '<span class="tariff-plan-feature qr">' . __( 'QR-код для друку', 'inheart' ) . '</span>';
		}

		if( get_field( 'eternal_data_storage' ) )
			echo '<span class="tariff-plan-feature eternal">' . __( 'Вічне зберігання даних', 'inheart' ) . '</span>';

		if( get_field( 'epitaph_writing' ) )
			echo '<span class="tariff-plan-feature highlighted epitaph-writing">' . __( 'Написання епітафії та біографії професійним автором', 'inheart' ) . '</span>';
		?>
	</span>
</button>


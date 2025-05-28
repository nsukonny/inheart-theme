<?php

/**
 * Template name: Successful Payment
 *
 * @package WordPress
 * @subpackage inheart
 */


$title			= get_field( 'title_succesfull_payment' );
$subTitle		= get_field( 'subTitle_succesfull_payment' );
$description	= get_field( 'description_succesfull_payment' );
$image          = get_field( 'image_succesfull_payment' );
$button_text	= get_field( 'button_text_succesfull_payment' ) ?: 'Зареєструватися / увійти в акаунт';

wp_enqueue_style( 'payment-succesfull-styles', THEME_URI . '/static/css/pages/succesfull-payment.min.css', [], THEME_VERSION );
?>

<?php wp_head(); ?>

<!doctype html>
<html class="no-js" <?php language_attributes() ?>>
<head>
	<?php if( $gtag_head ) echo $gtag_head ?>

	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-KZXZ4PTX');</script>
	<!-- End Google Tag Manager -->

	<meta charset="<?php bloginfo( 'charset' ) ?>" />
	<meta http-equiv="x-ua-compatible" content="ie=edge" />
	<meta content="" name="description" />
	<meta content="" name="keywords" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<meta content="telephone=no" name="format-detection" />
	<meta name="HandheldFriendly" content="true" />

	<title>
		<?php
		global $page, $paged;

		wp_title( '|', true, 'right' );
		bloginfo( 'name' );
		$site_description = get_bloginfo( 'description', 'display' );

		if( $site_description && ( is_home() || is_front_page() ) ) echo " | $site_description";

		if( $paged >= 2 || $page >= 2 ) echo ' | ' . sprintf( __( 'Page %s', 'inheart' ), max( $paged, $page ) );
		?>
	</title>

	<style>
        .notification-popup {
            display: none;
            position: fixed;
			top: 20px;
			right: 0px;
			left: 0px;
			margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            max-width: 380px;
            animation: slideIn 0.3s ease-out;
        }
        
        .notification-popup__title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
        
        .notification-popup__text {
            font-size: 14px;
            color: #666;
            line-height: 1.4;
        }
        
        .notification-popup__close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            color: #999;
            font-size: 20px;
            line-height: 1;
        }

		@keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>



<main class="main successful-payment">
	<div class="notification-popup" id="notificationPopup">
        <span class="notification-popup__close" onclick="this.parentElement.style.display='none'">&times;</span>
        <div class="notification-popup__title">Акаунт успішно створено!</div>
        <div class="notification-popup__text">
            Ваш обліковий запис успішно зареєстровано. Дані для входу (email та пароль) надіслано на вашу електронну пошту.
        </div>
    </div>
	<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Показываем popup
            document.getElementById('notificationPopup').style.display = 'block';
            
            // Автоматически скрываем через 10 секунд
            setTimeout(function() {
                document.getElementById('notificationPopup').style.display = 'none';
            }, 10000);
        });
    </script>
	<div class="container">
		<div class="successful-payment__content">
			<!-- Success Image -->
			<div class="successful-payment__image">
				<img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
			</div>

			<!-- Main Text -->
			<h1 class="successful-payment__title">
				<?php echo esc_html($title); ?>
			</h1>
			<p class="successful-payment__subtitle">
				<?php echo esc_html($subTitle); ?>
			</p>

			<!-- Description -->
			<div class="successful-payment__description">
				<p>
					<?php echo wp_kses_post($description); ?>
				</p>
			</div>

			<!-- Action Button -->
			<div class="payment-submit-container full flex justify-center">
				<a href="<?php echo home_url('/login'); ?>" class="btn lg primary btn-primary">
					<?php echo esc_html($button_text); ?>
				</a>
			</div>
		</div>
	</div>
</main>
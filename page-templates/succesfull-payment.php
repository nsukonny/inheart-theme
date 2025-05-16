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
?>


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
</head>



<main class="main successful-payment">
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

<style>
.successful-payment {
	padding: 0px;
    margin-top: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: calc(100vh - 200px);
}

.successful-payment__content {
	max-width: 650px;
	margin: 0 auto;
	text-align: center;
	padding: 0 20px;
    width: 100%;
}

.successful-payment__image {
    max-width: 500px;
    margin: auto;
    text-align: start;
}

.successful-payment__image img {
	max-width: 350px;
	width: 100%;
	height: auto;
	margin: 0px auto;
}

.successful-payment__title {
	font-size: 40px;
	font-weight: 700;
	color: #1A1A1A;
	margin: 0px auto;
	line-height: 1.2;
    font-family:"UAF Memory",Arial,sans-serif
}

.successful-payment__subtitle {
	font-size: 40px;
	font-weight: 700;
	margin-bottom: 16px;
	color: #1A1A1A;
	margin: 0px auto 16px;
	line-height: 1.2;
    font-family:"UAF Memory",Arial,sans-serif
}

.successful-payment__description {
	font-size: 18px;
	line-height: 1.6;
	color: #011C1A;
	margin-bottom: 60px;
	max-width: 500px;
	margin-left: auto;
	margin-right: auto;
    font-family:"UAF Memory",Arial,sans-serif
}

.payment-submit-container {
	margin-top: 40px;
}

.btn-primary {
	display: inline-block;
	padding: 16px 32px;
	background-color: #c69a39;
	color: #011c1a;
	text-decoration: none;
	border-radius: 8px;
	transition: background-color 0.3s ease;
    border: none;
    font-family: "Grafita-Normal", Arial, sans-serif;
    font-weight: 400;
    font-size: 14px;
    line-height: 120%;
    letter-spacing: 0px;
    text-align: center;
    vertical-align: middle;
    max-width:405px;
    width: 100%;
}

.btn-primary:hover {
	background-color: #f7b941;
}

/* Large screens */
@media (min-width: 1200px) {
    .successful-payment__content {
        max-width: 650px;
    }
}

/* Tablets and smaller desktops */
@media (max-width: 1199px) {
    .successful-payment__content {
        max-width: 600px;
    }
}

/* Tablets */
@media (max-width: 991px) {
    .successful-payment {
        margin-top: 32px;
    }



    .successful-payment__image {
        max-width: 400px;
        margin: auto;
        text-align: start;
    }

    .successful-payment__image img {
        max-width: 300px;
        width: 100%;
        height: auto;
        margin: 0px auto;
    }

    .successful-payment__title,
    .successful-payment__subtitle {
        font-size: 28px;
    }

    .successful-payment__description {
        font-size: 16px;
        max-width: 450px;
    }

    .btn-primary{
        max-width:405px;
    }
}

/* Large phones */
@media (max-width: 767px) {
    .successful-payment {
        margin-top: 24px;
        padding: 0 16px;
    }

    .successful-payment__content {
        padding: 0;
        width: 100%;
    }

    .successful-payment__image {
        max-width: 300px;
        margin: auto;
        text-align: start;
    }

    .successful-payment__image img {
        max-width: 250px;
        width: 100%;
        height: auto;
        margin: 0px auto;
    }

    .successful-payment__title,
    .successful-payment__subtitle {
        font-size: 30px;
    }

    .successful-payment__description {
        font-size: 16px;
        margin-bottom: 32px;
        /* max-width: 100%; */
        max-width: 450px;
    }

    .payment-submit-container {
        margin-top: 32px;
    }

    .btn-primary {
        padding: 14px 28px;
        max-width:350px;
    }
}

/* Small phones */
@media (max-width: 480px) {
    .successful-payment {
        margin-top: 16px;
        padding: 0 12px;
    }

    .successful-payment__content {
        padding: 0;
        width: 100%;
    }

    .successful-payment__image {
        max-width: 250px;
        margin: auto;
        text-align: start;
    }

    .successful-payment__image img {
        max-width: 200px;
        width: 100%;
        height: auto;
        margin: 0px auto;
    }

    .successful-payment__title,
    .successful-payment__subtitle {
        font-size: 20px;
    }

    .successful-payment__description {
        font-size: 14px;
        margin-bottom: 24px;
        line-height: 1.5;
    }

    .payment-submit-container {
        margin-top: 24px;
    }

    .btn-primary {
        padding: 12px 24px;
        font-size: 14px;
        max-width:250px;
    }
}

</style>



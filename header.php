<?php

/**
 * Header default template.
 *
 * @see Options -> Header.
 *
 * @package WordPress
 * @subpackage inheart
 */

$page_theme		= get_field( 'page_theme' ) ?: 'light';
$page_theme		= is_singular( 'memory_page' ) ? 'dark' : $page_theme;
$memory_theme	= is_singular( 'memory_page' ) ? get_field( 'theme' ) : '';
$logo_only		= get_field( 'logo_only' );
?>

<!doctype html>
<html class="no-js" <?php language_attributes() ?>>
<head>
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

	<script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>
	<?php wp_head() ?>
</head>

<body <?php body_class( "theme-$page_theme $memory_theme" ) ?>>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe
		src="https://www.googletagmanager.com/ns.html?id=GTM-KZXZ4PTX"
		height="0"
		width="0"
		style="display:none;visibility:hidden"
	></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

	<?php wp_body_open() ?>

	<div class="wrapper">
		<?php
		// Use different layout for the Profile pages.
		if(
			! is_page_template( 'page-templates/profile.php' ) &&
			! is_page_template( 'page-templates/profile-settings.php' ) &&
			! is_page_template( 'page-templates/profile-memories.php' )
		){
			if( $logo_only ) get_template_part( 'template-parts/header/logo-only' );
			else get_template_part( 'template-parts/header/full' );
		}
		?>


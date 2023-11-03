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
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-NRBKM3VLV8"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'G-NRBKM3VLV8');
	</script>
	<!-- End of Google tag (gtag.js) -->

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


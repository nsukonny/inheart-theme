<?php

/**
 * Header default template.
 *
 * @see Options -> Header.
 *
 * @package WordPress
 * @subpackage inheart
 */

$gtag			= get_field( 'google_tag_head' );
$page_theme		= get_field( 'page_theme' ) ?: 'light';
$page_theme		= is_singular( 'memory_page' ) ? 'dark' : $page_theme;
$memory_theme	= is_singular( 'memory_page' ) ? get_field( 'theme' ) : '';
?>

<!doctype html>
<html class="no-js" <?php language_attributes() ?>>
<head>
	<?php if( $gtag ) echo $gtag ?>

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
		?>
	</title>

	<script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>
	<?php wp_head() ?>
</head>

<body <?php body_class( "theme-$page_theme $memory_theme" ) ?>>
	<?php wp_body_open() ?>

	<div class="wrapper">


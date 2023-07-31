<?php

/**
 * Header default template.
 *
 * @see Options -> Header.
 *
 * @package WordPress
 * @subpackage inheart
 */

$page_theme	= get_field( 'page_theme' ) ?: 'light';
$logo_only	= get_field( 'logo_only' );
?>

<!doctype html>
<html class="no-js" <?php language_attributes() ?>>
<head>
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

	<!-- FAVICON -->
	<!-- /FAVICON -->

	<script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>
	<?php wp_head() ?>
</head>

<body <?php body_class( "theme-$page_theme" ) ?>>
	<?php wp_body_open() ?>

	<div class="wrapper">
		<?php
		$binaries_arr = [
			'ffmpeg.binaries'  => THEME_DIR . '/lib-php/ffmpeg.exe',
			'ffprobe.binaries' => THEME_DIR . '/lib-php/ffprobe.exe'
		];
		$ffprobe	= FFMpeg\FFProbe::create( $binaries_arr );
		$duration	= ( int ) $ffprobe->format( get_attached_file( 181 ) )->get( 'duration' );
		echo 'test - ', $duration;

		if( $logo_only ) get_template_part( 'template-parts/header/logo-only' );
		else get_template_part( 'template-parts/header/full' );
		?>

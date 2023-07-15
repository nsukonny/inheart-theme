<?php

/**
 * Theme functions.
 *
 * @package WordPress
 * @subpackage inheart
 */

const THEME_NAME = 'inheart';
define( 'THEME_URI', get_template_directory_uri() );
define( 'THEME_DIR', get_template_directory() );
define( 'THEME_VERSION', mt_rand() );

add_action( 'after_setup_theme', 'ih_load_theme_dependencies' );
/**
 * Theme dependencies.
 */
function ih_load_theme_dependencies(): void
{
	// Register theme menus.
	register_nav_menus( [
		'header_menu'	=> esc_html__( 'Header Menu', 'inheart' ),
		'footer_menu'	=> esc_html__( 'Footer Menu', 'inheart' )
	] );

	require_once( 'theme-functions/acf-fc-templates-generator.php' );	// Auto-generate ACF Flexible Content templates files.
	require_once( 'theme-functions/theme-functions.php' );	// Please place all custom functions declarations in this file.
	require_once( 'theme-functions/auth.php' );	// Authorization.
	require_once( 'theme-functions/new-memory-creation.php' );	// Create new memory.
}

add_action( 'init', 'ih_init_theme' );
/**
 * Theme initialization.
 */
function ih_init_theme(): void
{
	// Remove extra styles and default SVG tags.
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
	remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

	// Enable post thumbnails.
	add_theme_support( 'post-thumbnails' );

	// Custom image sizes.
	add_image_size( 'ih-logo', 98 );
	add_image_size( 'ih-content-half', 413 );
	add_image_size( 'ih-content-full', 845 );
	add_image_size( 'ih-additional-material', 285, 151 );
	add_image_size( 'ih-profile-media', 305, 240 );
	add_image_size( 'ih-illustration', 490 );
	add_image_size( 'ih-illustration-alt', 571 );
	add_image_size( 'ih-theme', 197, 197 );

	require_once( 'theme-functions/custom-post-types.php' );
}

add_action( 'wp_enqueue_scripts', 'ih_inclusion_enqueue' );
/**
 * Enqueue styles and scripts.
 */
function ih_inclusion_enqueue(): void
{
	// Remove Gutenberg styles on front-end.
	if( ! is_admin() ){
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-blocks-style' );
		wp_dequeue_style( 'classic-theme-styles' );
	}

	// Styles.
	wp_enqueue_style( 'main', THEME_URI . '/static/css/main.min.css', [], THEME_VERSION, 'all' );

	// Scripts.
	wp_enqueue_script( 'scripts', THEME_URI . '/static/js/main.min.js', ['jquery'], THEME_VERSION, true );
}

add_action( 'acf/init', 'ih_acf_init' );
/**
 * ACF add Theme Settings.
 *
 * @return void
 */
function ih_acf_init(): void
{
	$acf_parent_options = null;

	// Add ACF Options Page.
	if( function_exists( 'acf_add_options_page' ) ){
		$acf_parent_options = acf_add_options_page( [
			'page_title' 	=> 'Theme Settings',
			'menu_title'	=> 'Theme Settings',
			'menu_slug' 	=> 'theme_settings',
			'capability'	=> 'edit_posts',
			'redirect'		=> true
		] );
	}

	// Options sub-pages.
	if( function_exists( 'acf_add_options_sub_page' ) && $acf_parent_options ){
		acf_add_options_sub_page( [
			'page_title' 	=> __( 'Global' ),
			'menu_title'	=> __( 'Global' ),
			'parent_slug'	=> $acf_parent_options['menu_slug']
		] );

		acf_add_options_sub_page( [
			'page_title' 	=> __( 'Header' ),
			'menu_title'	=> __( 'Header' ),
			'parent_slug'	=> $acf_parent_options['menu_slug']
		] );

		acf_add_options_sub_page( [
			'page_title' 	=> __( 'Footer' ),
			'menu_title'	=> __( 'Footer' ),
			'parent_slug'	=> $acf_parent_options['menu_slug']
		] );

		acf_add_options_sub_page( [
			'page_title' 	=> __( 'Email Templates' ),
			'menu_title'	=> __( 'Email Templates' ),
			'parent_slug'	=> $acf_parent_options['menu_slug']
		] );
	}
}

add_action( 'wp_head', 'ih_js_vars_for_frontend' );
/**
 * JS variables for frontend, such as AJAX URL. Available in JS via window.wpData object.
 * @example window.wpData.ajaxUrl
 *
 * @return void
 */
function ih_js_vars_for_frontend(): void
{
	$variables = ['ajaxUrl' => admin_url( 'admin-ajax.php' )];
	echo '<script type="text/javascript">window.wpData = ' . json_encode( $variables ) . ';</script>';
}


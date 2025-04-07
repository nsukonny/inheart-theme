<?php

/**
 * Theme functions.
 *
 * @package WordPress
 * @subpackage inheart
 */

require 'vendor/autoload.php';

const THEME_NAME = 'inheart';
define( 'THEME_VERSION', mt_rand() );
define( 'THEME_URI', get_template_directory_uri() );
define( 'THEME_DIR', get_template_directory() );

add_action( 'after_setup_theme', 'ih_load_theme_dependencies' );
/**
 * Theme dependencies.
 */
function ih_load_theme_dependencies(): void
{
	// Register theme menus.
	register_nav_menus( [
		'header_menu'			=> esc_html__( 'Меню Хедеру', 'inheart' ),
		'footer_menu'			=> esc_html__( 'Меню Футеру', 'inheart' ),
		'footer_bottom_menu'	=> esc_html__( 'Меню знизу футеру', 'inheart' ),
		'profile_sidebar_menu'	=> esc_html__( 'Меню профілю', 'inheart' ),
	] );

	// Hide admin bar for everyone on the frontend.
	if( ! is_admin() ) show_admin_bar( false );
	else require_once( 'theme-functions/admin-functions.php' );

	require_once( 'theme-functions/acf-fc-templates-generator.php' );	// Auto-generate ACF Flexible Content templates files.
	require_once( 'theme-functions/theme-functions.php' );	// Please place all custom functions declarations in this file.
	require_once( 'theme-functions/auth.php' );	// Authorization.
	require_once( 'theme-functions/new-memory-creation.php' );	// Create new memory.
	require_once( 'theme-functions/profile-actions.php' );	// Profile pages functions.
	require_once( 'theme-functions/order-actions.php' );	// Order functions.
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
	add_image_size( 'ih-icon', 16, 16 );
	add_image_size( 'ih-logo-mobile', 41 );
	add_image_size( 'ih-logo', 98 );
	add_image_size( 'ih-theme', 197, 197 );
	add_image_size( 'ih-smartphone', 252 );
	add_image_size( 'ih-additional-material', 285, 151 );
	add_image_size( 'ih-profile-media', 305, 240 );
	add_image_size( 'ih-memory-photo', 306, 460 );
	add_image_size( 'ih-content-half', 413 );
	add_image_size( 'ih-illustration', 490 );
	add_image_size( 'ih-illustration-alt', 571 );
	add_image_size( 'ih-content-full', 845 );

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
	wp_enqueue_style( 'main', THEME_URI . '/static/css/main.min.css', [], THEME_VERSION );

	// Scripts.
	wp_enqueue_script( 'scripts', THEME_URI . '/static/js/main.min.js', ['jquery'], THEME_VERSION, true );

	/**
	 * Additional pages.
	 */
    if( is_singular( 'memory_page' ) ){
        wp_enqueue_style( 'memory', THEME_URI . '/static/css/pages/memory.min.css', [], THEME_VERSION );
        wp_enqueue_script( 'memory', THEME_URI . '/static/js/single-memory/single-memory.min.js', ['jquery'], THEME_VERSION, true );

        if (get_field('theme') == 'military'){
            wp_enqueue_script('memory-military', THEME_URI . '/static/js/single-memory-military/single-memory-military.min.js', ['jquery'], THEME_VERSION, true);
            wp_enqueue_style('mapbox-gl-style', 'https://api.mapbox.com/mapbox-gl-js/v3.1.2/mapbox-gl.css');
            wp_enqueue_script('mapbox-gl-script', 'https://api.mapbox.com/mapbox-gl-js/v3.1.2/mapbox-gl.js', array(), null, false);
            wp_enqueue_script('mapbox-geocoder-script', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.min.js', array(), null, false);
            wp_enqueue_style('mapbox-geocoder-style', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.css');
        }
    }
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
			'page_title' 	=> 'Global',
			'menu_title'	=> 'Global',
			'parent_slug'	=> $acf_parent_options['menu_slug']
		] );

		acf_add_options_sub_page( [
			'page_title' 	=> 'Header',
			'menu_title'	=> 'Header',
			'parent_slug'	=> $acf_parent_options['menu_slug']
		] );

		acf_add_options_sub_page( [
			'page_title' 	=> 'Footer',
			'menu_title'	=> 'Footer',
			'parent_slug'	=> $acf_parent_options['menu_slug']
		] );

		acf_add_options_sub_page( [
			'page_title' 	=> 'Email Templates',
			'menu_title'	=> 'Email Templates',
			'parent_slug'	=> $acf_parent_options['menu_slug']
		] );

		acf_add_options_sub_page( [
			'page_title' 	=> 'Pages',
			'menu_title'	=> 'Pages',
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

/**
 * Rewrite all redirects from home page to profile page.
 *
 * @param string $location The path or URL to redirect to.
 *
 * @return string|false
 */
function rewrite_redirect_to_profile(string $location): bool|string
{
    if (home_url() === $location) {
        return get_the_permalink(pll_get_post(ih_get_profile_page_id()));
    }

    return $location;
}

add_filter('wp_redirect', 'rewrite_redirect_to_profile');

/**
 * Adds a live chat bot script to the footer of the website.
 *
 * This function outputs a script tag that loads a live chat bot from an external source.
 * The script is loaded asynchronously to avoid blocking page rendering.
 *
 * @return void
 */
function add_live_chat_bot() {
    ?>
    <script src="https://cdn.pulse.is/livechat/loader.js" data-live-chat-id="67dbfd8379266126cf05a4f3" async></script>
    <?php
}

add_action( 'wp_footer', 'add_live_chat_bot' );

function add_live_chat_bot_custom_btn() {
    ?>
    <style>
        .contact-btn-custom {
            position: fixed;
            top: 50%;
            right: -80px;
            transform: translateY(-50%) rotate(-90deg);
            transform-origin: center center;
            padding: 0 15px;
            background-color: rgb(1, 28, 26);
            color: rgb(255, 255, 255);
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            line-height: 30px;
            height: 34px;
            border-radius: 5px 5px 0 0;
            box-shadow: 0 0 3px #9b9b9b;
            user-select: none;
            white-space: nowrap;
            -webkit-font-smoothing: antialiased;
            font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Oxygen, Ubuntu, Cantarell, Open Sans, Helvetica Neue, sans-serif;
            text-align: center;
            -webkit-appearance: none;
            transition: transform 0.15s ease-in-out;
            margin: 0 10px;
            z-index: 9999;
        }

        .contact-btn-custom:hover {
            transform: translateY(-50%) rotate(-90deg) scale(1.05);
        }
    </style>

    <button type="button" id="openChat" class="contact-btn-custom" title="Зворотній зв'язок" tabindex="0">Зворотній зв'язок</button>
    <?php
}

add_action('wp_footer', 'add_live_chat_bot_custom_btn');

function add_live_chat_bot_custom_btn_logic() {
	?>
	<script>
        document.addEventListener('spLiveChatLoaded', function () {
            const chatElement = document.querySelector('sp-live-chat');
            const openChatBtn = document.getElementById("openChat");
        
            // Utility: wait for element inside shadowRoot
            function waitForShadowElement(selector, callback) {
                const interval = setInterval(() => {
                    if (chatElement?.shadowRoot) {
                        const el = chatElement.shadowRoot.querySelector(selector);
                        if (el) {
                            clearInterval(interval);
                            callback(el);
                        }
                    }
                }, 50);
            }
        
            // Hide the default floating chat button when available
            waitForShadowElement('.widget-fab', (widgetFab) => {
                widgetFab.style.display = 'none';
            });
        
            // Handle custom button click
            openChatBtn.addEventListener("click", function () {
                waitForShadowElement('.widget-fab', (widgetFab) => {
                    widgetFab.click();               // Open the chat
                    widgetFab.style.display = 'none'; // Hide system button again if it reappears
					openChatBtn.style.display = 'none'; // Hide custom button
                    // Wait for close button to appear and bind handler once
                    waitForShadowElement('.button-close', (closeBtn) => {
                        closeBtn.addEventListener('click', () => {
							openChatBtn.style.display = 'block'; // Show custom button again
                            waitForShadowElement('.widget-fab', (fab) => {
                                fab.style.display = 'none'; // Hide default chat button again
                            });
                        }, { once: true });
                    });
                });
            });
        });
    </script>
		<?php
}

add_action( 'wp_footer', 'add_live_chat_bot_custom_btn_logic' );
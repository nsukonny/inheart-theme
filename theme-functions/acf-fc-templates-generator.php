<?php

/**
 * Generates ACF Flexible Content layouts templates
 * when page with ACF Flexible Content template is saved.
 */

add_action( 'updated_post_meta', 'markupus_save_acf_flexible_content', 10, 4 );
/**
 * Function for `updated_post_meta` action-hook.
 */
function markupus_save_acf_flexible_content( $meta_id, $post_ID, $meta_key = '', $meta_value = '' ): void
{
	$template_name = get_post_meta( $post_ID, '_wp_page_template' );

	// Page template needs to be ACF Flexible Content.
	if(
		! $template_name ||
		( $template_name[0] !== 'page-templates/acf-flexible-content.php' ) ||
		! ( $meta = get_post_meta( $post_ID ) )
	) return;

	$fc = false;

	// Loop through all page meta fields to find flexible_content.
	foreach( $meta as $key => $value ){
		if( $key === 'flexible_content' ){
			$fc = true;
			break;
		}
	}

	if( ! $fc ) return;

	// Loop through flexible_content repeater.
	while( have_rows( 'flexible_content' ) ){
		the_row();

		if( ! $layout_name = get_row_layout() ) continue;

		$template_dir = THEME_DIR . "/acf-flexible-content/{$layout_name}";

		// If such directory already exists - go next.
		if( file_exists( $template_dir ) ) continue;

		markupus_render_php_template( $post_ID, $layout_name, $template_dir );
		markupus_render_scss_template( $layout_name, $template_dir );
		markupus_render_js_template( $layout_name, $template_dir );
	}
}

/**
 * Render PHP template file for ACF Flexible Content layout.
 *
 * @param	int			$post_ID		Saved page ID.
 * @param	string		$layout_name	ACF FC layout name (slug), e.g. 'hero_section'.
 * @param	string		$template_dir	Path to template directory inside acf-flexible-content directory.
 * @return	bool|null
 */
function markupus_render_php_template( int $post_ID, string $layout_name, string $template_dir ): ?bool
{
	if( ! $layout_name ) return null;

	$template_php = "{$template_dir}/{$layout_name}.php";

	// If such layout already has template OR can't create file.
	if( ! wp_mkdir_p( $template_dir ) ) return null;

	$layout_name_human	= ucwords( str_replace( '_', ' ', $layout_name ) );
	$layout_name_hyphen	= str_replace( '_', '-', $layout_name );
	$php_file_content	= '<?php' . PHP_EOL . PHP_EOL .
		'/**' . PHP_EOL .
		' * ' . $layout_name_human . ' - ACF Flexible Content section layout.' . PHP_EOL .
		' *' . PHP_EOL .
		' * @see Page with ACF Flexible Content template -> Flexible Content -> ' . $layout_name_human . PHP_EOL .
		' *' . PHP_EOL .
		' * @package WordPress' . PHP_EOL .
		' * @subpackage ' . THEME_NAME . PHP_EOL .
		' */' . PHP_EOL . PHP_EOL .
		'wp_enqueue_style( "' . $layout_name_hyphen . '", THEME_URI . "/static/css/' . $layout_name . '/' . $layout_name . '.min.css", [], THEME_VERSION );' . PHP_EOL .
		'wp_enqueue_script( "' . $layout_name_hyphen . '", THEME_URI . "/static/js/' . $layout_name . '/' . $layout_name . '.min.js", [], THEME_VERSION, true );' . PHP_EOL . PHP_EOL;

	// Get all ACF fields for this page.
	$all_fields = get_fields( $post_ID );

	// Go through flexible_content sections only.
	foreach( $all_fields['flexible_content'] as $obj ){
		// If this is not current layout - go to the next one.
		if( $obj['acf_fc_layout'] !== $layout_name ) continue;

		// When current layout is found - loop through its subfields.
		foreach( $obj as $name => $field ){
			// Skip layout name field.
			if( $name === 'acf_fc_layout' ) continue;

			// Put every subfield inside template file.
			$php_file_content .= "\${$name} = get_sub_field( '{$name}' );" . PHP_EOL;
		}

		break;
	}

	// Add starter HTML.
	$php_file_content .= '?>' . PHP_EOL . PHP_EOL . '<section class="' . $layout_name_hyphen . '"></section>' . PHP_EOL . PHP_EOL;

	// Create PHP template file for this layout.
	if( ! file_put_contents( $template_php, $php_file_content ) ) return false;

	return true;
}

/**
 * Render SCSS template file for ACF Flexible Content layout.
 *
 * @param	string		$layout_name	ACF FC layout name (slug), e.g. 'hero_section'.
 * @param	string		$template_dir	Path to template directory inside acf-flexible-content directory.
 * @return	bool|null
 */
function markupus_render_scss_template( string $layout_name, string $template_dir ): ?bool
{
	if( ! $layout_name ) return null;

	$template_scss = "{$template_dir}/{$layout_name}.scss";

	// If such layout already has template OR can't create file.
	if( ! wp_mkdir_p( $template_dir ) ) return null;

	// Create SCSS template file for this layout.
	if( ! file_put_contents( $template_scss, '@import \'../../src/scss/components/vars\';' ) ) return false;

	return true;
}

/**
 * Render JavaScript template file for ACF Flexible Content layout.
 *
 * @param	string		$layout_name	ACF FC layout name (slug), e.g. 'hero_section'.
 * @param	string		$template_dir	Path to template directory inside acf-flexible-content directory.
 * @return	bool|null
 */
function markupus_render_js_template( string $layout_name, string $template_dir ): ?bool
{
	if( ! $layout_name ) return null;

	$template_js = "{$template_dir}/{$layout_name}.js";

	// If such layout already has template OR can't create file.
	if( ! wp_mkdir_p( $template_dir ) ) return null;

	$js_file_content = "document.addEventListener( 'DOMContentLoaded', () => {\n\t'use strict'\n} )";

	// Create SCSS template file for this layout.
	if( ! file_put_contents( $template_js, $js_file_content ) ) return false;

	return true;
}

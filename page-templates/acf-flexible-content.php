<?php

/**
 * Template name: ACF Flexible Content
 *
 * @package WordPress
 * @subpackage inheart
 */

get_header();
?>

	<main class="main">
		<?php
		/**
		 * 1. Please create ACF Flexible Content setting
		 * for pages with slug 'flexible_content'.
		 *
		 * 2. Then set names of sections PHP-files
		 * the same as ACF Flexible Content sections slugs.
		 *
		 * 3. Put all JavaScript and SCSS files for your section
		 * right inside its PHP-file directory.
		 *
		 * 4. Include these scripts & styles right inside your
		 * PHP-file code from theme_name/static/acf-flexible-content.
		 *
		 * @example
		 * 'hero_section' in ACF Flexible Content sections
		 * will use template from 'theme_name/acf-flexible-content/hero_section/hero_section.php'
		 */
		if( have_rows( 'flexible_content' ) ){
			while( have_rows( 'flexible_content' ) ){
				the_row();
				$slug = get_row_layout();
				get_template_part( "acf-flexible-content/{$slug}/{$slug}" );
			}
		}
		?>
	</main>

<?php
get_footer();

<?php

/**
 * Index page default template.
 *
 * @package WordPress
 * @subpackage inheart
 */

get_header();
?>

	<main class="main">
		<?php
		if( have_posts() ){
			while( have_posts() ){
				the_post();

				echo get_the_permalink();
				the_post_thumbnail( 'thumb-width' ) || ( $backgroundImg = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ) );
				the_title();

				if( has_excerpt() ) the_excerpt();

				echo esc_html( get_the_date( 'F j, Y' ) );
			}
		}	else {
			esc_html_e( 'Posts not found.', THEME_NAME );
		}

		if( get_next_posts_link() ) next_posts_link( '' );
		?>
	</main>

<?php
get_footer();

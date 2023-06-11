<?php
/**
 * The main template file
 *
 * @package Inheart
 */

get_header();
?>
    <header>
        <?php _e('Header says hi', 'inheart'); ?>
    </header>
<?php
if (have_posts()) {
    while (have_posts()) {
        the_post();

        get_template_part('template-parts/content/content', get_theme_mod('display_excerpt_or_full_post', 'excerpt'));
    }
} else {
    get_template_part('template-parts/content/content-none');
}

get_footer();

<?php

/**
 * Tariff page template.
 * Plans list.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $memory_page_id = $args['id'] ?? null ) return;

$plans_query = new WP_Query( [
	'post_type'		=> 'subscription_plan',
	'post_status'	=> 'publish',
	'posts_per_page'=> -1,
	'meta_key'		=> 'position',
	'orderby'		=> 'meta_value',
	'order'			=> 'ASC'
] );

if( ! $plans_query->have_posts() ) return;
?>

<div class="tariff-plans">
	<?php
	while( $plans_query->have_posts() ){
		$plans_query->the_post();
		get_template_part( 'template-parts/tariff/plan' );
	}
	?>
</div>


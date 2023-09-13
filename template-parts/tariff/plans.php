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
		$price_year = get_field( 'price_per_year' );
		?>
		<div class="tariff-plan">
			<div class="tariff-plan-header flex flex-wrap align-end">
				<span class="tariff-plan-title">
					<?php the_title() ?>
				</span>

				<?php
				if( get_field( 'is_optimal' ) )
					echo '<span class="tariff-plan-optimal">' . esc_html__( 'Оптимальна', 'inheart' ) . '</span>';
				?>

				<span class="tariff-plan-price">
					<span class="tariff-plan-price-amount"><?php echo number_format( $price_year, 0, '', ' ' ) ?></span>
					<span class="tariff-plan-price-currency"><?php esc_html_e( 'грн/рік', 'inheart' ) ?></span>
				</span>
			</div>
		</div>
		<?php
	}
	?>
</div>


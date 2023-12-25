<?php

/**
 * Memory single page.
 * Memories section.
 *
 * @package WordPress
 * @subpackage inheart
 */

if( ! $id = $args['id'] ?? null ) return;

$lang			= $args['lang'] ?? 'uk';
$add_memory_url	= get_the_permalink( pll_get_post( 1374 ) ) . '?mp=' . $id;
$memories		= get_posts( [
	'post_type'		=> 'memory',
	'post_status'	=> 'publish',
	'numberposts'	=> -1,
	'meta_query'	=> [ [
		  'key'		=> 'memory_page',
		  'value'	=> $id
	  ] ]
] );
?>

<section class="single-memory-memories">
	<div class="container">
		<h2 class="single-memory-heading">
			<?php echo pll_translate_string( 'Спогади рiдних i близьких', $lang ) ?>
		</h2>

		<?php
		if( ! empty( $memories ) ){
			?>
			<div class="single-memory-memories-cols flex flex-wrap justify-center align-start">
				<?php
				$memories_count = count( $memories );

				if( $memories_count <= 3 ){
					?>
					<div class="single-memory-memories-col single-col flex flex-wrap align-start justify-center add-padding">
						<?php
						foreach( $memories as $memory )
							get_template_part( 'components/memory-page/memory', null, ['id' => $memory->ID] );
						?>
					</div>

					<?php
					get_template_part( 'components/memory-page/add-memory-button', null, [
						'url'	=> $add_memory_url,
						'lang'	=> $lang
					] );
				}else{
					$float_part		= $memories_count % 3;
					$third_part		= $memories_count / 3;
					$count_in_col	= $float_part === 0 ? ( int ) $third_part : ( int ) $third_part + 1;
					?>
					<!-- First column -->
					<div class="single-memory-memories-col flex direction-column add-padding">
						<?php
						for( $i = 0; $i < $count_in_col; $i++ )
							get_template_part( 'components/memory-page/memory', null, [
								'id'	=> $memories[$i]->ID,
								'index'	=> $i
							] );

						get_template_part( 'components/memory-page/add-memory-button', null, [
							'url'	=> $add_memory_url,
							'lang'	=> $lang,
							'class'	=> 'hide-before-lg'
						] );
						?>
					</div>

					<!-- Second column -->
					<div class="single-memory-memories-col flex direction-column">
						<?php
						$count_without_1st_col	= $memories_count - $count_in_col;
						$float_part				= $count_without_1st_col % 2;
						$half					= $count_without_1st_col / 2;
						$count_in_col_2			= $float_part === 0 ? ( int ) $half : ( int ) $half + 1;

						for( $i = $count_in_col; $i < ( $count_in_col + $count_in_col_2 ); $i++ )
							get_template_part( 'components/memory-page/memory', null, [
								'id'	=> $memories[$i]->ID,
								'index'	=> $i
							] );
						?>
					</div>

					<!-- Third column -->
					<div class="single-memory-memories-col flex direction-column add-padding">
						<?php
						for( $i = ( $count_in_col + $count_in_col_2 ); $i < $memories_count; $i++ )
							get_template_part( 'components/memory-page/memory', null, [
								'id'	=> $memories[$i]->ID,
								'index'	=> $i
							] );
						?>
					</div>

					<?php
					if( $memories_count > 3 ){
						?>
						<div class="show-more-posts flex grow justify-end hide-after-lg">
							<button>
								<?php printf( pll_translate_string( 'Дивитись ще %d', $lang ), $memories_count - 3 ) ?>
							</button>
						</div>
						<?php
					}

					get_template_part( 'components/memory-page/add-memory-button', null, [
						'url'	=> $add_memory_url,
						'lang'	=> $lang,
						'class'	=> 'hide-after-lg'
					] );
				}
				?>
			</div>
			<?php
		}else{
			get_template_part( 'template-parts/single/memory/memories-not-found', null, [
				'lang'	=> $lang,
				'url'	=> $add_memory_url
			] );
		}
		?>
	</div>
</section>


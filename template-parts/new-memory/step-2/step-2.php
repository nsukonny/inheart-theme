<?php

/**
 * New Memory page template.
 * Step 2.
 *
 * @see Page Template: New Memory -> Step 2
 *
 * @package WordPress
 * @subpackage inheart
 */

$title					= get_field( 'title_2' );
$desc					= get_field( 'desc_2' );
$sections_title			= get_field( 'sections_title' );
$added_sections_title	= get_field( 'added_sections_title' );
$sections				= get_field( 'sections' );
$sections_count			= count( $sections );
$sections_desc			= get_field( 'sections_desc' );

if( $memory_page_id = $_SESSION['memory_page_id'] ?? null ){
	$ready_sections 		= get_field( 'biography_sections', $memory_page_id );
	$last_fight				= get_field( 'last_fight', $memory_page_id );
	$cto					= get_field( 'cto', $memory_page_id );
	$war					= get_field( 'war', $memory_page_id );
	$cto['index']			= $sections_count;
	$cto['category']		= __( 'АТО', 'inheart' );
	$war['index']			= $sections_count + 1;
	$war['category']		= __( 'Повномасштабне вторгнення', 'inheart' );
	$last_fight['index']	= $sections_count + 2;
}else{
	$ready_sections	= null;
	$cto			= $war = $last_fight = [];
}

// Sort array of arrays by position property, maintain keys.
if( $ready_sections ){
	uasort( $ready_sections, function( $a, $b ){
		return ( int ) $a['position'] - ( int ) $b['position'];
	} );
}
?>

<section id="new-memory-step-2" class="new-memory-step new-memory-step-2 direction-column">
	<div class="container direction-column">
		<div class="new-memory-step-suptitle">
			<span><?php _e( 'Крок 2', 'inheart' ) ?></span>
			<span class="hidden"><?php _e( 'Крок 4', 'inheart' ) ?></span>
		</div>

		<?php
		if( $title ){
			?>
			<div class="new-memory-step-title">
				<?php echo $title ?>
			</div>
			<?php
		}

		if( $desc ){
			?>
			<div class="new-memory-step-desc">
				<?php echo $desc ?>
			</div>
			<?php
		}

		if( $sections ){
			?>
			<div class="form-white sections-wrapper flex flex-wrap">
				<div class="sections-sidebar">
					<div class="sections-added hide-before-md">
						<?php
						if( $added_sections_title ){
							?>
							<div class="sections-title">
								<?php echo esc_html( $added_sections_title ) ?>
							</div>
							<?php
						}
						?>

						<div class="sections-added-list flex direction-column">
							<?php
							if( ! empty( $ready_sections ) ){
								foreach( $ready_sections as $key => $section )
									get_template_part(
										'template-parts/new-memory/step-2/section-sidebar',
										'added',
										['key' => $key, 'section' => $section]
									);
							}else{
								get_template_part(
									'template-parts/new-memory/step-2/section-sidebar',
									'added',
									[
										'key' => 0,
										'section' => [
											'category'	=> $sections[0]['title'],
											'position'	=> 0,
											'index'		=> 0
										]
									]
								);
							}

							if( ih_is_set_military_section( $cto ) ){
								get_template_part(
									'template-parts/new-memory/step-2/section-sidebar-military',
									null,
									[
										'key'	=> $sections_count,
										'title'	=> __( 'АТО', 'inheart' ),
										'id'	=> 'cto'
									]
								);
							}

							if( ih_is_set_military_section( $war ) ){
								get_template_part(
									'template-parts/new-memory/step-2/section-sidebar-military',
									null,
									[
										'key'	=> $sections_count + 1,
										'title'	=> __( 'Повномасштабне вторгнення', 'inheart' ),
										'id'	=> 'war'
									]
								);
							}

							if( ih_is_set_last_fight( $last_fight ) ){
								get_template_part(
									'template-parts/new-memory/step-2/section-sidebar-military',
									null,
									[
										'key'	=> $sections_count + 2,
										'title'	=> __( 'Останній бій', 'inheart' ),
										'id'	=> 'last-fight'
									]
								);
							}
							?>
						</div><!-- .sections-added-list -->
					</div><!-- .sections-added -->

					<div class="sections">
						<?php
						if( $sections_title ){
							?>
							<div class="sections-title">
								<?php echo esc_html( $sections_title ) ?>
							</div>
							<?php
						}
						?>

						<div class="sections-list">
							<?php
							foreach( $sections as $key => $section )
								get_template_part(
									'template-parts/new-memory/step-2/section-sidebar',
									null,
									['key' => $key, 'section' => $section, 'ready_sections' => $ready_sections]
								);
							?>
						</div>

						<div class="sections-military-title">
							<span><?php _e( 'Військова служба', 'inheart' ) ?></span>
						</div>

						<div class="sections-military">
							<?php
							if( ! ih_is_set_military_section( $cto ) )
								get_template_part(
									'template-parts/new-memory/step-2/section-sidebar-military',
									null,
									['key' => $sections_count, 'title' => __( 'АТО', 'inheart' ), 'id' => 'cto']
								);

							if( ! ih_is_set_military_section( $war ) )
								get_template_part(
									'template-parts/new-memory/step-2/section-sidebar-military',
									null,
									['key' => $sections_count + 1, 'title' => __( 'Повномасштабне вторгнення', 'inheart' ), 'id' => 'war']
								);

							if( ! ih_is_set_last_fight( $last_fight ) )
								get_template_part(
									'template-parts/new-memory/step-2/section-sidebar-military',
									null,
									['key' => $sections_count + 2, 'title' => __( 'Останній бій', 'inheart' ), 'id' => 'last-fight']
								);
							?>
						</div>
					</div><!-- .sections -->
				</div><!-- .sections-sidebar -->

				<?php
				if( $sections_desc )
					echo '<div class="sections-desc">',
						$sections_desc,
						'<div class="sections-desc-icon flex justify-center">
							<svg width="16" height="22" viewBox="0 0 16 22" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M8.00056 21.615C7.15776 20.6109 6.50812 19.5065 6.06478 18.2838C6.0195 18.1591 5.96583 18.1172 5.83137 18.1175C3.94479 18.1225 2.05793 18.1211 0.171355 18.1211C0.116566 18.1211 0.0620567 18.1211 0 18.1211V2.73021C0.275901 2.88452 0.545931 3.0173 0.797512 3.17915C1.94947 3.91935 2.65642 4.99081 3.06845 6.27499C3.30522 7.01268 3.42821 7.7719 3.4939 8.54314C3.57637 9.51144 3.56183 10.4798 3.48244 11.4455C3.41507 12.2654 3.30913 13.0822 3.218 13.9001C3.19089 14.1445 3.15567 14.3882 3.1224 14.6445H6.22412C6.22412 14.441 6.22859 14.2339 6.22328 14.0268C6.197 12.9933 6.17716 11.9596 6.13802 10.9264C6.09609 9.81446 6.04018 8.70275 5.98148 7.5916C5.94486 6.89919 5.87554 6.20818 5.84842 5.5155C5.79335 4.10525 6.12768 2.78864 6.8458 1.57154C7.15664 1.04462 7.52144 0.557672 7.93068 0.103707C7.9522 0.0799469 7.96953 0.052273 8.00867 0C8.26668 0.321744 8.5191 0.613578 8.74608 0.924141C9.3904 1.80635 9.83654 2.77857 10.0426 3.85618C10.1709 4.52762 10.1991 5.20354 10.1499 5.88504C10.1021 6.54894 10.061 7.21339 10.0258 7.87812C9.97687 8.79388 9.92571 9.70991 9.89413 10.6265C9.84968 11.9202 9.82229 13.2147 9.78762 14.5087C9.7865 14.5542 9.78762 14.5995 9.78762 14.6546H12.8706C12.8139 14.199 12.7521 13.7531 12.704 13.3056C12.6372 12.6861 12.5603 12.0667 12.5243 11.4453C12.4835 10.7392 12.4552 10.0303 12.4709 9.32332C12.4983 8.07603 12.671 6.85055 13.1585 5.68713C13.7047 4.38338 14.6023 3.41368 15.8859 2.80932C15.9108 2.79758 15.9365 2.78668 15.9625 2.77718C15.9706 2.7741 15.9807 2.77662 16 2.77662V18.1275C15.9474 18.1275 15.8977 18.1275 15.8482 18.1275C13.9477 18.1275 12.0468 18.1292 10.1463 18.1256C10.0272 18.1256 9.97994 18.1639 9.94221 18.274C9.5584 19.4011 8.70499 20.8656 8.00056 21.615Z" fill="#E69636"/>
							</svg>
						</div>
					</div>';
				?>

				<div class="sections-content">
					<?php
					if( ! empty( $ready_sections ) ){
						foreach( $ready_sections as $key => $section )
							get_template_part(
								'template-parts/new-memory/step-2/section-content',
								null,
								['sections' => $sections, 'section' => $section]
							);
					}else{
						get_template_part(
							'template-parts/new-memory/step-2/section-content',
							null,
							[ 'section' => [ 'category' => $sections[0]['title'] ] ]
						);
					}

					if( ih_is_set_military_section( $cto ) )
						get_template_part( 'template-parts/new-memory/step-2/section-content', null, [
							'section'	=> $cto,
							'class'		=> 'section-content-cto'
						] );

					if( ih_is_set_military_section( $war ) )
						get_template_part( 'template-parts/new-memory/step-2/section-content', null, [
							'section'	=> $war,
							'class'		=> 'section-content-war'
						] );

					if( ih_is_set_last_fight( $last_fight ) )
						get_template_part( 'template-parts/new-memory/step-2/section-content-last-fight', null, [
							'section' => $last_fight
						] );
					?>
				</div><!-- .sections-content -->
			</div><!-- .sections-wrapper -->
			<?php
		}
		?>
	</div><!-- .container -->

	<?php
	get_template_part( 'template-parts/new-memory/step-2/section-content-last-fight', null, [
		'section'	=> $last_fight,
		'class'		=> 'hidden'
	] );
	?>
</section><!-- #new-memory-step-2 -->


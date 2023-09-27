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
$ready_sections			= isset( $_SESSION['memory_page_id'] )
						? get_field( 'biography_sections', $_SESSION['memory_page_id'] ) : null;

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
			<?php esc_html_e( 'Крок 2', 'inheart' ) ?>
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
							?>
						</div><!-- .sections-added-list -->
					</div><!-- .sections-added -->

					<div class="sections">
						<?php
						if( $sections_title ){
							?>
							<div class="sections-title hide-before-md">
								<?php echo esc_html( $sections_title ) ?>
							</div>
							<?php
						}
						?>

						<div class="sections-list flex direction-column">
							<?php
							foreach( $sections as $key => $section )
								get_template_part(
									'template-parts/new-memory/step-2/section-sidebar',
									null,
									['key' => $key, 'section' => $section, 'ready_sections' => $ready_sections]
								);
							?>
						</div>
					</div><!-- .sections -->
				</div><!-- .sections-sidebar -->

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
					?>
				</div>
			</div><!-- .sections-wrapper -->
			<?php
		}
		?>
</section><!-- #new-memory-step-2 -->


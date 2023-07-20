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

$is_active				= ( isset( $args['is_active'] ) && $args['is_active'] == 'true' ) ? ' active' : '';
$title					= get_field( 'title_2' );
$desc					= get_field( 'desc_2' );
$sections_title			= get_field( 'sections_title' );
$added_sections_title	= get_field( 'added_sections_title' );
$sections				= get_field( 'sections' );
?>

<section id="new-memory-step-2" class="new-memory-step new-memory-step-2<?php echo esc_attr( $is_active ) ?>">
	<div class="container">
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
			<div class="new-memory-main-info sections-wrapper flex flex-wrap">
				<div class="sections-sidebar">
					<div class="sections-added">
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
							<div class="section flex flex-wrap align-center" data-order="0" data-id="0">
								<div class="section-label">
									<?php echo esc_html( $sections[0]['title'] ) ?>
								</div>
								<button
									class="section-add"
									aria-label="<?php esc_attr_e( 'Додати секцію', 'inheart' ) ?>"
									title="<?php esc_attr_e( 'Додати секцію', 'inheart' ) ?>"
								>
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<mask id="mask-add-0" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="2" y="2" width="20" height="20">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.48 6.48 2 12 2C17.52 2 22 6.48 22 12C22 17.52 17.52 22 12 22C6.48 22 2 17.52 2 12ZM13 13H16C16.55 13 17 12.55 17 12C17 11.45 16.55 11 16 11H13V8C13 7.45 12.55 7 12 7C11.45 7 11 7.45 11 8V11H8C7.45 11 7 11.45 7 12C7 12.55 7.45 13 8 13H11V16C11 16.55 11.45 17 12 17C12.55 17 13 16.55 13 16V13Z" fill="black"/>
										</mask>
										<g mask="url(#mask-add-0)">
											<rect width="24" height="24" fill="currentColor"/>
										</g>
									</svg>
								</button>
								<button
									class="section-remove"
									aria-label="<?php esc_attr_e( 'Видалити секцію', 'inheart' ) ?>"
									title="<?php esc_attr_e( 'Видалити секцію', 'inheart' ) ?>"
								>
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<mask id="mask-remove-0" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="2" y="2" width="20" height="20">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM7 12C7 12.55 7.45 13 8 13H16C16.55 13 17 12.55 17 12C17 11.45 16.55 11 16 11H8C7.45 11 7 11.45 7 12ZM4 12C4 16.41 7.59 20 12 20C16.41 20 20 16.41 20 12C20 7.59 16.41 4 12 4C7.59 4 4 7.59 4 12Z" fill="black"/>
										</mask>
										<g mask="url(#mask-remove-0)">
											<rect width="24" height="24" fill="currentColor"/>
										</g>
									</svg>
								</button>
								<button
									class="section-drag"
									aria-label="<?php esc_attr_e( 'Перетягнути секцію', 'inheart' ) ?>"
									title="<?php esc_attr_e( 'Перетягнути секцію', 'inheart' ) ?>"
								>
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<mask id="mask-drag-0" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="7" y="4" width="10" height="16">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M9 4C7.9 4 7 4.9 7 6C7 7.1 7.9 8 9 8C10.1 8 11 7.1 11 6C11 4.9 10.1 4 9 4ZM7 12C7 10.9 7.9 10 9 10C10.1 10 11 10.9 11 12C11 13.1 10.1 14 9 14C7.9 14 7 13.1 7 12ZM9 20C10.1 20 11 19.1 11 18C11 16.9 10.1 16 9 16C7.9 16 7 16.9 7 18C7 19.1 7.9 20 9 20ZM17 6C17 7.1 16.1 8 15 8C13.9 8 13 7.1 13 6C13 4.9 13.9 4 15 4C16.1 4 17 4.9 17 6ZM15 10C13.9 10 13 10.9 13 12C13 13.1 13.9 14 15 14C16.1 14 17 13.1 17 12C17 10.9 16.1 10 15 10ZM13 18C13 16.9 13.9 16 15 16C16.1 16 17 16.9 17 18C17 19.1 16.1 20 15 20C13.9 20 13 19.1 13 18Z" fill="black"/>
										</mask>
										<g mask="url(#mask-drag-0)">
											<rect width="24" height="24" fill="currentColor"/>
										</g>
									</svg>
								</button>
							</div><!-- .section.section-added -->
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

						<div class="sections-list flex direction-column">
							<?php
							foreach( $sections as $key => $section ){
								if( ! $key || ! ( $sec_title = $section['title'] ) ) continue;
								?>
								<div
									class="section flex flex-wrap align-center"
									data-order="<?php echo esc_attr( $key ) ?>"
									data-id="<?php echo esc_attr( $key ) ?>"
								>
									<div class="section-label">
										<?php echo esc_html( $sec_title ) ?>
									</div>
									<button
										class="section-add"
										aria-label="<?php esc_attr_e( 'Додати секцію', 'inheart' ) ?>"
										title="<?php esc_attr_e( 'Додати секцію', 'inheart' ) ?>"
									>
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<mask id="mask-add-<?php echo esc_attr( $key ) ?>" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="2" y="2" width="20" height="20">
												<path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.48 6.48 2 12 2C17.52 2 22 6.48 22 12C22 17.52 17.52 22 12 22C6.48 22 2 17.52 2 12ZM13 13H16C16.55 13 17 12.55 17 12C17 11.45 16.55 11 16 11H13V8C13 7.45 12.55 7 12 7C11.45 7 11 7.45 11 8V11H8C7.45 11 7 11.45 7 12C7 12.55 7.45 13 8 13H11V16C11 16.55 11.45 17 12 17C12.55 17 13 16.55 13 16V13Z" fill="black"/>
											</mask>
											<g mask="url(#mask-add-<?php echo esc_attr( $key ) ?>)">
												<rect width="24" height="24" fill="currentColor"/>
											</g>
										</svg>
									</button>
									<button
										class="section-remove"
										aria-label="<?php esc_attr_e( 'Видалити секцію', 'inheart' ) ?>"
										title="<?php esc_attr_e( 'Видалити секцію', 'inheart' ) ?>"
									>
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<mask id="mask-remove-<?php echo esc_attr( $key ) ?>" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="2" y="2" width="20" height="20">
												<path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM7 12C7 12.55 7.45 13 8 13H16C16.55 13 17 12.55 17 12C17 11.45 16.55 11 16 11H8C7.45 11 7 11.45 7 12ZM4 12C4 16.41 7.59 20 12 20C16.41 20 20 16.41 20 12C20 7.59 16.41 4 12 4C7.59 4 4 7.59 4 12Z" fill="black"/>
											</mask>
											<g mask="url(#mask-remove-<?php echo esc_attr( $key ) ?>)">
												<rect width="24" height="24" fill="currentColor"/>
											</g>
										</svg>
									</button>
									<button
										class="section-drag"
										aria-label="<?php esc_attr_e( 'Перетягнути секцію', 'inheart' ) ?>"
										title="<?php esc_attr_e( 'Перетягнути секцію', 'inheart' ) ?>"
									>
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<mask id="mask-drag-<?php echo esc_attr( $key ) ?>" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="7" y="4" width="10" height="16">
												<path fill-rule="evenodd" clip-rule="evenodd" d="M9 4C7.9 4 7 4.9 7 6C7 7.1 7.9 8 9 8C10.1 8 11 7.1 11 6C11 4.9 10.1 4 9 4ZM7 12C7 10.9 7.9 10 9 10C10.1 10 11 10.9 11 12C11 13.1 10.1 14 9 14C7.9 14 7 13.1 7 12ZM9 20C10.1 20 11 19.1 11 18C11 16.9 10.1 16 9 16C7.9 16 7 16.9 7 18C7 19.1 7.9 20 9 20ZM17 6C17 7.1 16.1 8 15 8C13.9 8 13 7.1 13 6C13 4.9 13.9 4 15 4C16.1 4 17 4.9 17 6ZM15 10C13.9 10 13 10.9 13 12C13 13.1 13.9 14 15 14C16.1 14 17 13.1 17 12C17 10.9 16.1 10 15 10ZM13 18C13 16.9 13.9 16 15 16C16.1 16 17 16.9 17 18C17 19.1 16.1 20 15 20C13.9 20 13 19.1 13 18Z" fill="black"/>
											</mask>
											<g mask="url(#mask-drag-<?php echo esc_attr( $key ) ?>)">
												<rect width="24" height="24" fill="currentColor"/>
											</g>
										</svg>
									</button>
								</div>
								<?php
							}
							?>
						</div>
					</div><!-- .sections -->
				</div><!-- .sections-sidebar -->

				<div class="sections-content">
					<div class="section-content" data-id="0">
						<h3 class="section-content-title">
							<?php echo esc_html( $sections[0]['title'] ) ?>
						</h3>
						<textarea class="section-content-text" placeholder="<?php esc_attr_e( 'Напишіть якомога детальну біографію', 'inheart' ) ?>"></textarea>
						<button
							class="section-drag"
							aria-label="<?php esc_attr_e( 'Перетягнути секцію', 'inheart' ) ?>"
							title="<?php esc_attr_e( 'Перетягнути секцію', 'inheart' ) ?>"
						>
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<mask id="content-drag-0" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="7" y="4" width="10" height="16">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M9 4C7.9 4 7 4.9 7 6C7 7.1 7.9 8 9 8C10.1 8 11 7.1 11 6C11 4.9 10.1 4 9 4ZM7 12C7 10.9 7.9 10 9 10C10.1 10 11 10.9 11 12C11 13.1 10.1 14 9 14C7.9 14 7 13.1 7 12ZM9 20C10.1 20 11 19.1 11 18C11 16.9 10.1 16 9 16C7.9 16 7 16.9 7 18C7 19.1 7.9 20 9 20ZM17 6C17 7.1 16.1 8 15 8C13.9 8 13 7.1 13 6C13 4.9 13.9 4 15 4C16.1 4 17 4.9 17 6ZM15 10C13.9 10 13 10.9 13 12C13 13.1 13.9 14 15 14C16.1 14 17 13.1 17 12C17 10.9 16.1 10 15 10ZM13 18C13 16.9 13.9 16 15 16C16.1 16 17 16.9 17 18C17 19.1 16.1 20 15 20C13.9 20 13 19.1 13 18Z" fill="black"/>
								</mask>
								<g mask="url(#content-drag-0)">
									<rect width="24" height="24" fill="currentColor"/>
								</g>
							</svg>
						</button>
						<button
							class="section-remove"
							aria-label="<?php esc_attr_e( 'Видалити секцію', 'inheart' ) ?>"
							title="<?php esc_attr_e( 'Видалити секцію', 'inheart' ) ?>"
						>
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<mask id="content-remove-0" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="2" y="2" width="20" height="20">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM7 12C7 12.55 7.45 13 8 13H16C16.55 13 17 12.55 17 12C17 11.45 16.55 11 16 11H8C7.45 11 7 11.45 7 12ZM4 12C4 16.41 7.59 20 12 20C16.41 20 20 16.41 20 12C20 7.59 16.41 4 12 4C7.59 4 4 7.59 4 12Z" fill="black"/>
								</mask>
								<g mask="url(#content-remove-0)">
									<rect width="24" height="24" fill="currentColor"/>
								</g>
							</svg>
						</button>
					</div>
				</div>
			</div><!-- .sections-wrapper -->
			<?php
		}
		?>
</section><!-- #new-memory-step-2 -->


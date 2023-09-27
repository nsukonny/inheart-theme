<?php

/**
 * Footer for New Memory page template.
 *
 * @package WordPress
 * @subpackage inheart
 */

$class		= isset( $args['active_back'] ) && $args['active_back'] ? '' : ' hidden';
$next_index	= $class ? 1 : 2;
$allow_next = ( isset( $_SESSION['memory_page_id'] ) && get_field( 'theme', $_SESSION['memory_page_id'] ) )
			? '' : 'disabled';
?>

			<footer class="new-memory-footer">
				<div class="new-memory-progress-bar flex flex-wrap">
					<?php
					for( $i = 1; $i <= 5; $i++ ){
						$percents = $next_index === 2 && $i === 1 ? '100%' : 0;
						?>
						<div class="new-memory-progress-part" data-part="<?php echo esc_attr( $i ) ?>">
							<div class="new-memory-progress-inner" style="width: <?php echo esc_attr( $percents ) ?>"></div>
						</div>
						<?php
					}
					?>
				</div>

				<div class="container">
					<div class="new-memory-footer-inner flex flex-wrap">
						<button
							class="btn simple new-memory-prev-step<?php echo esc_attr( $class ) ?>" data-prev="0">
							<?php esc_html_e( 'Назад', 'inheart' ) ?>
						</button>
						<button
							class="btn lg primary min-width new-memory-next-step"
							data-next="<?php echo esc_attr( $next_index ) ?>"
							<?php echo esc_attr( $allow_next ) ?>
						>
							<?php esc_html_e( 'Далі', 'inheart' ) ?>
						</button>
					</div>
				</div>
			</footer>

			<?php wp_footer() ?>
		</div><!-- .wrapper -->
	</body>
</html>

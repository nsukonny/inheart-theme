<?php

/**
 * Footer for New Memory page template.
 *
 * @package WordPress
 * @subpackage inheart
 */

$allow_next = ( isset( $_SESSION['memory_page_id'] ) && get_field( 'theme', $_SESSION['memory_page_id'] ) )
			? '' : 'disabled';
?>

			<footer class="new-memory-footer">
				<div class="new-memory-progress-bar flex flex-wrap">
					<?php
					for( $i = 1; $i <= 5; $i++ ){
						$progress = ( $i === 1 && $allow_next ) ? ' style="width: 100%"' : '';
						?>
						<div class="new-memory-progress-part" data-part="<?php echo esc_attr( $i ) ?>">
							<div class="new-memory-progress-inner"<?php echo $progress ?>></div>
						</div>
						<?php
					}
					?>
				</div>

				<div class="container">
					<div class="new-memory-footer-inner flex flex-wrap">
						<button class="btn simple new-memory-prev-step hidden" data-prev="0">
							<?php esc_html_e( 'Назад', 'inheart' ) ?>
						</button>
						<button
							class="btn lg primary min-width new-memory-next-step"
							data-next="1"
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

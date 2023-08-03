<?php

/**
 * Footer for New Memory page template.
 *
 * @package WordPress
 * @subpackage inheart
 */

$step		= isset( $args['step'] ) ? ( int ) $args['step'] : 0;
$prev_class	= $step ? '' : ' hidden';
$allow_next	= ( $step && isset( $_SESSION["step{$step}"]['ready'] ) ) ? '' : 'disabled';
?>

			<footer class="new-memory-footer">
				<div class="new-memory-progress-bar flex flex-wrap">
					<?php
					for( $i = 1; $i <= 5; $i++ ){
						$progress = $step > $i ? ' style="width: 100%"' : '';
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
						<button
							class="btn simple new-memory-prev-step<?php echo esc_attr( $prev_class ) ?>"
							data-prev="<?php echo $step <= 1 ? 0 : esc_attr( $step - 1 ) ?>"
						>
							<?php esc_html_e( 'Назад', 'inheart' ) ?>
						</button>
						<button
							class="btn lg primary min-width new-memory-next-step"
							data-next="<?php echo esc_attr( $step + 1 ) ?>"
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

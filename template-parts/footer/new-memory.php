<?php

/**
 * Footer for New Memory page template.
 *
 * @package WordPress
 * @subpackage inheart
 */
?>

			<footer class="new-memory-footer">
				<div class="new-memory-progress-bar"></div>

				<div class="container">
					<div class="new-memory-footer-inner flex flex-wrap new-memory-prev-step">
						<button class="btn lg primary full hidden">
							<?php esc_html_e( 'Назад', 'inheart' ) ?>
						</button>
						<button class="btn lg primary full new-memory-next-step" disabled>
							<?php esc_html_e( 'Далі', 'inheart' ) ?>
						</button>
					</div>
				</div>
			</footer>

			<?php wp_footer() ?>
		</div><!-- .wrapper -->
	</body>
</html>

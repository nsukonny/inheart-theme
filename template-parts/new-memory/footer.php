<?php

/**
 * Footer for New Memory page template.
 *
 * @package WordPress
 * @subpackage inheart
 */
?>

			<footer class="new-memory-footer">
				<div class="new-memory-progress-bar flex flex-wrap">
					<div class="new-memory-progress-part" data-part="1"><div class="new-memory-progress-inner"></div></div>
					<div class="new-memory-progress-part" data-part="2"><div class="new-memory-progress-inner"></div></div>
					<div class="new-memory-progress-part" data-part="3"><div class="new-memory-progress-inner"></div></div>
					<div class="new-memory-progress-part" data-part="4"><div class="new-memory-progress-inner"></div></div>
					<div class="new-memory-progress-part" data-part="5"><div class="new-memory-progress-inner"></div></div>
				</div>

				<div class="container">
					<div class="new-memory-footer-inner flex flex-wrap">
						<button class="btn lg primary min-width new-memory-prev-step hidden" data-prev="0">
							<?php esc_html_e( 'Назад', 'inheart' ) ?>
						</button>
						<button class="btn lg primary min-width new-memory-next-step" data-next="1" disabled>
							<?php esc_html_e( 'Далі', 'inheart' ) ?>
						</button>
					</div>
				</div>
			</footer>

			<?php wp_footer() ?>
		</div><!-- .wrapper -->
	</body>
</html>

<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>
			</div> <!-- row -->
		</section><!-- #main -->
		
		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="container">
<hr />
				<div class="site-info">
					<?php do_action( 'twentythirteen_credits' ); ?>
				</div><!-- .site-info -->
			</div>
		</footer><!-- #colophon -->
	</div><!-- #page -->

	<?php wp_footer(); ?>
</body>
</html>
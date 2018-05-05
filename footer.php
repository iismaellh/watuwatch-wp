<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package watuwatch
 */

?>
		</div><!-- .container -->
	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="site-info">
				<span class="powered">Powered by:</span>
				<a class="powered" href="<?php echo esc_url( __( 'https://www.themoviedb.org/', 'watuwatch' ) ); ?>">TMDB</a>
				<a class="powered" href="<?php echo esc_url( __( 'https://wordpress.org/', 'watuwatch' ) ); ?>">WordPress</a>
				<a class="powered" href="<?php echo esc_url( __( 'http://underscores.me/', 'watuwatch' ) ); ?>" title="<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'watuwatch' ), 'watuwatch', 'Underscores.me' ); ?>">
					Underscores.me
				</a>
			</div><!-- .site-info -->
		</div><!-- .container -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

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
				<a href="<?php echo esc_url( __( 'https://www.themoviedb.org/', 'watuwatch' ) ); ?>">
					<img class="powered" src="<?php echo get_theme_file_uri( '/img/tmdb.png' ); ?>" title="<?php printf( esc_html__( 'Powered by %s', 'watuwatch' ), 'TMDB' ); ?>">
				</a>
				<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'watuwatch' ) ); ?>">
					<img class="powered" src="<?php echo get_theme_file_uri( '/img/wordpress.png' ); ?>" title="<?php printf( esc_html__( 'Powered by %s', 'watuwatch' ), 'WordPress' ); ?>">
				</a>
				<a href="<?php echo esc_url( __( 'http://underscores.me/', 'watuwatch' ) ); ?>" class="powered underscore_logo" title="<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'watuwatch' ), 'watuwatch', 'Underscores.me' ); ?>">
					<span class="site-header-bubblewrap">
						<span class="site-header-bubblewrap-inner">Underscores</span>
					</span>
				</a>
			</div><!-- .site-info -->
		</div><!-- .container -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

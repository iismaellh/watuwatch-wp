<?php
/**
 * The template for displaying the movies page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package watuwatch
 */

get_header(); ?>
<div id="content" class="site-content">
	<div id="masthead-bg"></div>
	<div class="container films">
	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'templates/content', 'films' );

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer();

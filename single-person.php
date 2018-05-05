<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header();

?>
<div id="content" class="site-content">
	<div id="masthead-bg"></div>
	<div class="container">
				<?php
				/*
					* If a regular post or page, and not the front page, show the featured image.
					* Using get_queried_object_id() here since the $post global may not be set before a call to the_post().
					*/

				while ( have_posts() ) : the_post();

					get_template_part( 'templates/content', 'person' );

				endwhile; // End of the loop.
				?>


	</div><!-- .wrap -->

<?php get_footer(); ?>

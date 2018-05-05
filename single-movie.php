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
	<?php $backdrop = get_post_meta( get_the_ID(), '_watu_movie_backdrop_url', true ); ?>

	<div class="single-movie-bg" style="background-image: url('<?php echo $backdrop; ?>');">
		<div class="movie-bottom-gradient"></div>
	</div>

	<?php if( is_singular('movie') ) : ?>
	<div class="single-movie-header" style="background-image: url('<?php echo $backdrop; ?>');">
		<!-- <div class="movie-left-gradient"></div>  -->
		<!-- <div class="movie-right-gradient"></div> -->
	</div><!-- .single-movie-header -->
	<?php endif; ?>
	
	<div class="container">
	<?php
	/*
	 * If a regular post or page, and not the front page, show the featured image.
	 * Using get_queried_object_id() here since the $post global may not be set before a call to the_post().
	 */

	while ( have_posts() ) : the_post();

		 get_template_part( 'templates/content', 'movie' );

	endwhile; // End of the loop.


get_footer();

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
<?php
/**
 * Template part for displaying home content in page-home.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>


<?php

$tmdb     = new TMDB();
$int1     = rand(1, 5);
$int2     = rand(2, 5);
$slider   = $tmdb->getNowPlayingMovies($int2);
$playing  = $tmdb->getNowPlayingMovies(1);
$discover = $tmdb->getDiscoverMovies($int1, '', 'vote_count.desc', '');

?>
<div id="content" class="site-content discover">
    <div id="masthead-bg"></div>
    <div class="swiper-container-discover">
        <div class="swiper-wrapper">
            <?php
            if( ! empty ( $slider ) ) {
                watuwatch_create_movie_grid($slider, 5, 'movie', '', true);
            }
            ?>
            <div class="clear"></div>
        </div>
        <div class="swiper-pagination swiper-pagination-white"></div>
    </div>

	<div class="container">

    <?php if( ! empty ( $discover ) ) : ?>
    	<div class="movie-container">
        <h5>Highest Rated</h5>
      	<div class="movie-container-wrapper grid grid-medium-gap grid-one-tenth display-small">
    			<?php watuwatch_create_movie_grid($discover, 20, 'movie', 'portrait', true); ?>
    		</div>
    	</div>
    <?php endif; ?>

    <?php if( ! empty ( $playing ) ) : ?>
    	<div class="movie-container">
        <h5>Now Playing</h5>
      	<div class="movie-container-wrapper grid grid-medium-gap grid-one-tenth display-small">
    			<?php watuwatch_create_movie_grid($playing, 20, 'movie', 'portrait', true); ?>
    		</div>
    	</div>
    <?php endif; ?>

<?php
get_footer();

<?php
/**
 * Template part for displaying page content in page.php
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
$paged    = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$discover = $tmdb->getDiscoverMovies($paged);
$playing  = $tmdb->getNowPlayingMovies(1);


if( ! empty ( $playing ) ) {
	echo '<div class="movie-container">';
  	echo '<div class="movie-container-wrapper grid grid-one-fifth">';
			watuwatch_create_movie_grid($playing, 5, 'movie', 'portrait', true);
		echo '</div>';
	echo '</div>';
}

if( ! empty ( $discover ) ) {
	echo '<div class="movie-container">';
  	echo '<div class="movie-container-wrapper grid grid-large-gap grid-one-third">';
			watuwatch_create_movie_grid($discover, 18, 'movie', 'landscape', true);
		echo '</div>';
	echo '</div>';
}

?>

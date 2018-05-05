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

$tmdb  = new TMDB();
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$start = $paged == 1 ? 0 : $paged + 3;
$end   = $paged == 1 ? 4 : $start + 3;
$page  = $paged == 1 ? 0 : $paged + 2;
$genres = watuwatch_movie_genre(false);
$sort  = watuwatch_movie_sort_by(false);

?>

<div class="movie-filter-container">
	<div class="btn-group dropdown-filter">
		<div class="dropdown">
			<button class="btn btn-primary dropdown-toggle" id="genre" type="button" data-toggle="dropdown">Genre<span class="caret"></span></button>
			<ul class="dropdown-menu" role="menu" aria-labelledby="genre">
				<?php
				foreach($genres as $id => $name) {
					echo '<li role="presentation"><a class="genre" role="menuitem" tabindex="-1" href="#" data-genre="'.$id.'">'.$name.'</a></li>';
				}
				?>
			</ul>
		</div>

		<div class="dropdown">
			<button class="btn btn-primary dropdown-toggle" id="sortby" type="button" data-toggle="dropdown">Sort by<span class="caret"></span></button>
			<ul class="dropdown-menu" role="menu" aria-labelledby="sortby">
				<?php
				foreach($sort as $id => $name) {
					echo '<li role="presentation"><a class="sortby" role="menuitem" tabindex="-1" href="#" data-sort="'.$name.'">'.$id.'</a></li>';
				}
				?>
			</ul>
		</div>

		<div class="dropdown">
			<button class="btn btn-primary dropdown-toggle" id="size" type="button" data-toggle="dropdown">Display<span class="caret"></span></button>
			<ul class="dropdown-menu" role="menu" aria-labelledby="size">
				<?php
					echo '<li role="presentation"><a class="size" role="menuitem" tabindex="-1" href="#" data-size="small">Small</a></li>';
					echo '<li role="presentation"><a class="size" role="menuitem" tabindex="-1" href="#" data-size="medium">Medium</a></li>';
					echo '<li role="presentation"><a class="size" role="menuitem" tabindex="-1" href="#" data-size="large">Large</a></li>';
				?>
			</ul>
		</div>
	</div>

	<button href="<?php echo get_the_permalink( get_queried_object_id() ); ?>" class="btn btn-primary" id="remove-filters" type="button" >Remove Filters</button>

	<div class="swiper-year-selector">
		<div class="swiper-wrapper">
			<?php
				$currently_selected = date('Y');
				$earliest_year = 1900;
				$latest_year = date('Y');
				foreach ( range( $latest_year, $earliest_year ) as $i ) {
				 echo '<div class="swiper-slide" role="presentation"><a class="release" role="menuitem" tabindex="-1" href="#" data-release="'.$i.'">'.$i.'</a></div>';
				}
			?>
		</div>
	</div>
</div>

<?php

$genre   = isset($_GET['genre']) ? $_GET['genre'] : '';
$sortby  = isset($_GET['sort_by']) ? $_GET['sort_by'] : '';
$release = isset($_GET['release']) ? $_GET['release'] : '';

for ($i = $start; $i < $end; $i++) {
	$discover[] = $tmdb->getDiscoverMovies($page + $i, $genre, $sortby, $release);
}

if( ! empty ( $discover ) ) {
	echo '<div class="movie-container films">';
  	echo '<div class="movie-container-wrapper grid grid-medium-gap grid-one-tenth display-small">';
			foreach ($discover as $collection) {
				watuwatch_create_movie_grid($collection, 20, 'movie', 'portrait', true);
			}
		echo '</div>';
	echo '</div>';
}

?>

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
$start = $paged == 1 ? 1 : $paged + 2;
$end   = $paged == 1 ? 3 : $start + 2;
$page  = $paged == 1 ? 0 : $paged + 2;
$genres = watuwatch_movie_genre(false);
$sort  = watuwatch_movie_sort_by(false);
?>

<div class="movie-filter-container">
	<div class="btn-group dropdown-filter">
		<div class="dropdown">
			<button class="btn btn-primary dropdown-toggle" id="genre" type="button" data-toggle="dropdown">Genre<span class="caret"></span></button>
			<ul class="dropdown-menu" role="menu" aria-labelledby="genre">
					<li role="presentation"><a class="genre" role="menuitem" tabindex="-1" href="#" data-genre="">All</a></li>
				<?php foreach($genres as $id => $name) { ?>
					<li role="presentation"><a class="genre" role="menuitem" tabindex="-1" href="#" data-genre="<?php echo $id; ?>"><?php echo $name; ?></a></li>
				<?php } ?>
			</ul>
		</div>

		<a href="#" class="btn btn-primary movie-param movie-param-current" data-type="genre">All</a>

		<div class="dropdown">
			<button class="btn btn-primary dropdown-toggle" id="sortby" type="button" data-toggle="dropdown">Sort by<span class="caret"></span></button>
			<ul class="dropdown-menu" role="menu" aria-labelledby="sortby">
				<?php foreach($sort as $id => $name) { ?>
					<li role="presentation"><a class="sortby" role="menuitem" tabindex="-1" href="#" data-sort="<?php echo $name; ?>"><?php echo $id; ?></a></li>
				<?php } ?>
			</ul>
		</div>

		<a href="#" class="btn btn-primary movie-param movie-param-current" data-type="sort">Most Popular</a>

		<div class="dropdown">
			<button class="btn btn-primary dropdown-toggle" id="size" type="button" data-toggle="dropdown">Display<span class="caret"></span></button>
			<ul class="dropdown-menu" role="menu" aria-labelledby="size">
					<li role="presentation"><a class="size" role="menuitem" tabindex="-1" href="#" data-size="small">Small</a></li>
					<li role="presentation"><a class="size" role="menuitem" tabindex="-1" href="#" data-size="medium">Medium</a></li>
					<li role="presentation"><a class="size" role="menuitem" tabindex="-1" href="#" data-size="large">Large</a></li>
			</ul>
		</div>

		<a href="#" class="btn btn-primary movie-param movie-param-current" data-type="size">Small</a>
	</div>

	<button href="<?php echo get_the_permalink( get_queried_object_id() ); ?>" class="btn btn-primary remove-filters" type="button" >Remove Filters</button>

	<div class="swiper-year-selector">
		<div class="swiper-wrapper">
			<?php
			$currently_selected = date('Y');
			$earliest_year = 1900;
			$latest_year = date('Y');
			?>

			<?php foreach ( range( $latest_year, $earliest_year ) as $i ) { ?>
				 <div class="swiper-slide" role="presentation"><a class="release" role="menuitem" tabindex="-1" href="#" data-release="<?php echo $i; ?>"><?php echo $i; ?></a></div>
		  <?php } ?>
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

?>

<?php if( ! empty ( $discover ) ) : ?>
	<div class="movie-container films">
  	<div class="movie-container-wrapper grid grid-medium-gap grid-one-tenth display-small">
			<?php
				foreach ($discover as $movie) {
					watuwatch_create_movie_grid($movie, 20, 'movie', 'portrait', true);
				}
			?>
		</div>
	</div>
<?php endif; ?>

<div class="movie-pagination">
	<?php if( $paged !== 1 ) : ?>
		<button href="#" class="btn btn-primary back">First Page</button>
	<?php endif; ?>
	<?php if( $paged !== 1 ) : ?>
		<button data-paged="<? echo $paged; ?>" class="btn btn-primary filter-page prev" type="button" >Previous Page</button>
	<?php endif; ?>
		<button data-paged="<? echo $paged; ?>" class="btn btn-primary filter-page next" type="button" >Next Page</button>
		<div class="movie-query"><p class="current">Filtered by:</p><a href="#" class="movie-param current-page"><? echo 'Page: ' . $paged; ?></a></div>
</div>

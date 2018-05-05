<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.2
 */

global $wpdb;

$tmdb = new TMDB();

$person = $tmdb->getPerson( get_queried_object_id() );
$movies = $person->getMovieRoles();

?>

<div id="poster">
  <div class="portrait movie-item">
	<?php if ( 'person' !== get_post_type() ) return; ?>
	<?php if( ! empty( get_the_post_thumbnail_url( get_queried_object_id() ) ) ) : ?>
    <a href="#">
      <img class="placeholder" src="<?php echo get_theme_file_uri( '/img/placeholder.jpg' ); ?>" />
      <img class="real" src="<?php echo get_the_post_thumbnail_url( get_queried_object_id() ); ?>" title="<?php echo get_the_title(); ?>" alt="<?php get_the_title(); ?>" />
    </a>

		<div class="excerpt">
			<h5>Bio</h5>
			<span class="more">
				<?php echo get_the_excerpt(); ?>
			</span>
		</div><!-- .excerpt -->
  <?php endif; ?>
  </div>
</div><!-- #poster -->

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

				<ul class="movie-entry-meta">
					<?php if( ! empty ( $person->getBirthday() ) ) : ?>
						<li><label>Born on: </label><?php echo date( "F d, Y", strtotime( $person->getBirthday() ) ); ?></li>
					<?php endif; ?>

					<?php if( ! empty ( $person->getPlaceOfBirth() ) ) : ?>
						<li><label>Origin: </label><?php echo $person->getPlaceOfBirth(); ?></li>
					<?php endif; ?>
				</ul>

				<?php if( ! empty ( $tag ) ) : ?>
					<span class="entry-tagline"><?php echo $tag; ?></span>
				<?php endif; ?>
			</header><!-- .entry-header -->

			<?php if( ! empty ( $movies ) ) : ?>
				<div class="movie-container">
					<div class="movie-container-wrapper grid grid-medium-gap grid-one-fifth display-medium">
						<?php watuwatch_create_movie_grid($movies, 100, 'movie', 'portrait', true); ?>
					</div>
				</div>
			<?php endif; ?>
		</article><!-- #post-## -->
	</main><!-- #main -->
</div><!-- #primary -->

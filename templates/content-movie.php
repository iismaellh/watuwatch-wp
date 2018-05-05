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

 /* extract movie data */
 $movie    = $tmdb->getMovie( get_the_ID() );
 $trailer  = $movie->getTrailers();
 $director = $movie->getDirectorIds();
 $similar  = $movie->getSimilarMovies(false, true, 6 , get_the_ID());
 $backdrop = get_post_meta( get_the_ID(), '_watu_movie_backdrop_url', true );
?>

<div id="poster" class="movie-container">
  <div class="portrait movie-item">
	<?php  if( ! empty( get_the_post_thumbnail_url( get_queried_object_id() ) ) ) : ?>
    <a href="#">
      <img class="placeholder" src="<?php echo get_theme_file_uri( '/img/placeholder.jpg' ); ?>" />
      <img class="real" src="<?php echo get_the_post_thumbnail_url( get_queried_object_id() ); ?>" title="<?php echo get_the_title(); ?>" alt="<?php get_the_title(); ?>" />
    </a>
  <?php endif; ?>
  </div>
</div><!-- #poster -->

<div id="primary" class="content-area">
  <main id="main" class="site-main" role="main">

      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
          <?php if ( 'movie' !== get_post_type() ) return; ?>

          <?php the_title( '<h1 class="movie-title">', '<span class="year">'.explode("-", $movie->getReleaseDate())[0].'</span></h1>' ); ?>
        </header><!-- .entry-header <span class="director">'.$movie->getDirectorIds()[0].'</span> -->

        <div class="entry-content">
          <div class="excerpt">
            <?php if( ! empty ( $movie->getTagline() ) ) : ?>
              <p class="movie-tagline"><?php echo $movie->getTagline(); ?></p>
            <?php endif; ?>

            <?php the_excerpt( sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ), get_the_title() ) ); ?>
          </div> <!-- .excerpt -->

          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#casts">Casts</a></li>
            <li><a data-toggle="tab" href="#crews">Crews</a></li>
            <li><a data-toggle="tab" href="#info">Info</a></li>
          </ul>

          <div class="tab-content">
            <div id="casts" class="tab-pane fade in active">
              <?php  if( ! empty ( $movie->getCasts(true, true, -1) ) ) : ?>
                <?php watuwatch_create_person_grid($movie->getCasts(true, true, -1), -1, 'person', 'tooltip', true); ?>
              <?php endif; ?>
            </div>

            <div id="crews" class="tab-pane fade">
              <?php if( ! empty ( $movie->getCrews(true, true, -1) ) ) : ?>
                <?php
                foreach( $movie->getCrews(true, true, -1) as $crew ) {
                  if( $crew['department'] === 'Directing' ) {
                    $directing[] = $crew;
                  }

                  if( $crew['department'] === 'Editing' ) {
                    $editing[] = $crew;
                  }

                  if( $crew['department'] === 'Writing' ) {
                    $writing[] = $crew;
                  }

                  if( $crew['department'] === 'Sound' ) {
                    $sounds[] = $crew;
                  }
                }
                ?>

                <?php  if( ! empty ( $directing ) ) : ?>
                  <?php watuwatch_create_person_grid($directing, -1, 'person', 'taglist', true, 'Directing'); ?>
                <?php endif; ?>

                <?php  if( ! empty ( $editing ) ) : ?>
                  <?php watuwatch_create_person_grid($editing, -1, 'person', 'taglist', true, 'Editing'); ?>
                <?php endif; ?>

                <?php  if( ! empty ( $writing ) ) : ?>
                  <?php watuwatch_create_person_grid($writing, -1, 'person', 'taglist', true, 'Writing'); ?>
                <?php endif; ?>

                <?php  if( ! empty ( $sounds ) ) : ?>
                  <?php watuwatch_create_person_grid($sounds, -1, 'person', 'taglist', true, 'Sounds'); ?>
                <?php endif; ?>
            <?php endif; ?>
            </div>

            <div id="info" class="tab-pane fade">
              <?php
              if( ! empty ( $movie->getCompanies() ) ) {
                $companies = array();
                foreach( $movie->getCompanies() as $comp ) {
                  $companies[] = $comp->getName();
                }
                watuwatch_create_tag_list( $companies, 'Companies', 'object' );
              }

              if( ! empty( $movie->getCountries() ) ) {
                watuwatch_create_tag_list( $movie->getCountries(), 'Countries', 'object' );
              }

              if( ! empty( $movie->get('spoken_languages') ) ) {
                watuwatch_create_tag_list( $movie->get('spoken_languages'), 'Languages', 'default' );
              }

              if( ! empty( $movie->getGenres() ) ) {
                $genres = array();
                foreach( $movie->getGenres() as $gen ) {
                  $genres[] = $gen->getName();
                }
                watuwatch_create_tag_list( $genres, 'Genres', 'object' );
              }

              if( ! empty( $movie->get('runtime') ) ) {
                watuwatch_create_tag_list( $movie->get('runtime'), 'Runtime', 'string', '', ' minutes' );
              }

              if( ! empty( $movie->get('budget') ) ) {
                watuwatch_create_tag_list( number_format( $movie->get('budget'), 2 ), 'Budget', 'string', '$ ' );
              }

              if( ! empty( $movie->get('revenue') ) ) {
                watuwatch_create_tag_list( number_format( $movie->get('revenue'), 2 ), 'Revenue', 'string', '$ ' );
              }
              ?>
            </div>
          </div>
        </div><!-- .entry-content -->
      </article><!-- #post-## -->
		</main><!-- #main -->
	</div><!-- #primary -->
  <div id="secondary">
    <div class="doughnut-container">
      <div data-doughnut>
        <h5>Vote Average <br /><span class="score"><?php echo round( $movie->getVoteAverage(), 2 ); ?></span> out of 10</h5>
        <div data-doughnut-value="<?php echo round( $movie->getVoteAverage() * 10 ); ?>">
        </div>
      </div>
    </div>
  </div><!-- #secondary -->
  <?php if( ! empty ( $similar ) ) : ?>
  	<div class="related movie-container">
      <h5>Similar Movies</h5>
    	<div class="movie-container-wrapper grid grid-medium-gap grid-one-tenth display-small">
  			<?php watuwatch_create_movie_grid($similar, 10, 'movie', 'portrait', true); ?>
  		</div>
  	</div>
  <?php endif; ?>

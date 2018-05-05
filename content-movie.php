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
 $tag      = $movie->getTagline();
 $runtime  = $movie->get('runtime');
 $budget   = $movie->get('budget');
 $revenue  = $movie->get('revenue');
 $vcount   = $movie->getVoteCount();
 $vaverage = $movie->getVoteAverage();
 $genre    = $movie->getGenres();
 $langs    = $movie->get('spoken_languages');
 $date     = $movie->getReleaseDate();
 $casts    = $movie->getCasts(true, true, -1);
 $crews    = $movie->getCrews(true, false, -1);
 $crewdata = $movie->getCrews(true, true);
 $director = $movie->getDirectorIds();
 $company  = $movie->getCompanies();
 $country  = $movie->getCountries();
 $similar  = $movie->getSimilarMovies(true, false, 6 , get_the_ID());
 $backdrop = get_post_meta( get_the_ID(), '_watu_movie_backdrop_url', true );
 $poster   = get_the_post_thumbnail_url( get_queried_object_id() );
 $holder   = get_template_directory_uri() . '/img/placeholder.jpg';
?>

<div id="poster" class="movie-container">
  <div class="portrait movie-item">
	<?php
  if( ! empty( $poster ) ) {
    $output  = '';
    $output .= '<a href="#">';
    $output .= '<img class="placeholder" src="'.$holder.'" />';
    $output .= '<img class="real" src="'.$poster.'" title="'.get_the_title().'" alt="'.get_the_title().'" />';
    $output .= '</a>';
    echo $output;
  }
  ?>
  </div>
</div><!-- #poster -->

<div id="primary" class="content-area">
  <main id="main" class="site-main" role="main">

      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
          <?php
          if ( 'movie' !== get_post_type() ) return;

          the_title( '<h1 class="movie-title">', '</h1>' );
          //echo '<pre>' . var_export($movie, true) . '</pre>';

          if ( is_single() ) {
            echo '<div class="movie-meta-container">';
              echo '<ul class="movie-entry-meta">';
                if( ! empty( $date ) ) {
                  echo '<li><label>Released:</label>'.date( "F d, Y", strtotime( $date ) ).'</li>';
                }
              echo '</ul>';
            }
          echo '</div><!-- .entry-meta-container -->';
          ?>
        </header><!-- .entry-header -->

        <div class="entry-content">
          <?php
          echo '<div class="excerpt">';

            if( ! empty ( $tag ) ) echo '<p class="movie-tagline">'.$tag.'</p>';

            the_excerpt( sprintf(
              __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ),
              get_the_title()
            ) );
          echo '</div>';
          ?>
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#casts">Casts</a></li>
            <li><a data-toggle="tab" href="#crews">Crews</a></li>
            <li><a data-toggle="tab" href="#info">Info</a></li>
            <li><a data-toggle="tab" href="#stats">Stats</a></li>
          </ul>

          <div class="tab-content">
            <div id="casts" class="tab-pane fade in active">
              <?php
              if( ! empty ( $casts ) ) {
                watuwatch_create_movie_grid($casts, -1, 'person', 'tooltip', true);
              }
               ?>
            </div>
            <div id="crews" class="tab-pane fade">
              <?php
              if( ! empty ( $crews ) ) {
                foreach( $crewdata as $crew ) {
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

                watuwatch_create_tag_list( $directing, 'Directing', 'tooltip' );

                watuwatch_create_tag_list( $editing, 'Editing', 'tooltip' );

                watuwatch_create_tag_list( $writing, 'Writing', 'tooltip' );

                watuwatch_create_tag_list( $sounds, 'Sound', 'tooltip' );
              }
               ?>
            </div>

            <div id="info" class="tab-pane fade">
              <?php
              if( ! empty ( $company ) ) {
                $companies = array();
                foreach( $company as $comp ) {
                  $companies[] = $comp->getName();
                }
                watuwatch_create_tag_list( $companies, 'Companies', 'object' );
              }

              if( ! empty( $country ) ) {
                watuwatch_create_tag_list( $country, 'Countries', 'object' );
              }

              if( ! empty( $langs ) ) {
                watuwatch_create_tag_list( $langs, 'Languages', 'default' );
              }

              if( ! empty( $genre ) ) {
                $genres = array();
                foreach( $genre as $gen ) {
                  $genres[] = $gen->getName();
                }
                watuwatch_create_tag_list( $genres, 'Genres', 'object' );
              }
              ?>
            </div>

            <div id="stats" class="tab-pane fade">
              <?php
              if( ! empty( $runtime ) ) {
                watuwatch_create_tag_list( $runtime, 'Runtime', 'string', '', ' minutes' );
              }

              if( ! empty( $budget ) ) {
                watuwatch_create_tag_list( number_format( $budget, 2 ), 'Budget', 'string', '$ ' );
              }

              if( ! empty( $revenue ) ) {
                watuwatch_create_tag_list( number_format( $revenue, 2 ), 'Revenue', 'string', '$ ' );
              }

              if( ! empty( $vcount ) ) {
                watuwatch_create_tag_list( number_format( $vcount, 0 ), 'Vote Count', 'string' );
              }

              if( ! empty( $vaverage ) ) {
                watuwatch_create_tag_list( number_format( $vaverage, 0 ), 'Vote Average', 'string', '', ' out of 10' );
              }
              ?>
            </div>
          </div>
        </div><!-- .entry-content -->
      </article><!-- #post-## -->
		</main><!-- #main -->
	</div><!-- #primary -->

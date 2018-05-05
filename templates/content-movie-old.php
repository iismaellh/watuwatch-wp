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
 $genre    = $movie->getGenres();
 $langs    = $movie->get('spoken_languages');
 $date     = $movie->getReleaseDate();
 $casts    = $movie->getCasts(true, true, 5);
 $crews    = $movie->getCrews(true, false, 5);
 $crewdata = $movie->getCrews(true, true);
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
          if ( 'movie' !== get_post_type()) return;

          if( ! empty ( $trailer ) ) {
            echo '<a href="#" data-trailer="'.$trailer['results'][0]['key'].'" class="trailer-button button" data-show-id="1">Watch Trailer</a>';
          }

          if( ! empty ( $date )) {
            $year = '<span class="movie-year">( '. explode( '-', $date )[0] . ' )</span>';
          }

          the_title( '<h1 class="movie-title">', $year . '</h1>' );

          if( ! empty ( $tag ) ) echo '<span class="movie-tagline">'.$tag.'</span>';

          if ( is_single() ) {
            echo '<div class="entry-meta-container">';
              echo '<ul class="entry-meta">';
                if( ! empty( $date ) ) {
                  echo '<li><label>Released:</label>'.date( "F d, Y", strtotime( $date ) ).'</li>';
                }

                if( ! empty( $runtime ) ) {
                  echo '<li><label>Runtime:</label>'.$runtime.' Minutes</li>';
                }

                if( ! empty( $langs ) ) {
                  echo '<li><label>Language:</label>';
                    foreach( $langs as $key => $lang ) {
                      echo $lang['name'];
                      if ( end ( array_keys( $langs ) ) != $key ) echo ', ';
                    }
                  echo '</li>';
                }

                if( ! empty( $budget ) ) {
                  echo '<li><label>Budget:</label>$'.number_format( $budget, 2 ).'</li>';
                }

                if( ! empty( $revenue ) ) {
                  echo '<li><label>Revenue:</label>$'.number_format( $revenue, 2 ).'</li>';
                }

                if( ! empty( $genre ) ) {
                  echo '<li><label>Genre:</label>';
                    foreach( $genre as $key => $gen ) {
                      echo $gen->getName();
                      if ( end ( array_keys( $genre ) ) != $key ) echo ', ';
                    }
                  echo '</li>';
                }
              echo '</ul>';
            }
          echo '</div><!-- .entry-meta-container -->';

          ?>
        </header><!-- .entry-header -->

        <div class="entry-content">
          <?php
          echo '<div class="excerpt">';
            echo '<h5>Storyline</h5>';
            the_excerpt( sprintf(
              __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ),
              get_the_title()
            ) );
          echo '</div>';

          if( ! empty ( $casts ) ) {
            echo '<div class="cast-profiles">';
              echo '<h5>Casts</h5>';
              echo '<div class="cast-profiles-wrapper">';
                  foreach( $casts as $key => $cast ) {
                    $castid = $cast['id'];

                    $post = $wpdb->get_col(
                      "
                      SELECT ID
                      FROM $wpdb->posts
                      WHERE post_tmdb = $castid
                        AND post_type = 'person'
                      "
                    );

                    if( empty( $post ) ) {
                      continue;
                    }

                    $post = array_map( 'intval', $post);

                    $title = get_the_title( $post[0] );
                    $img   = get_the_post_thumbnail( $post[0] );
                    $link  = get_the_permalink( $post[0] );

                    $output  = '<div class="cast-profiles-slide">';
                    $output .= '<a href="'.$link.'" title="'.$title.'" alt="'.$title.'">';
                    $output .= '<span class="cast-profiles-slide-img">'.$img.'</span>';
                    $output .= '<span class="cast-profiles-slide-title">'.$title.'</span>';
                    $output .= '<span class="cast-profiles-slide-character">'.$cast['character'].'</span>';
                    $output .= '<span class="clear"></span>';
                    $output .= '</a>';
                    $output .= '</div>';
                    echo $output;
                  }
              echo '</div>';
              echo '<div class="clear"></div>';
            echo '</div>';
          }


          if( ! empty ( $crews ) ) {
            echo '<h5>Credits</h5>';
            echo '<div class="movie-credits swiper-container">';
              echo '<div class="swiper-wrapper">';
                foreach( array_unique( $crews ) as $key => $value ) {
                  $img = get_the_post_thumbnail( $value );
                  if( ! empty( $img )) {
                    foreach( $crewdata as $crew ) {
                      //echo '<pre>' . var_export($crew, true) . '</pre>';
                      if( $crew['department'] === 'Directing' ) {
                        if( $crew['job'] === 'Director' ) {
                          echo twentyseventeen_create_credits_table($crew['name'], 'directed by');
                        }
                      }

                      if( $crew['department'] === 'Editing' ) {
                        if( $crew['job'] === 'Editor' ) {
                          echo twentyseventeen_create_credits_table($crew['name'], 'edited by');
                        }
                      }

                      if( $crew['department'] === 'Writing' ) {
                        if( $crew['job'] === 'Writer' ) {
                          echo twentyseventeen_create_credits_table($crew['name'], 'written by');
                        }

                        if( $crew['job'] === 'Comic Book' ) {
                          echo twentyseventeen_create_credits_table($crew['name'], 'comic book by');
                        }
                      }

                      if( $crew['department'] === 'Sound' ) {
                        if( $crew['job'] === 'Sound Designer' ) {
                          echo twentyseventeen_create_credits_table($crew['name'], 'sound design by');
                        }

                        if( $crew['job'] === 'Sound Re-Recording Mixer' ) {
                          echo twentyseventeen_create_credits_table($crew['name'], 'sound re-recorded by');
                        }

                        if( $crew['job'] === 'Music Editor' ) {
                          echo twentyseventeen_create_credits_table($crew['name'], 'music edited by');
                        }

                        if( $crew['job'] === 'Original Music Composer' ) {
                          echo twentyseventeen_create_credits_table($crew['name'], 'music composed by');
                        }

                        if( $crew['job'] === 'Music Supervisor' ) {
                          echo twentyseventeen_create_credits_table($crew['name'], 'music supervised by');
                        }

                        if( $crew['job'] === 'Sound Effects Editor' ) {
                          echo twentyseventeen_create_credits_table($crew['name'], 'sound effects edited by');
                        }

                        if( $crew['job'] === 'Supervising Sound Editor' ) {
                          echo twentyseventeen_create_credits_table($crew['name'], 'sound supervised by');
                        }

                        if( $crew['job'] === 'Foley' ) {
                          echo twentyseventeen_create_credits_table($crew['name'], 'foley by');
                        }
                      }
                    }
                  }
                }
              echo '</div>';
              echo '<div class="swiper-scrollbar"></div>';
            echo '</div>';
          }

          wp_link_pages( array(
            'before'      => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
            'after'       => '</div>',
            'link_before' => '<span class="page-number">',
            'link_after'  => '</span>',
          ) );
          ?>
        </div><!-- .entry-content -->

        <?php
        if ( is_single() ) {
          twentyseventeen_entry_footer();
        }
        ?>
      </article><!-- #post-## -->
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

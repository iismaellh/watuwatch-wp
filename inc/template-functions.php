<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package watuwatch
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function watuwatch_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'watuwatch_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function watuwatch_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'watuwatch_pingback_header' );


/**
 * Create movie credits table
 */
function watuwatch_create_toggle_tooltip($name, $job, $link, $echo = false) {
	$output  = '';
	$output .= '<a href="'.$link.'" class="tooltip-toggle" data-toggle="tooltip" title="'.$job.'">'.$name.'</a>';

	if( $echo == true ) {
		echo $output;
	} else {
		return $output;
	}
}

/**
 * Create role credits table
 */
function watuwatch_create_person_role_table($link, $title, $character, $year, $echo = false) {
	$output .= '<tr><td class="year">'.$year.'</td><td class="name"><a href="'.$link.'" title="'.$title.'" alt="'.$title.'">'.$title.'</a></td><td>as</td><td class="credit">'.$character.'</td></tr>';

	if( $echo == true ) {
		echo $output;
	} else {
		return $output;
	}
}

/**
 * Create pagination
 */
function watuwatch_custom_pagination($total, $midsize) {
	global $wp_query;
	// only bother with the rest if we have more than 1 page!
	if ( $total > 1 )  {
	 // get the current page
	 if ( !$current_page = get_query_var('paged') )
				$current_page = 1;
	 // structure of "format" depends on whether we're using pretty permalinks
	 $format = 'page/%#%/';

	 echo paginate_links(array(
		'base'     => get_pagenum_link(1) . '%_%',
		'format'   => $format,
		'current'  => $current_page,
		'total'    => $total,
		'mid_size' => 1,
		'prev_text' => __('Previous Page'),
		'next_text' => __('See more'),
		'type'     => 'list'
	 ));
	}
}

/**
 * Display movie items on archive
 */
function watuwatch_movie_archive_query( $query )
{
	if ( ! $query->is_main_query() OR ! $query->is_archive() ) return $query;

	if(!is_admin()) {
		$query->set( 'post_type', array( 'post', 'movie' ) );
	}

	return $query;
}
add_filter( 'pre_get_posts', 'watuwatch_movie_archive_query' );

/**
 * Display movie items on gender archive
 */
function watuwatch_movie_gender_query($query) {
	if (!is_admin() && is_tax('genders') && $query->is_tax)
			$query->set( 'post_type', array('person', 'post') );
	remove_action( 'pre_get_posts', 'watuwatch_movie_gender_query' );
}
add_action('pre_get_posts', 'watuwatch_movie_gender_query');

/**
 * Disable the emoji's
 */
function disable_emojis() {
 remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
 remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
 remove_action( 'wp_print_styles', 'print_emoji_styles' );
 remove_action( 'admin_print_styles', 'print_emoji_styles' );
 remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
 remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
 remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
 add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
 add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param array $plugins
 * @return array Difference betwen the two arrays
 */
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
	return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
	return array();
	}
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Difference betwen the two arrays.
 */
function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' == $relation_type ) {
	/** This filter is documented in wp-includes/formatting.php */
	$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
	$urls = array_diff( $urls, array( $emoji_svg_url ) );
	}

	return $urls;
}

/**
 * Return movie sort by options array
 *
 * @param bool $echo echo the sort
 * @return array
 */
function watuwatch_movie_sort_by($echo = false) {
	$sort = array (
	  'Most Popular' => 'popularity.desc',
	  'Least Popular' => 'popularity.asc',
	  'Latest' => 'release_date.desc',
	  'Oldest' => 'release_date.asc',
	  'Highest Grossing' => 'revenue.asc',
	  'Highest Vote Average' => 'vote_average.desc',
	  'Lowest Vote Average' => 'vote_average.asc',
	  'Highest Vote Count' => 'vote_count.desc',
	  'Lowest Vote Count' => 'vote_count.asc',
	);

	return $sort;
}

/**
 * Return movie genre array
 *
 * @param bool $echo echo the genres
 * @return array
 */
function watuwatch_movie_genre($echo) {
	$genres = array (
	  28 => 'Action',
	  12 => 'Adventure',
	  16 => 'Animation',
	  35 => 'Comedy',
	  80 => 'Crime',
	  99 => 'Documentary',
	  18 => 'Drama',
	  10751 => 'Family',
	  14 => 'Fantasy',
	  36 => 'History',
	  27 => 'Horror',
	  10402 => 'Music',
	  9648 => 'Mystery',
	  10749 => 'Romance',
	  878 => 'Science Fiction',
	  10770 => 'TV Movie',
	  53 => 'Thriller',
	  10752 => 'War',
	  37 => 'Western',
	);

	return $genres;
}

 /**
  * Display movies items in a grid
  *
	* @param array $source json data or the movies collection source
	* @param int $limit limit of movie items : default 20
	* @param string $orientation portrait or landscape movie items
	* @param bool $echo enable next or previous nav
	* @return string
  */
	function watuwatch_create_movie_grid($source = '', $limit = 20, $type = 'movie', $style = 'portrait', $echo = false) {
		$placeholder = get_template_directory_uri() . '/img/placeholder.jpg';
		$output = '';
		
		foreach( array_slice( $source, 0, $limit ) as $key => $value ) {
			$id = $type == 'person' ? $value['id'] : $value->getID();
			$post = watuwatch_query_posts( $id, $type );

			if( empty( $post ) ) {
				continue;
			}

			$title = get_the_title( $post[0] );
			$link  = get_the_permalink( $post[0] );
			$img = get_the_post_thumbnail_url( $post[0], 'medium' );
			$backdrop = get_post_meta( $post[0], '_watu_movie_backdrop_url', true );

			if( ! empty( $post[0] ) ) {
				if ( $style == 'landscape' )
				{
					$output .= '<div class="landscape movie-item" style="background-image: url('.$backdrop.');">';
					//$output .= '<span class="swiper-image"><img src="'.$backdrop.'" title="'.$title.'" alt="'.$title.'"/></span>';
					$output .= '<a href="'.$link.'" class="movie-item-link">';
					$output .= '<h2 class="movie-item-title">'.$title.'<span class="movie-item-year">'.explode( '-', $value->get('released_date') )[0].'</span></h2>';
					$output .= '<span class="clear"></span>';
					$output .= '</a>';
					$output .= '</div>';
				}
				elseif ( $style == 'portrait' )
				{
					$output .= '<div class="portrait movie-item">';
					$output .= '<a href="'.$link.'">';
					$output .= '<img class="placeholder" src="'.$placeholder.'" />';
					$output .= '<img class="real" data-src="'.$img.'" title="'.$title.'" alt="'.$title.'" />';
					$output .= '</a>';
					$output .= '</div>';
				}
				elseif ( $style == 'tooltip' )
				{
					//$output .= '<a href="'.$link.'" class="tooltip-toggle" data-toggle="tooltip" title="'.$value['character'].'">'.$title.'</a>';
					$output .= '<a href="'.$link.'" class="tooltip-toggle" data-toggle="tooltip" title="'.$value['character'].'">'.$title.'</a>';
				}
				else
				{
					$output .= '<a href="'.$link.'" class="swiper-slide" style="background-image: url('.$backdrop.');" title="'.$title.'" alt="'.$title.'">';
					$output .= '<span class="swiper-link">';
					$output .= '<h2 class="swiper-title">'.$title.'<span class="swiper-year">'.explode( '-', $value->get('release_date') )[0].'</span></h2>';
					//$output .= '<span class="swiper-image">'.$img.'</span>';
					//$output .= '<span class="swiper-overview">'.$value->get('overview').'</span>';
					$output .= '</span>';
					$output .= '</a>';
				}
			}
		}

		if( $echo == true ) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * Display movies items in a grid
	 *
	 * @param array $source json data or the movies collection source
	 * @param int $limit limit of movie items : default 20
	 * @param string $orientation portrait or landscape movie items
	 * @param bool $echo enable next or previous nav
	 * @return string
	 */
	 function watuwatch_create_actors_grid($source = '', $limit = 20, $type = 'person', $style = 'portrait', $echo = false) {
		 $placeholder = get_template_directory_uri() . '/img/placeholder.jpg';
		 $output = '';

		 foreach( array_slice( $source, 0, $limit ) as $key => $value ) {
			 $id = $value->getID();
			 $post = watuwatch_query_posts( $id, $type );

			 if( empty( $post ) ) {
				 continue;
			 }

			 $title = get_the_title( $post[0] );
			 $link  = get_the_permalink( $post[0] );
			 $img = get_the_post_thumbnail_url( $post[0], 'medium' );

			 if( ! empty( $post[0] ) ) {
				 if ( $style == 'portrait' )
				 {
					 $output .= '<div class="portrait movie-item">';
					 $output .= '<a href="'.$link.'">';
					 $output .= '<img class="placeholder" src="'.$placeholder.'" />';
					 $output .= '<img class="real" data-src="'.$img.'" title="'.$title.'" alt="'.$title.'" />';
					 $output .= '</a>';
					 $output .= '</div>';
				 }
				 elseif ( $style == 'tooltip' )
				 {
					 $output .= '<a href="'.$link.'" class="tooltip-toggle" data-toggle="tooltip" title="'.$value['character'].'">'.$title.'</a>';
				 }
				 else
				 {
					 $output .= '<a href="'.$link.'" class="swiper-slide" style="background-image: url('.$backdrop.');" title="'.$title.'" alt="'.$title.'">';
					 $output .= '<span class="swiper-link">';
					 $output .= '<h2 class="swiper-title">'.$title.'<span class="swiper-year">'.explode( '-', $value->get('release_date') )[0].'</span></h2>';
					 //$output .= '<span class="swiper-image">'.$img.'</span>';
					 //$output .= '<span class="swiper-overview">'.$value->get('overview').'</span>';
					 $output .= '</span>';
					 $output .= '</a>';
				 }
			 }
		 }

		 if( $echo == true ) {
			 echo $output;
		 } else {
			 return $output;
		 }
	 }

	/**
	 * Display person posts
	 *
	 * @param array $source json data or the persons
	 * @param int $limit limit of person items : default 20
	 * @param string $orientation taglist or tooltip person items
	 * @return string
	 */
	 function watuwatch_create_person_grid($source = '', $limit = 20, $type = 'person', $style = 'tooltip', $echo = false, $dep = '') {
		 $placeholder = get_template_directory_uri() . '/img/placeholder.jpg';
		 $output  = '';
		 $output .= ! empty ( $dep ) ? '<h3><span>'.$dep.'</span></h3>' : '';
		 $output .= ! empty ( $dep ) ? "<div class='tag_list'>" : '';
			foreach( array_slice( $source, 0, $limit ) as $key => $value ) {
				$id = $type == 'person' ? $value['id'] : $value->getID();
				$post = watuwatch_query_posts( $id, $type );
				
				$title = ! empty( $post[0] ) ? get_the_title( $post[0] ) : $value['name'];
				$link  = ! empty( $post[0] ) ? get_the_permalink( $post[0] ) : '#';
				$img   = get_the_post_thumbnail_url( $post[0], 'thumbnail' );

				if ( $style == 'tooltip' ) {
					$output .= '<a href="'.$link.'" class="tooltip-toggle" data-toggle="tooltip" title="'.$value['character'].'">'.$title.'</a>';
				} else {
					$output .= '<a href="'.$link.'" class="tooltip-toggle" data-toggle="tooltip" alt="'.$title.'" title="'.$value['job'].'">'.$title.'</a>';
				}
			}
		 $output .= ! empty ( $dep ) ? "</div>" : '';

		 if( $echo == true ) {
			 echo $output;
		 } else {
			 return $output;
		 }
	 }

	/**
	 * Create tag list
	 */
	function watuwatch_create_tag_list( $sources, $title, $type = 'default', $prepend = '', $append = '' ) {
		if( empty ( $sources ) ) return;
		$output = '<h3><span>'.$title.'</span></h3>';
		$output .= "<div class='tag_list'>";
		if( $type !== 'string' ) {
			foreach( $sources as $source ) {
				if( $type == 'tooltip' ) {
					$output .= watuwatch_create_toggle_tooltip($source['name'], $source['job'], '#');
				} elseif ( $type == 'object' ) {
					$output .= '<a href="#" class="tooltip-toggle" data-toggle="tooltip" alt="'.$source.'">'.$source.'</a>';
				} else {
					$output .= '<a href="#" class="tooltip-toggle" data-toggle="tooltip" alt="'.$source['name'].'">'.$source['name'].'</a>';
				}
			}
		} else {
			$output .= '<a href="#" class="tooltip-toggle" data-toggle="tooltip" alt="'.$sources.'">'.$prepend.$sources.$append.'</a>';;
		}
		$output .= "</div>";

		echo $output;
	}

	/**
	 * Query movie and person posts
	 *
	 * @param int $id post id
	 * @param string $type movie or person post type
	 * @return string
	 */
	function watuwatch_query_posts($id, $type) {
		global $wpdb;

		$post = $wpdb->get_col(
			"
			SELECT ID
			FROM $wpdb->posts
			WHERE post_tmdb = $id
				AND post_type =  '$type'
			"
		);

		$post = array_map( 'intval', $post);

		return $post;
	}

<?php
/**
 * 	This class handles all the data you can get from a Movie
 *
 *	@package TMDB-V3-PHP-API
 * 	@author Alvaro Octal | <a href="https://twitter.com/Alvaro_Octal">Twitter</a>
 * 	@version 0.2
 * 	@date 02/04/2016
 * 	@link https://github.com/Alvaroctal/TMDB-PHP-API
 * 	@copyright Licensed under BSD (http://www.opensource.org/licenses/bsd-license.php)
 */

class Movie{

	//------------------------------------------------------------------------------
	// Class Constants
	//------------------------------------------------------------------------------

	const MEDIA_TYPE_MOVIE = 'movie';

	//------------------------------------------------------------------------------
	// Class Variables
	//------------------------------------------------------------------------------

	public $_data;
	private $_tmdb;

	/**
	 * 	Construct Class
	 *
	 * 	@param array $data An array with the data of the Movie
	 */
	public function __construct($data) {
		$this->_data = $data;
	}

	//------------------------------------------------------------------------------
	// Get Variables
	//------------------------------------------------------------------------------

	/**
	 * 	Get the Movie id
	 *
	 * 	@return int
	 */
	public function getID() {
		return $this->_data['id'];
	}

	/**
	 * 	Get the Movie title
	 *
	 * 	@return string
	 */
	public function getTitle() {
		return $this->_data['title'];
	}

	/**
	 * 	Get the Movie tagline
	 *
	 * 	@return string
	 */
	public function getTagline() {
		return $this->_data['tagline'];
	}

	/**
	 * 	Get the Movie release date
	 *
	 * 	@return string
	 */
	public function getReleaseDate() {
		return $this->_data['release_date'];
	}

	/**
	 * 	Get the Movie Directors IDs
	 *
	 * 	@return Array(int)
	 */
	public function getDirectorIds() {
		$director_ids = array();

		$crew = $this->_data['credits']['crew'];

		foreach ($crew as $crew_member) {

			if ($crew_member['job'] == 'Director'){
				array_push($director_ids, $crew_member["id"]);
			}
		}
		return $director_ids;
	}

	/**
	 * 	Get the Movie Casts
	 *
	 * 	@return Object
	 */
	public function getCasts( $id = false, $data = false, $posts_per_page = 0 ) {
		global $wpdb;

		$casts = array();

		$cast = $this->_data['credits']['cast'];

		if( $data == true ) return array_slice( $cast, 0, $posts_per_page );;

		if( !empty( $cast ) ) {
			foreach ( $cast as $cast_member ) {
					array_push( $casts, $cast_member['id']);
			}
		}

		$extract = array();

		foreach( $casts as $cast ) {
				$extract[] = $wpdb->get_col(
					"
					SELECT ID
					FROM $wpdb->posts
					WHERE post_tmdb = $cast
						AND post_type = 'person'
					"
				);
		}

		$postids = array();

		foreach( $extract as $id => $value ) {
				$postids[] = $value[0];
		}

		$postids = array_filter( $postids );

		if( $posts_per_page != 0 ) $postids = array_slice( $postids, 0, $posts_per_page );

		if( $id == true ) {
			 return $postids;
		} else {
			if( !empty( $postids[0] ) ) {
				$args = array(
					'post__in' => $postids,
					'orderby' => 'post__in',
					'post_type' => 'person'
				);

				$actors = get_posts( $args );
				return $actors;
			} else {
				return 'No casts found.';
 			}
		}
	}

	/**
	 * 	Get the Movie Crews
	 *
	 * 	@return Object
	 */
	public function getCrews( $id = false, $data = false, $posts_per_page = 0 ) {
		global $wpdb;

		$crews = array();

		$crew = $this->_data['credits']['crew'];

		if( $data == true ) return $crew;

		if( !empty( $crew ) ) {
			foreach ( $crew as $crew_member ) {
					array_push( $crews, $crew_member['id']);
			}
		}

		$extract = array();

		foreach( $crews as $key => $crew ) {
				$extract[] = $wpdb->get_col(
					"
					SELECT ID
					FROM $wpdb->posts
					WHERE post_tmdb = $crew
						AND post_type = 'person'
					"
				);
		}


		$postids = array();

		foreach( $extract as $id => $value ) {
			if( ! isset( $value[0] ) ) continue;
			$postids[] = $value[0];
		}

		$postids = array_filter( $postids );

		if( $posts_per_page != 0 ) $postids = array_slice( $postids, 0, $posts_per_page );

		if( $id == true ) {
			 return $postids;
		} else {
			if( !empty( $postids[0] ) ) {
			$args = array(
				'post__in' => $postids,
				'orderby' => 'post__in',
				'post_type' => 'person'
			);

			$crews = get_posts( $args );
				return $crews;
			} else {
				return 'No crews found.';
 			}
		}
	}

	/**
	 * 	Get the Similar Movies
	 *
	 * 	@return Array(int)
	 */
	public function getSimilarMovies() {
		global $wpdb;

		$movies = array();

		foreach($this->get('similar_movies')['results'] as $data){
				$movies[] = new Movie($data);
		}

		return $movies;
	}

	/**
	 * 	Get the Movie Poster
	 *
	 * 	@return string
	 */
	public function getPoster() {
		return $this->_data['poster_path'];
	}

	/**
	 * 	Get the Movie vote average
	 *
	 * 	@return int
	 */
	public function getVoteAverage() {
		return $this->_data['vote_average'];
	}

	/**
	 * 	Get the Movie vote count
	 *
	 * 	@return int
	 */
	public function getVoteCount() {
		return $this->_data['vote_count'];
	}

	/**
	 * 	Get the Movie trailers
	 *
	 * 	@return array
	 */
	public function getTrailers() {
		if(!empty($this->_data['videos'])) {
			return $this->_data['videos'];
		}
	}

	/**
	 * 	Get the Movie trailer
	 *
	 * 	@return string
	 */
	public function getTrailer() {
		$trailers = $this->getTrailers();
		return $trailers['results'][0]['key'];
	}

	/**
	 * 	Get the Movie genres
	 *
	 * 	@return Genre[]
	 */
	public function getGenres() {
		$genres = array();

		foreach ($this->_data['genres'] as $data) {
			$genres[] = new Genre($data);
		}

		return $genres;
	}

	/**
	 * 	Get the Movie reviews
	 *
	 * 	@return Review[]
	 */
	public function getReviews() {
		$reviews = array();

		foreach ($this->_data['review']['result'] as $data) {
			$reviews[] = new Review($data);
		}

		return $reviews;
	}

	/**
	 * 	Get the Movie companies
	 *
	 * 	@return Company[]
	 */
	public function getCompanies() {
		$companies = array();

		foreach ($this->_data['production_companies'] as $data) {
			$companies[] = new Company($data);
		}

		return $companies;
	}

	/**
	 * 	Get the production countries
	 *
	 * 	@return Countries
	 */
	public function getCountries() {
		$companies = array();

		foreach ($this->_data['production_countries'] as $data) {
			$countries[] = $data['name'];
		}

		return $countries;
	}


	/**
	 *  Get Generic.<br>
	 *  Get a item of the array, you should not get used to use this, better use specific get's.
	 *
	 * 	@param string $item The item of the $data array you want
	 * 	@return array
	 */
	public function get($item = ''){
		return (empty($item)) ? $this->_data : $this->_data[$item];
	}

	//------------------------------------------------------------------------------
	// Import an API instance
	//------------------------------------------------------------------------------

	/**
	 *	Set an instance of the API
	 *
	 *	@param TMDB $tmdb An instance of the api, necessary for the lazy load
	 */
	public function setAPI($tmdb){
		$this->_tmdb = $tmdb;
	}

	//------------------------------------------------------------------------------
	// Export
	//------------------------------------------------------------------------------

	/**
	 * 	Get the JSON representation of the Movie
	 *
	 * 	@return string
	 */
	public function getJSON() {
		return json_encode($this->_data, JSON_PRETTY_PRINT);
	}


	/**
	 * @return string
	 */
	public function getMediaType(){
		return self::MEDIA_TYPE_MOVIE;
	}
}
?>

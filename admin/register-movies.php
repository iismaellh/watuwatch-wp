<?php

add_action( 'init', 'watu_register_movie_post_type', 20 );
function watu_register_movie_post_type() {
	$labels = array(
		'name'               => __( 'Movies', 'watu_watch' ),
		'singular_name'      => __( 'Movie', 'watu_watch' ),
		'add_new'            => __( 'Add New', 'watu_watch' ),
		'add_new_item'       => __( 'Add New Movie', 'watu_watch' ),
		'edit_item'          => __( 'Edit Movie', 'watu_watch' ),
		'new_item'           => __( 'New Movie', 'watu_watch' ),
		'all_items'          => __( 'All Movies', 'watu_watch' ),
		'view_item'          => __( 'View Movie', 'watu_watch' ),
		'search_items'       => __( 'Search Movies', 'watu_watch' ),
		'not_found'          => __( 'No Movies Found', 'watu_watch' ),
		'not_found_in_trash' => __( 'No Movies Found In Trash', 'watu_watch' ),
		'parent_item_colon'  => '',
		'menu_name'          => __( 'Movies', 'watu_watch' )
	);

	$slug = 'movies';

	$args = array(
		'labels'             => $labels,
		'rewrite'            => array(
			'slug'       => $slug
		),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'has_archive'        => true,
		'show_in_rest'       => true,
		'rest_base'          => 'movie-api',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'comments' ),
		'menu_position'      => 4
	);

	$args['taxonomies'] = array();
	$args['taxonomies'][] = 'category';
	$args['taxonomies'][] = 'post_tag';

	register_post_type( 'movie', $args );
}

/**
* Add REST API support to movies.
*/
add_action( 'init', 'watu_movie_rest_support', 25 );
function watu_movie_rest_support() {
	global $wp_post_types;

	//be sure to set this to the name of your post type!
	$post_type_name = 'movie';
	if( isset( $wp_post_types[ $post_type_name ] ) ) {
		$wp_post_types[$post_type_name]->show_in_rest = true;
		$wp_post_types[$post_type_name]->rest_base = $post_type_name;
		$wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';
	}
}

?>

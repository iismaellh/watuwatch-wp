<?php

add_action( 'init', 'watu_register_shows_post_type', 20 );
function watu_register_shows_post_type() {
 $labels = array(
   'name'               => __( 'Shows', 'watu_watch' ),
   'singular_name'      => __( 'Show', 'watu_watch' ),
   'add_new'            => __( 'Add New', 'watu_watch' ),
   'add_new_item'       => __( 'Add New Show', 'watu_watch' ),
   'edit_item'          => __( 'Edit Show', 'watu_watch' ),
   'new_item'           => __( 'New Show', 'watu_watch' ),
   'all_items'          => __( 'All Shows', 'watu_watch' ),
   'view_item'          => __( 'View Show', 'watu_watch' ),
   'search_items'       => __( 'Search Shows', 'watu_watch' ),
   'not_found'          => __( 'No Shows Found', 'watu_watch' ),
   'not_found_in_trash' => __( 'No Shows Found In Trash', 'watu_watch' ),
   'parent_item_colon'  => '',
   'menu_name'          => __( 'Shows', 'watu_watch' )
 );

 $slug = 'shows';

 $args = array(
   'labels'             => $labels,
   'rewrite'            => array(
     'slug'       => $slug,
     'with_front' => false
   ),
   'public'             => true,
   'publicly_queryable' => true,
   'show_ui'            => true,
   'show_in_menu'       => true,
   'has_archive'        => true,
   'show_in_rest'       => true,
   'rest_base'          => 'shows-api',
   'rest_controller_class' => 'WP_REST_Posts_Controller',
   'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'comments' ),
   'menu_position'      => 6
 );

 $args['taxonomies'] = array();
 $args['taxonomies'][] = 'category';
 $args['taxonomies'][] = 'post_tag';

 register_post_type( 'show', $args );
}

/**
* Add REST API support to people.
*/
add_action( 'init', 'watu_shows_rest_support', 25 );

function watu_shows_rest_support() {
 global $wp_post_types;

 //be sure to set this to the name of your post type!
 $post_type_name = 'shows';
 if( isset( $wp_post_types[ $post_type_name ] ) ) {
   $wp_post_types[$post_type_name]->show_in_rest = true;
   $wp_post_types[$post_type_name]->rest_base = $post_type_name;
   $wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';
 }
}

?>

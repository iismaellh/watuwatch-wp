<?php

add_action( 'init', 'watu_register_seasons_post_type', 20 );
function watu_register_seasons_post_type() {
 $labels = array(
   'name'               => __( 'Seasons', 'watu_watch' ),
   'singular_name'      => __( 'Season', 'watu_watch' ),
   'add_new'            => __( 'Add New', 'watu_watch' ),
   'add_new_item'       => __( 'Add New Season', 'watu_watch' ),
   'edit_item'          => __( 'Edit Season', 'watu_watch' ),
   'new_item'           => __( 'New Season', 'watu_watch' ),
   'all_items'          => __( 'All Seasons', 'watu_watch' ),
   'view_item'          => __( 'View Season', 'watu_watch' ),
   'search_items'       => __( 'Search Seasons', 'watu_watch' ),
   'not_found'          => __( 'No Seasons Found', 'watu_watch' ),
   'not_found_in_trash' => __( 'No Seasons Found In Trash', 'watu_watch' ),
   'parent_item_colon'  => '',
   'menu_name'          => __( 'Seasons', 'watu_watch' )
 );

 $slug = 'series/%showtitle%/%season_number%';

 $args = array(
   'labels'             => $labels,
   'rewrite'            => array(
     'slug'       => $slug,
     'with_front' => false
   ),
   'public'             => true,
   'publicly_queryable' => true,
   'show_ui'            => true,
   'showin_menu'       => true,
   'has_archive'        => true,
   'show_in_rest'       => true,
   'rest_base'          => 'seasons-api',
   'rest_controller_class' => 'WP_REST_Posts_Controller',
   'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'comments' ),
   'menu_position'      => 7
 );

 $args['taxonomies'] = array();
 $args['taxonomies'][] = 'category';
 $args['taxonomies'][] = 'post_tag';

 register_post_type( 'season', $args );
}

/**
* Add REST API support to people.
*/
add_action( 'init', 'watu_seasons_rest_support', 25 );

function watu_seasons_rest_support() {
 global $wp_post_types;

 //be sure to set this to the name of your post type!
 $post_type_name = 'seasons';
 if( isset( $wp_post_types[ $post_type_name ] ) ) {
   $wp_post_types[$post_type_name]->season_in_rest = true;
   $wp_post_types[$post_type_name]->rest_base = $post_type_name;
   $wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';
 }
}

add_filter('post_type_link', 'watu_season_permalink_rewrite', 10, 4);
function watu_season_permalink_rewrite($post_link, $post, $leavename, $sample)
{
  $test = $post->ID == 7055 ? sanitize_title( get_the_title( 7057 ) ) : sanitize_title( get_the_title( 7049 ) );;
  if( has_term( 'season', 'category', $post->ID ) ) {
    $post_link = str_replace( '%showtitle%', $test, $post_link );
    $post_link = str_replace( '%season_number%', 'season-1', $post_link );
  }

  return $post_link;
}


add_action( 'init', 'watu_add_season_rewrite_tag', 10 );

function watu_add_season_rewrite_tag() {
  add_rewrite_tag('%showtitle%', '([^&]+)');
  add_rewrite_tag('%season_number%', '([^&]+)');
}

?>

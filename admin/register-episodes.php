<?php

add_action( 'init', 'watu_register_episodes_post_type', 20 );
function watu_register_episodes_post_type() {
 $labels = array(
   'name'               => __( 'Episodes', 'watu_watch' ),
   'singular_name'      => __( 'Episode', 'watu_watch' ),
   'add_new'            => __( 'Add New', 'watu_watch' ),
   'add_new_item'       => __( 'Add New Episode', 'watu_watch' ),
   'edit_item'          => __( 'Edit Episode', 'watu_watch' ),
   'new_item'           => __( 'New Episode', 'watu_watch' ),
   'all_items'          => __( 'All Episodes', 'watu_watch' ),
   'view_item'          => __( 'View Episode', 'watu_watch' ),
   'search_items'       => __( 'Search Episodes', 'watu_watch' ),
   'not_found'          => __( 'No Episodes Found', 'watu_watch' ),
   'not_found_in_trash' => __( 'No Episodes Found In Trash', 'watu_watch' ),
   'parent_item_colon'  => '',
   'menu_name'          => __( 'Episodes', 'watu_watch' )
 );

 $slug = 'shows/%showtitle%/%showseason%';

 $args = array(
   'labels'             => $labels,
   'rewrite'            => array(
     'slug'       => $slug,
     'with_front' => false
   ),
   'public'             => true,
   'publicly_queryable' => true,
   'show_ui'            => true,
   'showin_menu'        => true,
   'has_archive'        => true,
   'show_in_rest'       => true,
   'rest_base'          => 'episodes-api',
   'rest_controller_class' => 'WP_REST_Posts_Controller',
   'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'comments' ),
   'menu_position'      => 7
 );

 $args['taxonomies'] = array();
 $args['taxonomies'][] = 'category';
 $args['taxonomies'][] = 'post_tag';

 register_post_type( 'episode', $args );
}

/**
* Add REST API support to people.
*/
add_action( 'init', 'watu_episodes_rest_support', 25 );

function watu_episodes_rest_support() {
 global $wp_post_types;

 //be sure to set this to the name of your post type!
 $post_type_name = 'episodes';
 if( isset( $wp_post_types[ $post_type_name ] ) ) {
   $wp_post_types[$post_type_name]->episode_in_rest = true;
   $wp_post_types[$post_type_name]->rest_base = $post_type_name;
   $wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';
 }
}

add_filter('post_type_link', 'watu_episode_permalink_rewrite', 10, 4);
function watu_episode_permalink_rewrite($post_link, $post, $leavename, $sample)
{
  if( has_term( 'episode', 'category', $post->ID ) ) {
    $post_link = str_replace( '%showseason%', 'season-1', $post_link );
    $post_link = str_replace( '%showtitle%', 'game-of-thrones', $post_link );
  }

  return $post_link;
}


add_action( 'init', 'watu_add_episode_rewrite_tag', 10 );

function watu_add_episode_rewrite_tag() {
  add_rewrite_tag('%showseason%', '([^&]+)');
}

?>

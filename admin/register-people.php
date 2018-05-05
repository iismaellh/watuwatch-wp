<?php

add_action( 'init', 'watu_register_people_post_type', 20 );
function watu_register_people_post_type() {
 $labels = array(
   'name'               => __( 'People', 'watu_watch' ),
   'singular_name'      => __( 'Person', 'watu_watch' ),
   'add_new'            => __( 'Add New', 'watu_watch' ),
   'add_new_item'       => __( 'Add New Person', 'watu_watch' ),
   'edit_item'          => __( 'Edit Person', 'watu_watch' ),
   'new_item'           => __( 'New Person', 'watu_watch' ),
   'all_items'          => __( 'All People', 'watu_watch' ),
   'view_item'          => __( 'View Person', 'watu_watch' ),
   'search_items'       => __( 'Search People', 'watu_watch' ),
   'not_found'          => __( 'No People Found', 'watu_watch' ),
   'not_found_in_trash' => __( 'No People Found In Trash', 'watu_watch' ),
   'parent_item_colon'  => '',
   'menu_name'          => __( 'People', 'watu_watch' )
 );

 $slug = 'people';

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
   'rest_base'          => 'person-api',
   'rest_controller_class' => 'WP_REST_Posts_Controller',
   'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'comments' ),
   'menu_position'      => 5
 );

 $args['taxonomies'] = array();
 $args['taxonomies'][] = 'post_tag';

 $tax_args = array(
   "hierarchical" => true,
   "label" => "Genders",
   "singular_label" => "Gender",
   "rewrite" => array('slug'=>_x('gender', 'avia_framework'), 'with_front'=>false),
   "query_var" => true
 );

 register_taxonomy("genders", array("person"), $tax_args);

 register_post_type( 'person', $args );
}

/**
* Add REST API support to people.
*/
add_action( 'init', 'watu_people_rest_support', 25 );
function watu_people_rest_support() {
 global $wp_post_types;

 //be sure to set this to the name of your post type!
 $post_type_name = 'person';
 if( isset( $wp_post_types[ $post_type_name ] ) ) {
   $wp_post_types[$post_type_name]->show_in_rest = true;
   $wp_post_types[$post_type_name]->rest_base = $post_type_name;
   $wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';
 }
}

?>

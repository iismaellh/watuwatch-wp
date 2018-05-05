<?php
/**
 * Jetpack Compatibility File
 *
 * @link https://jetpack.com/
 *
 * @package watuwatch
 */

/**
 * Jetpack setup function.
 *
 * See: https://jetpack.com/support/infinite-scroll/
 * See: https://jetpack.com/support/responsive-videos/
 * See: https://jetpack.com/support/content-options/
 */
function watuwatch_jetpack_setup() {
	// Add theme support for Infinite Scroll.
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'watuwatch_infinite_scroll_render',
		'footer'    => 'page',
	) );

	// Add theme support for Responsive Videos.
	add_theme_support( 'jetpack-responsive-videos' );

	// Add theme support for Content Options.
	add_theme_support( 'jetpack-content-options', array(
		'post-details' => array(
			'stylesheet' => 'watuwatch-style',
			'date'       => '.posted-on',
			'categories' => '.cat-links',
			'tags'       => '.tags-links',
			'author'     => '.byline',
			'comment'    => '.comments-link',
		),
	) );
}
add_action( 'after_setup_theme', 'watuwatch_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function watuwatch_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		if ( is_search() ) :
			get_template_part( 'templatess/content', 'search' );
		else :
			get_template_part( 'templatess/content', get_post_format() );
		endif;
	}
}

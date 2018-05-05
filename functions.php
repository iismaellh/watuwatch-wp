<?php
/**
 * watuwatch functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package watuwatch
 */

if ( ! function_exists( 'watuwatch_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function watuwatch_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on watuwatch, use a find and replace
		 * to change 'watuwatch' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'watuwatch', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'watuwatch' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'watuwatch_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'watuwatch_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function watuwatch_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'watuwatch_content_width', 640 );
}
add_action( 'after_setup_theme', 'watuwatch_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function watuwatch_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'watuwatch' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'watuwatch' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'watuwatch_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function watuwatch_scripts() {
	wp_enqueue_style( 'watuwatch-bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', false);

	wp_enqueue_style( 'watuwatch-default', get_stylesheet_uri() );

	wp_enqueue_style( 'watuwatch-movie', get_theme_file_uri( '/css/movie.css' ), array(), '1.0' );

	wp_enqueue_style( 'watuwatch-grid', get_theme_file_uri( '/css/grid.css' ),  array(), '1.0' );

	wp_enqueue_style( 'watuwatch-swiper', get_theme_file_uri( '/css/swiper.min.css' ),  array(), '1.0' );

	wp_enqueue_style( 'watuwatch-lightbox', get_theme_file_uri( '/css/basicLightbox.min.css' ),  array(), '1.0' );

	if ( is_customize_preview() ) {
		wp_enqueue_style( 'twentyseventeen-ie9', get_theme_file_uri( '/css/ie9.css' ), array( 'watuwatch-default-css' ), '1.0' );
		wp_style_add_data( 'twentyseventeen-ie9', 'conditional', 'IE 9' );
	}

	wp_enqueue_style( 'watuwatch-ie8', get_theme_file_uri( '/css/ie8.css' ), array( 'watuwatch-default-css' ), '1.0' );
	wp_style_add_data( 'watuwatch-ie8', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'html5', get_theme_file_uri( '/assets/js/html5.js' ), array( 'jquery' ), '3.7.3' );
	wp_script_add_data( 'html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'watuwatch-navigation-js', get_theme_file_uri('/js/navigation.js'), array( 'jquery' ), '20170927', true );

	wp_enqueue_script( 'watuwatch-skip-link-focus-fix-js', get_theme_file_uri('/js/skip-link-focus-fix.js'), array( 'jquery' ), '20170927', true );

	wp_enqueue_script( 'watuwatch-swiper-js', get_theme_file_uri( '/js/swiper.min.js' ), array( 'jquery' ), '20170927', true );

	wp_enqueue_script( 'watuwatch-lightbox-js', get_theme_file_uri( '/js/basicLightbox.min.js' ), array( 'jquery' ), '20170927', true );

	wp_enqueue_script( 'watuwatch-global-js', get_theme_file_uri( '/js/global.js' ), array( 'jquery' ), '20170927', true );

	wp_enqueue_script( 'watuwatch-chart-js', get_theme_file_uri( '/js/doughnut.js' ), array( 'jquery' ), '20170927', true );

	wp_enqueue_script( 'watuwatch-jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js');

	wp_enqueue_script( 'watuwatch-bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array( 'watuwatch-jquery' ), '20170927', true );

	wp_enqueue_style( 'watuwatch-alegreya', 'https://fonts.googleapis.com/css?family=Alegreya:400,700&amp;subset=latin-ext', false );

	wp_enqueue_style( 'watuwatch-open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Open+Sans:400,700&amp;subset=latin-ext', false );

	wp_enqueue_style( 'watuwatch-roboto', 'https://fonts.googleapis.com/css?family=Roboto+Slab:300,400,700|Roboto:300,400,700&amp;subset=latin-ext', false );

	wp_enqueue_style( 'watuwatch-ubuntu', 'https://fonts.googleapis.com/css?family=Ubuntu:300,400,700&amp;subset=latin-ext', false );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'watuwatch_scripts' );

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Detect Mobile
 */
require get_parent_theme_file_path( '/inc/mobile-detect.php' );

/**
 * Register movies post type
 */
require get_parent_theme_file_path( '/admin/register-movies.php' );

/**
 * Register people post type
 */
require get_parent_theme_file_path( '/admin/register-people.php' );

/**
 * Register shows post type
 */
require get_parent_theme_file_path( '/admin/register-shows.php' );

/**
 * Register seasons post type
 */
require get_parent_theme_file_path( '/admin/register-seasons.php' );

/**
 * Register episodes post type
 */
require get_parent_theme_file_path( '/admin/register-episodes.php' );

/**
 * Load TMDB wrapper
 */
require get_parent_theme_file_path( '/tmdb/tmdb-api.php' );

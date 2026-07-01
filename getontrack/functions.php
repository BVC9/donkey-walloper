<?php
/**
 * Get On Track theme functions.
 *
 * @package GetOnTrack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GOT_VERSION', '1.0.0' );
define( 'GOT_DIR', get_template_directory() );
define( 'GOT_URI', get_template_directory_uri() );

require_once GOT_DIR . '/inc/customizer.php';
require_once GOT_DIR . '/inc/template-tags.php';

/**
 * Theme setup.
 */
function got_setup() {
	load_theme_textdomain( 'getontrack', GOT_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'getontrack' ),
		'footer'  => __( 'Footer Menu', 'getontrack' ),
	) );

	add_image_size( 'got-hero', 1920, 1080, true );
	add_image_size( 'got-card', 640, 480, true );
}
add_action( 'after_setup_theme', 'got_setup' );

/**
 * Enqueue scripts and styles.
 */
function got_enqueue_assets() {
	wp_enqueue_style(
		'got-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Outfit:wght@300;400;500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'got-main', GOT_URI . '/assets/css/main.css', array( 'got-fonts' ), GOT_VERSION );
	wp_enqueue_script( 'got-main', GOT_URI . '/assets/js/main.js', array(), GOT_VERSION, true );

	wp_localize_script( 'got-main', 'gotData', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'got_nonce' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'got_enqueue_assets' );

/**
 * Register widget areas.
 */
function got_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Footer Column 1', 'getontrack' ),
		'id'            => 'footer-1',
		'description'   => __( 'First footer widget area.', 'getontrack' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Column 2', 'getontrack' ),
		'id'            => 'footer-2',
		'description'   => __( 'Second footer widget area.', 'getontrack' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'got_widgets_init' );

/**
 * Custom excerpt length.
 */
function got_excerpt_length( $length ) {
	return 24;
}
add_filter( 'excerpt_length', 'got_excerpt_length' );

/**
 * Custom excerpt more.
 */
function got_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'got_excerpt_more' );

/**
 * Add body classes.
 */
function got_body_classes( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'got-front-page';
	}
	return $classes;
}
add_filter( 'body_class', 'got_body_classes' );

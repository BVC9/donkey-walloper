<?php
/**
 * Get On Track theme functions.
 *
 * @package GetOnTrack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue theme styles.
 */
function getontrack_enqueue_styles() {
	wp_enqueue_style(
		'getontrack-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'getontrack_enqueue_styles' );

/**
 * Add body class for the coming-soon layout.
 *
 * @param array $classes Existing body classes.
 * @return array
 */
function getontrack_body_classes( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'getontrack-coming-soon';
	}

	return $classes;
}
add_filter( 'body_class', 'getontrack_body_classes' );

/**
 * Register theme customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function getontrack_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'getontrack_hero',
		array(
			'title'    => __( 'Coming Soon', 'getontrack' ),
			'priority' => 30,
		)
	);

	$wp_customize->add_setting(
		'getontrack_headline',
		array(
			'default'           => __( 'Great things are coming soon', 'getontrack' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'getontrack_headline',
		array(
			'label'   => __( 'Headline', 'getontrack' ),
			'section' => 'getontrack_hero',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'getontrack_tagline',
		array(
			'default'           => __( 'Stay tuned', 'getontrack' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'getontrack_tagline',
		array(
			'label'   => __( 'Tagline', 'getontrack' ),
			'section' => 'getontrack_hero',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'getontrack_footer_text',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'getontrack_footer_text',
		array(
			'label'   => __( 'Footer text', 'getontrack' ),
			'section' => 'getontrack_hero',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'getontrack_footer_link_label',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'getontrack_footer_link_label',
		array(
			'label'   => __( 'Footer link label', 'getontrack' ),
			'section' => 'getontrack_hero',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'getontrack_footer_link_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'getontrack_footer_link_url',
		array(
			'label'   => __( 'Footer link URL', 'getontrack' ),
			'section' => 'getontrack_hero',
			'type'    => 'url',
		)
	);
}
add_action( 'customize_register', 'getontrack_customize_register' );

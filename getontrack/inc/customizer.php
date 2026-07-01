<?php
/**
 * Customizer settings.
 *
 * @package GetOnTrack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function got_customize_register( $wp_customize ) {
	$wp_customize->add_panel( 'got_panel', array(
		'title'    => __( 'Get On Track Settings', 'getontrack' ),
		'priority' => 30,
	) );

	// Branding section.
	$wp_customize->add_section( 'got_branding', array(
		'title' => __( 'Branding', 'getontrack' ),
		'panel' => 'got_panel',
	) );

	got_add_text_setting( $wp_customize, 'got_tagline', __( 'Tagline', 'getontrack' ), 'got_branding', __( 'Peptide Therapy for Longevity', 'getontrack' ) );
	got_add_text_setting( $wp_customize, 'got_cta_text', __( 'Header CTA Text', 'getontrack' ), 'got_branding', __( 'Book Consultation', 'getontrack' ) );
	got_add_text_setting( $wp_customize, 'got_cta_url', __( 'Header CTA URL', 'getontrack' ), 'got_branding', '#consultation' );

	// Hero section.
	$wp_customize->add_section( 'got_hero', array(
		'title' => __( 'Hero Section', 'getontrack' ),
		'panel' => 'got_panel',
	) );

	got_add_text_setting( $wp_customize, 'got_hero_eyebrow', __( 'Eyebrow Text', 'getontrack' ), 'got_hero', __( 'Science-Backed Longevity', 'getontrack' ) );
	got_add_text_setting( $wp_customize, 'got_hero_title', __( 'Headline', 'getontrack' ), 'got_hero', __( 'Optimize Your Healthspan with Precision Peptide Therapy', 'getontrack' ) );
	got_add_textarea_setting( $wp_customize, 'got_hero_desc', __( 'Description', 'getontrack' ), 'got_hero', __( 'Personalized peptide protocols that support cellular repair, metabolic balance, and graceful aging — guided by evidence-based medicine.', 'getontrack' ) );
	got_add_text_setting( $wp_customize, 'got_hero_btn_primary', __( 'Primary Button Text', 'getontrack' ), 'got_hero', __( 'Start Your Journey', 'getontrack' ) );
	got_add_text_setting( $wp_customize, 'got_hero_btn_secondary', __( 'Secondary Button Text', 'getontrack' ), 'got_hero', __( 'Learn About Peptides', 'getontrack' ) );

	// Contact section.
	$wp_customize->add_section( 'got_contact', array(
		'title' => __( 'Contact', 'getontrack' ),
		'panel' => 'got_panel',
	) );

	got_add_text_setting( $wp_customize, 'got_contact_email', __( 'Email Address', 'getontrack' ), 'got_contact', 'hello@getontrack.live' );
	got_add_text_setting( $wp_customize, 'got_contact_phone', __( 'Phone Number', 'getontrack' ), 'got_contact', '' );

	// Footer section.
	$wp_customize->add_section( 'got_footer', array(
		'title' => __( 'Footer', 'getontrack' ),
		'panel' => 'got_panel',
	) );

	got_add_textarea_setting( $wp_customize, 'got_footer_desc', __( 'Footer Description', 'getontrack' ), 'got_footer', __( 'Science-backed peptide protocols designed to support cellular repair, metabolic health, and graceful aging.', 'getontrack' ) );
	got_add_textarea_setting( $wp_customize, 'got_disclaimer', __( 'Medical Disclaimer', 'getontrack' ), 'got_footer', __( 'This website is for educational purposes only and does not constitute medical advice. Consult a licensed healthcare provider before starting any peptide protocol.', 'getontrack' ) );
}
add_action( 'customize_register', 'got_customize_register' );

/**
 * Helper: add text setting.
 */
function got_add_text_setting( $wp_customize, $id, $label, $section, $default ) {
	$wp_customize->add_setting( $id, array(
		'default'           => $default,
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( $id, array(
		'label'   => $label,
		'section' => $section,
		'type'    => 'text',
	) );
}

/**
 * Helper: add textarea setting.
 */
function got_add_textarea_setting( $wp_customize, $id, $label, $section, $default ) {
	$wp_customize->add_setting( $id, array(
		'default'           => $default,
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( $id, array(
		'label'   => $label,
		'section' => $section,
		'type'    => 'textarea',
	) );
}

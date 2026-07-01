<?php
/**
 * Template tags and helpers.
 *
 * @package GetOnTrack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get theme mod with default.
 *
 * @param string $key     Setting key.
 * @param mixed  $default Default value.
 * @return mixed
 */
function got_get_mod( $key, $default = '' ) {
	return get_theme_mod( $key, $default );
}

/**
 * Fallback primary menu.
 */
function got_fallback_menu() {
	$items = array(
		array( 'label' => __( 'Peptides', 'getontrack' ), 'url' => '#peptides' ),
		array( 'label' => __( 'Science', 'getontrack' ), 'url' => '#science' ),
		array( 'label' => __( 'Process', 'getontrack' ), 'url' => '#process' ),
		array( 'label' => __( 'FAQ', 'getontrack' ), 'url' => '#faq' ),
		array( 'label' => __( 'Blog', 'getontrack' ), 'url' => get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ),
	);
	echo '<ul class="got-nav__list">';
	foreach ( $items as $item ) {
		printf(
			'<li><a href="%s">%s</a></li>',
			esc_url( $item['url'] ),
			esc_html( $item['label'] )
		);
	}
	echo '</ul>';
}

/**
 * Fallback footer menu.
 */
function got_fallback_footer_menu() {
	$items = array(
		array( 'label' => __( 'Peptide Therapies', 'getontrack' ), 'url' => '#peptides' ),
		array( 'label' => __( 'How It Works', 'getontrack' ), 'url' => '#process' ),
		array( 'label' => __( 'Research', 'getontrack' ), 'url' => '#science' ),
		array( 'label' => __( 'Consultation', 'getontrack' ), 'url' => '#consultation' ),
	);
	echo '<ul class="got-footer__menu">';
	foreach ( $items as $item ) {
		printf(
			'<li><a href="%s">%s</a></li>',
			esc_url( $item['url'] ),
			esc_html( $item['label'] )
		);
	}
	echo '</ul>';
}

/**
 * Render an SVG icon.
 *
 * @param string $name Icon name.
 */
function got_icon( $name ) {
	$icons = array(
		'dna' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 15c6.667-6 13.333 0 20-6"/><path d="M9 22c1.798-1.998 2.518-3.995 2.807-5.993"/><path d="M15 2c-1.798 1.998-2.518 3.995-2.807 5.993"/><path d="M17 6l-2.5-2.5"/><path d="M14 8l-1-1"/><path d="M7 18l2.5 2.5"/><path d="M3.5 14.5l.5.5"/><path d="M20 9l.5.5"/><path d="M6.5 12.5l1 1"/><path d="M16.5 10.5l1 1"/><path d="M10 16l1.5 1.5"/></svg>',
		'cell' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg>',
		'heart' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
		'bolt' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
		'shield' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
		'clock' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
		'check' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>',
		'arrow' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>',
		'star' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
	);

	if ( isset( $icons[ $name ] ) ) {
		echo $icons[ $name ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Get default peptide cards data.
 *
 * @return array
 */
function got_get_peptides() {
	return array(
		array(
			'name' => 'BPC-157',
			'desc' => __( 'Supports tissue repair, gut health, and accelerated recovery from injury.', 'getontrack' ),
			'tag'  => __( 'Recovery', 'getontrack' ),
		),
		array(
			'name' => 'CJC-1295 / Ipamorelin',
			'desc' => __( 'Stimulates natural growth hormone release for improved body composition and sleep quality.', 'getontrack' ),
			'tag'  => __( 'Growth Hormone', 'getontrack' ),
		),
		array(
			'name' => 'Thymosin Alpha-1',
			'desc' => __( 'Modulates immune function and supports resilience against age-related immune decline.', 'getontrack' ),
			'tag'  => __( 'Immunity', 'getontrack' ),
		),
		array(
			'name' => 'Epithalon',
			'desc' => __( 'Research suggests telomere support and potential regulation of circadian rhythms.', 'getontrack' ),
			'tag'  => __( 'Longevity', 'getontrack' ),
		),
		array(
			'name' => 'GHK-Cu',
			'desc' => __( 'Copper peptide known for skin rejuvenation, collagen synthesis, and wound healing.', 'getontrack' ),
			'tag'  => __( 'Skin & Beauty', 'getontrack' ),
		),
		array(
			'name' => 'Semaglutide / Tirzepatide',
			'desc' => __( 'GLP-1 receptor agonists that support metabolic health, weight management, and cardiovascular markers.', 'getontrack' ),
			'tag'  => __( 'Metabolic', 'getontrack' ),
		),
	);
}

/**
 * Get default FAQ items.
 *
 * @return array
 */
function got_get_faqs() {
	return array(
		array(
			'q' => __( 'What are peptides and how do they work?', 'getontrack' ),
			'a' => __( 'Peptides are short chains of amino acids that act as signaling molecules in the body. They can instruct cells to perform specific functions — such as repairing tissue, producing growth hormone, or modulating inflammation — making them powerful tools in precision longevity medicine.', 'getontrack' ),
		),
		array(
			'q' => __( 'Are peptide therapies safe?', 'getontrack' ),
			'a' => __( 'When prescribed and monitored by a qualified healthcare provider, peptide therapies have a strong safety profile. We use pharmaceutical-grade compounds and conduct thorough health assessments before recommending any protocol.', 'getontrack' ),
		),
		array(
			'q' => __( 'How long before I see results?', 'getontrack' ),
			'a' => __( 'Results vary by peptide and individual goals. Some clients notice improved sleep and energy within 2–4 weeks, while body composition and skin changes may take 8–12 weeks of consistent use.', 'getontrack' ),
		),
		array(
			'q' => __( 'Do I need a prescription?', 'getontrack' ),
			'a' => __( 'Yes. All peptide protocols at Get On Track require a consultation with a licensed provider who will evaluate your health history, lab work, and goals before creating a personalized plan.', 'getontrack' ),
		),
		array(
			'q' => __( 'What makes Get On Track different?', 'getontrack' ),
			'a' => __( 'We combine evidence-based peptide science with personalized care. Every protocol is tailored to your biomarkers, lifestyle, and longevity goals — not a one-size-fits-all approach.', 'getontrack' ),
		),
	);
}

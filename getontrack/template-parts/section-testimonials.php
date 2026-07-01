<?php
/**
 * Testimonials section.
 *
 * @package GetOnTrack
 */

$testimonials = array(
	array(
		'quote'  => __( 'After three months on a CJC/Ipamorelin protocol, my sleep improved dramatically and I have more energy than I did in my thirties. Get On Track made the process seamless.', 'getontrack' ),
		'name'   => 'Sarah M.',
		'detail' => __( 'Age 52 · Growth Hormone Protocol', 'getontrack' ),
	),
	array(
		'quote'  => __( 'The team took time to understand my goals and lab results. My recovery from workouts is faster, and my inflammation markers dropped significantly.', 'getontrack' ),
		'name'   => 'James R.',
		'detail' => __( 'Age 47 · Recovery & Anti-Inflammatory', 'getontrack' ),
	),
	array(
		'quote'  => __( 'I was skeptical about peptides until I saw the science and had a real consultation. Six months later, I feel like I turned back the clock on how I look and feel.', 'getontrack' ),
		'name'   => 'Linda K.',
		'detail' => __( 'Age 58 · Comprehensive Longevity Program', 'getontrack' ),
	),
);
?>

<section class="got-section got-section--testimonials">
	<div class="got-container">
		<div class="got-section-header got-section-header--center">
			<p class="got-eyebrow"><?php esc_html_e( 'Client Stories', 'getontrack' ); ?></p>
			<h2 class="got-section-header__title"><?php esc_html_e( 'Real Results, Real People', 'getontrack' ); ?></h2>
		</div>
		<div class="got-grid got-grid--3">
			<?php foreach ( $testimonials as $t ) : ?>
				<blockquote class="got-card got-card--testimonial">
					<div class="got-card__stars" aria-label="<?php esc_attr_e( '5 out of 5 stars', 'getontrack' ); ?>">
						<?php for ( $i = 0; $i < 5; $i++ ) : ?>
							<?php got_icon( 'star' ); ?>
						<?php endfor; ?>
					</div>
					<p class="got-card__quote">&ldquo;<?php echo esc_html( $t['quote'] ); ?>&rdquo;</p>
					<footer class="got-card__author">
						<strong><?php echo esc_html( $t['name'] ); ?></strong>
						<span><?php echo esc_html( $t['detail'] ); ?></span>
					</footer>
				</blockquote>
			<?php endforeach; ?>
		</div>
	</div>
</section>

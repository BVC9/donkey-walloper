<?php
/**
 * Peptides showcase section.
 *
 * @package GetOnTrack
 */

$peptides = got_get_peptides();
?>

<section class="got-section got-section--peptides" id="peptides">
	<div class="got-container">
		<div class="got-section-header got-section-header--center">
			<p class="got-eyebrow"><?php esc_html_e( 'Our Therapies', 'getontrack' ); ?></p>
			<h2 class="got-section-header__title"><?php esc_html_e( 'Peptide Protocols for Every Longevity Goal', 'getontrack' ); ?></h2>
			<p class="got-section-header__desc">
				<?php esc_html_e( 'From recovery and immunity to metabolic health and skin rejuvenation — explore the peptides we use in personalized anti-aging programs.', 'getontrack' ); ?>
			</p>
		</div>
		<div class="got-grid got-grid--3">
			<?php foreach ( $peptides as $peptide ) : ?>
				<article class="got-card got-card--peptide">
					<span class="got-card__tag"><?php echo esc_html( $peptide['tag'] ); ?></span>
					<h3 class="got-card__title"><?php echo esc_html( $peptide['name'] ); ?></h3>
					<p class="got-card__desc"><?php echo esc_html( $peptide['desc'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
		<div class="got-section__cta">
			<a class="got-btn got-btn--outline" href="#consultation">
				<?php esc_html_e( 'Find Your Protocol', 'getontrack' ); ?>
			</a>
		</div>
	</div>
</section>

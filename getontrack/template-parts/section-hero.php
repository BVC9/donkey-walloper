<?php
/**
 * Hero section.
 *
 * @package GetOnTrack
 */
?>

<section class="got-hero" id="top">
	<div class="got-hero__bg" aria-hidden="true">
		<div class="got-hero__gradient"></div>
		<div class="got-hero__mesh"></div>
	</div>
	<div class="got-container got-hero__inner">
		<div class="got-hero__content">
			<p class="got-eyebrow"><?php echo esc_html( got_get_mod( 'got_hero_eyebrow', __( 'Science-Backed Longevity', 'getontrack' ) ) ); ?></p>
			<h1 class="got-hero__title">
				<?php echo esc_html( got_get_mod( 'got_hero_title', __( 'Optimize Your Healthspan with Precision Peptide Therapy', 'getontrack' ) ) ); ?>
			</h1>
			<p class="got-hero__desc">
				<?php echo esc_html( got_get_mod( 'got_hero_desc', __( 'Personalized peptide protocols that support cellular repair, metabolic balance, and graceful aging — guided by evidence-based medicine.', 'getontrack' ) ) ); ?>
			</p>
			<div class="got-hero__actions">
				<a class="got-btn got-btn--primary got-btn--lg" href="#consultation">
					<?php echo esc_html( got_get_mod( 'got_hero_btn_primary', __( 'Start Your Journey', 'getontrack' ) ) ); ?>
				</a>
				<a class="got-btn got-btn--ghost got-btn--lg" href="#peptides">
					<?php echo esc_html( got_get_mod( 'got_hero_btn_secondary', __( 'Learn About Peptides', 'getontrack' ) ) ); ?>
				</a>
			</div>
			<ul class="got-hero__stats">
				<li>
					<strong>15+</strong>
					<span><?php esc_html_e( 'Peptide Protocols', 'getontrack' ); ?></span>
				</li>
				<li>
					<strong>2,500+</strong>
					<span><?php esc_html_e( 'Clients Served', 'getontrack' ); ?></span>
				</li>
				<li>
					<strong>98%</strong>
					<span><?php esc_html_e( 'Satisfaction Rate', 'getontrack' ); ?></span>
				</li>
			</ul>
		</div>
		<div class="got-hero__visual" aria-hidden="true">
			<div class="got-hero__card got-hero__card--main">
				<div class="got-hero__card-icon"><?php got_icon( 'dna' ); ?></div>
				<p class="got-hero__card-label"><?php esc_html_e( 'Cellular Repair', 'getontrack' ); ?></p>
				<div class="got-hero__card-bar"><span style="width:87%"></span></div>
			</div>
			<div class="got-hero__card got-hero__card--float-1">
				<?php got_icon( 'heart' ); ?>
				<span><?php esc_html_e( 'Vitality', 'getontrack' ); ?></span>
			</div>
			<div class="got-hero__card got-hero__card--float-2">
				<?php got_icon( 'clock' ); ?>
				<span><?php esc_html_e( 'Longevity', 'getontrack' ); ?></span>
			</div>
		</div>
	</div>
</section>

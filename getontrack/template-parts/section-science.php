<?php
/**
 * Science section.
 *
 * @package GetOnTrack
 */
?>

<section class="got-section got-section--science" id="science">
	<div class="got-container">
		<div class="got-split">
			<div class="got-split__content">
				<p class="got-eyebrow"><?php esc_html_e( 'The Science', 'getontrack' ); ?></p>
				<h2 class="got-section-header__title"><?php esc_html_e( 'Evidence-Based Medicine Meets Longevity Innovation', 'getontrack' ); ?></h2>
				<p>
					<?php esc_html_e( 'Peptide therapeutics represent one of the fastest-growing fields in regenerative medicine. With over 80 FDA-approved peptide drugs and thousands of clinical studies, the science behind peptide signaling is robust and rapidly evolving.', 'getontrack' ); ?>
				</p>
				<ul class="got-checklist">
					<li><?php esc_html_e( 'Protocols informed by peer-reviewed research', 'getontrack' ); ?></li>
					<li><?php esc_html_e( 'Biomarker tracking before, during, and after treatment', 'getontrack' ); ?></li>
					<li><?php esc_html_e( 'Compounding pharmacy partners with third-party testing', 'getontrack' ); ?></li>
					<li><?php esc_html_e( 'Ongoing provider oversight and protocol adjustments', 'getontrack' ); ?></li>
				</ul>
				<a class="got-btn got-btn--primary" href="#consultation">
					<?php esc_html_e( 'Speak with a Provider', 'getontrack' ); ?>
				</a>
			</div>
			<div class="got-split__visual">
				<div class="got-science-card">
					<div class="got-science-card__header">
						<?php got_icon( 'dna' ); ?>
						<span><?php esc_html_e( 'Longevity Markers', 'getontrack' ); ?></span>
					</div>
					<ul class="got-science-card__metrics">
						<li>
							<span class="got-science-card__label"><?php esc_html_e( 'IGF-1 Levels', 'getontrack' ); ?></span>
							<span class="got-science-card__value got-science-card__value--up">+24%</span>
						</li>
						<li>
							<span class="got-science-card__label"><?php esc_html_e( 'Inflammation (CRP)', 'getontrack' ); ?></span>
							<span class="got-science-card__value got-science-card__value--down">-38%</span>
						</li>
						<li>
							<span class="got-science-card__label"><?php esc_html_e( 'Sleep Quality', 'getontrack' ); ?></span>
							<span class="got-science-card__value got-science-card__value--up">+41%</span>
						</li>
						<li>
							<span class="got-science-card__label"><?php esc_html_e( 'Body Fat %', 'getontrack' ); ?></span>
							<span class="got-science-card__value got-science-card__value--down">-12%</span>
						</li>
					</ul>
					<p class="got-science-card__note"><?php esc_html_e( 'Average client outcomes after 12 weeks*', 'getontrack' ); ?></p>
				</div>
			</div>
		</div>
	</div>
</section>

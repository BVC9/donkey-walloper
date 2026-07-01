<?php
/**
 * Benefits section.
 *
 * @package GetOnTrack
 */

$benefits = array(
	array(
		'icon'  => 'cell',
		'title' => __( 'Cellular Regeneration', 'getontrack' ),
		'desc'  => __( 'Peptides signal your body to repair damaged tissue, support mitochondrial function, and slow age-related cellular decline.', 'getontrack' ),
	),
	array(
		'icon'  => 'bolt',
		'title' => __( 'Metabolic Optimization', 'getontrack' ),
		'desc'  => __( 'Improve insulin sensitivity, body composition, and energy metabolism with targeted peptide and GLP-1 protocols.', 'getontrack' ),
	),
	array(
		'icon'  => 'heart',
		'title' => __( 'Hormonal Balance', 'getontrack' ),
		'desc'  => __( 'Restore youthful growth hormone patterns, improve sleep architecture, and support healthy aging of the endocrine system.', 'getontrack' ),
	),
	array(
		'icon'  => 'shield',
		'title' => __( 'Immune Resilience', 'getontrack' ),
		'desc'  => __( 'Strengthen immune surveillance and reduce chronic inflammation — key drivers of accelerated aging.', 'getontrack' ),
	),
);
?>

<section class="got-section got-section--benefits" id="benefits">
	<div class="got-container">
		<div class="got-section-header">
			<p class="got-eyebrow"><?php esc_html_e( 'Why Peptides', 'getontrack' ); ?></p>
			<h2 class="got-section-header__title"><?php esc_html_e( 'Unlock Your Body\'s Natural Healing Potential', 'getontrack' ); ?></h2>
			<p class="got-section-header__desc">
				<?php esc_html_e( 'Unlike broad supplements, peptides deliver precise biological instructions — helping your body do what it was designed to do, only better.', 'getontrack' ); ?>
			</p>
		</div>
		<div class="got-grid got-grid--4">
			<?php foreach ( $benefits as $benefit ) : ?>
				<div class="got-card got-card--benefit">
					<div class="got-card__icon"><?php got_icon( $benefit['icon'] ); ?></div>
					<h3 class="got-card__title"><?php echo esc_html( $benefit['title'] ); ?></h3>
					<p class="got-card__desc"><?php echo esc_html( $benefit['desc'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

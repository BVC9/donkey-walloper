<?php
/**
 * Process section.
 *
 * @package GetOnTrack
 */

$steps = array(
	array(
		'num'   => '01',
		'title' => __( 'Consultation', 'getontrack' ),
		'desc'  => __( 'Meet with a licensed provider to discuss your health history, goals, and any current medications or conditions.', 'getontrack' ),
	),
	array(
		'num'   => '02',
		'title' => __( 'Lab Work', 'getontrack' ),
		'desc'  => __( 'Comprehensive blood panels establish your baseline biomarkers and identify the optimal peptide protocol for you.', 'getontrack' ),
	),
	array(
		'num'   => '03',
		'title' => __( 'Personalized Protocol', 'getontrack' ),
		'desc'  => __( 'Receive a tailored peptide plan with dosing instructions, administration guidance, and a clear timeline for results.', 'getontrack' ),
	),
	array(
		'num'   => '04',
		'title' => __( 'Ongoing Support', 'getontrack' ),
		'desc'  => __( 'Regular check-ins, lab retesting, and protocol refinements ensure you stay on track toward your longevity goals.', 'getontrack' ),
	),
);
?>

<section class="got-section got-section--process" id="process">
	<div class="got-container">
		<div class="got-section-header got-section-header--center">
			<p class="got-eyebrow"><?php esc_html_e( 'How It Works', 'getontrack' ); ?></p>
			<h2 class="got-section-header__title"><?php esc_html_e( 'Your Path to Optimal Healthspan', 'getontrack' ); ?></h2>
		</div>
		<div class="got-process">
			<?php foreach ( $steps as $step ) : ?>
				<div class="got-process__step">
					<span class="got-process__num"><?php echo esc_html( $step['num'] ); ?></span>
					<h3 class="got-process__title"><?php echo esc_html( $step['title'] ); ?></h3>
					<p class="got-process__desc"><?php echo esc_html( $step['desc'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

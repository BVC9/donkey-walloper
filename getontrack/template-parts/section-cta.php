<?php
/**
 * CTA / Consultation section.
 *
 * @package GetOnTrack
 */
?>

<section class="got-cta" id="consultation">
	<div class="got-cta__bg" aria-hidden="true"></div>
	<div class="got-container">
		<div class="got-cta__inner">
			<div class="got-cta__content">
				<p class="got-eyebrow got-eyebrow--light"><?php esc_html_e( 'Ready to Begin?', 'getontrack' ); ?></p>
				<h2 class="got-cta__title"><?php esc_html_e( 'Take the First Step Toward a Longer, Healthier Life', 'getontrack' ); ?></h2>
				<p class="got-cta__desc">
					<?php esc_html_e( 'Schedule a complimentary consultation with our longevity specialists. We\'ll review your goals, answer your questions, and design a peptide protocol tailored to you.', 'getontrack' ); ?>
				</p>
			</div>
			<form class="got-form" action="#" method="post" data-consultation-form>
				<div class="got-form__row">
					<label class="got-form__field">
						<span class="screen-reader-text"><?php esc_html_e( 'Full Name', 'getontrack' ); ?></span>
						<input type="text" name="name" placeholder="<?php esc_attr_e( 'Full Name', 'getontrack' ); ?>" required>
					</label>
					<label class="got-form__field">
						<span class="screen-reader-text"><?php esc_html_e( 'Email Address', 'getontrack' ); ?></span>
						<input type="email" name="email" placeholder="<?php esc_attr_e( 'Email Address', 'getontrack' ); ?>" required>
					</label>
				</div>
				<label class="got-form__field">
					<span class="screen-reader-text"><?php esc_html_e( 'Phone Number', 'getontrack' ); ?></span>
					<input type="tel" name="phone" placeholder="<?php esc_attr_e( 'Phone Number (optional)', 'getontrack' ); ?>">
				</label>
				<label class="got-form__field">
					<span class="screen-reader-text"><?php esc_html_e( 'Your Goals', 'getontrack' ); ?></span>
					<textarea name="message" rows="3" placeholder="<?php esc_attr_e( 'Tell us about your health & longevity goals...', 'getontrack' ); ?>"></textarea>
				</label>
				<button type="submit" class="got-btn got-btn--primary got-btn--lg got-btn--full">
					<?php esc_html_e( 'Request Free Consultation', 'getontrack' ); ?>
				</button>
				<p class="got-form__note">
					<?php esc_html_e( 'By submitting, you agree to be contacted about peptide therapy services. We respect your privacy.', 'getontrack' ); ?>
				</p>
			</form>
		</div>
	</div>
</section>

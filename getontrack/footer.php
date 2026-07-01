<?php
/**
 * Footer template.
 *
 * @package GetOnTrack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

</main><!-- #main-content -->

<footer class="got-footer">
	<div class="got-container">
		<div class="got-footer__grid">
			<div class="got-footer__brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="got-logo got-logo--footer" rel="home">
					<span class="got-logo__mark" aria-hidden="true"></span>
					<span class="got-logo__text">
						<strong><?php bloginfo( 'name' ); ?></strong>
						<small><?php echo esc_html( got_get_mod( 'got_tagline', __( 'Peptide Therapy for Longevity', 'getontrack' ) ) ); ?></small>
					</span>
				</a>
				<p class="got-footer__desc">
					<?php echo esc_html( got_get_mod( 'got_footer_desc', __( 'Science-backed peptide protocols designed to support cellular repair, metabolic health, and graceful aging.', 'getontrack' ) ) ); ?>
				</p>
			</div>

			<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
				<div class="got-footer__widgets"><?php dynamic_sidebar( 'footer-1' ); ?></div>
			<?php else : ?>
				<div class="got-footer__links">
					<h4><?php esc_html_e( 'Explore', 'getontrack' ); ?></h4>
					<?php
					wp_nav_menu( array(
						'theme_location' => 'footer',
						'menu_class'     => 'got-footer__menu',
						'container'      => false,
						'fallback_cb'    => 'got_fallback_footer_menu',
					) );
					?>
				</div>
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
				<div class="got-footer__widgets"><?php dynamic_sidebar( 'footer-2' ); ?></div>
			<?php else : ?>
				<div class="got-footer__contact">
					<h4><?php esc_html_e( 'Get in Touch', 'getontrack' ); ?></h4>
					<ul class="got-footer__contact-list">
						<?php if ( $email = got_get_mod( 'got_contact_email', 'hello@getontrack.live' ) ) : ?>
							<li><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
						<?php endif; ?>
						<?php if ( $phone = got_get_mod( 'got_contact_phone', '' ) ) : ?>
							<li><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li>
						<?php endif; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>

		<div class="got-footer__bottom">
			<p class="got-footer__copy">
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>.
				<?php esc_html_e( 'All rights reserved.', 'getontrack' ); ?>
			</p>
			<p class="got-footer__disclaimer">
				<?php echo esc_html( got_get_mod( 'got_disclaimer', __( 'This website is for educational purposes only and does not constitute medical advice. Consult a licensed healthcare provider before starting any peptide protocol.', 'getontrack' ) ) ); ?>
			</p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

<?php
/**
 * 404 template.
 *
 * @package GetOnTrack
 */

get_header();
?>

<section class="got-page-header">
	<div class="got-container">
		<h1 class="got-page-header__title"><?php esc_html_e( 'Page Not Found', 'getontrack' ); ?></h1>
		<p class="got-page-header__desc"><?php esc_html_e( 'The page you are looking for may have moved or no longer exists.', 'getontrack' ); ?></p>
	</div>
</section>

<section class="got-section">
	<div class="got-container got-content" style="text-align:center;">
		<a class="got-btn got-btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php esc_html_e( 'Return Home', 'getontrack' ); ?>
		</a>
	</div>
</section>

<?php
get_footer();

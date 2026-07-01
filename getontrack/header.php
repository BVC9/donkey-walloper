<?php
/**
 * Header template.
 *
 * @package GetOnTrack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="got-skip-link screen-reader-text" href="#main-content"><?php esc_html_e( 'Skip to content', 'getontrack' ); ?></a>

<header class="got-header" id="site-header">
	<div class="got-container got-header__inner">
		<div class="got-header__brand">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="got-logo" rel="home">
					<span class="got-logo__mark" aria-hidden="true"></span>
					<span class="got-logo__text">
						<strong><?php bloginfo( 'name' ); ?></strong>
						<small><?php echo esc_html( got_get_mod( 'got_tagline', __( 'Peptide Therapy for Longevity', 'getontrack' ) ) ); ?></small>
					</span>
				</a>
			<?php endif; ?>
		</div>

		<nav class="got-nav" id="site-navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'getontrack' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_class'     => 'got-nav__list',
				'container'      => false,
				'fallback_cb'    => 'got_fallback_menu',
			) );
			?>
		</nav>

		<div class="got-header__actions">
			<a class="got-btn got-btn--primary got-btn--sm" href="<?php echo esc_url( got_get_mod( 'got_cta_url', '#consultation' ) ); ?>">
				<?php echo esc_html( got_get_mod( 'got_cta_text', __( 'Book Consultation', 'getontrack' ) ) ); ?>
			</a>
			<button class="got-nav-toggle" type="button" aria-expanded="false" aria-controls="site-navigation">
				<span class="screen-reader-text"><?php esc_html_e( 'Toggle menu', 'getontrack' ); ?></span>
				<span class="got-nav-toggle__bar" aria-hidden="true"></span>
				<span class="got-nav-toggle__bar" aria-hidden="true"></span>
				<span class="got-nav-toggle__bar" aria-hidden="true"></span>
			</button>
		</div>
	</div>
</header>

<main id="main-content" class="got-main">

<?php
/**
 * Front page template.
 *
 * @package GetOnTrack
 */

$headline          = get_theme_mod( 'getontrack_headline', __( 'Great things are coming soon', 'getontrack' ) );
$tagline           = get_theme_mod( 'getontrack_tagline', __( 'Stay tuned', 'getontrack' ) );
$footer_text       = get_theme_mod( 'getontrack_footer_text', '' );
$footer_link_label = get_theme_mod( 'getontrack_footer_link_label', '' );
$footer_link_url   = get_theme_mod( 'getontrack_footer_link_url', '' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

	<h1><?php echo esc_html( $headline ); ?></h1>
	<p class="getontrack-tagline"><?php echo esc_html( $tagline ); ?></p>

	<?php if ( $footer_text || ( $footer_link_label && $footer_link_url ) ) : ?>
		<span class="getontrack-footer">
			<?php if ( $footer_text ) : ?>
				<p><?php echo esc_html( $footer_text ); ?></p>
			<?php endif; ?>
			<?php if ( $footer_link_label && $footer_link_url ) : ?>
				<a href="<?php echo esc_url( $footer_link_url ); ?>"><?php echo esc_html( $footer_link_label ); ?></a>
			<?php endif; ?>
		</span>
	<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>

<?php
/**
 * No content template.
 *
 * @package GetOnTrack
 */
?>

<div class="got-empty">
	<h2><?php esc_html_e( 'Nothing found', 'getontrack' ); ?></h2>
	<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'getontrack' ); ?></p>
	<?php get_search_form(); ?>
</div>

<?php
/**
 * Fallback template.
 *
 * @package GetOnTrack
 */

get_header();
?>

<main class="getontrack-main">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<article <?php post_class(); ?>>
				<h1><?php the_title(); ?></h1>
				<div><?php the_content(); ?></div>
			</article>
		<?php endwhile; ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No content found.', 'getontrack' ); ?></p>
	<?php endif; ?>
</main>

<?php
get_footer();

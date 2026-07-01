<?php
/**
 * Main template fallback.
 *
 * @package GetOnTrack
 */

get_header();
?>

<section class="got-page-header">
	<div class="got-container">
		<h1 class="got-page-header__title">
			<?php
			if ( is_home() && ! is_front_page() ) {
				single_post_title();
			} elseif ( is_archive() ) {
				the_archive_title();
			} elseif ( is_search() ) {
				printf( esc_html__( 'Search: %s', 'getontrack' ), esc_html( get_search_query() ) );
			} else {
				esc_html_e( 'Latest Insights', 'getontrack' );
			}
			?>
		</h1>
		<?php if ( is_archive() ) : ?>
			<div class="got-page-header__desc"><?php the_archive_description(); ?></div>
		<?php endif; ?>
	</div>
</section>

<section class="got-section">
	<div class="got-container">
		<?php if ( have_posts() ) : ?>
			<div class="got-post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', get_post_type() );
				endwhile;
				?>
			</div>
			<?php the_posts_pagination( array(
				'prev_text' => '&larr; ' . __( 'Previous', 'getontrack' ),
				'next_text' => __( 'Next', 'getontrack' ) . ' &rarr;',
			) ); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();

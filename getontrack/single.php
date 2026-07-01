<?php
/**
 * Single post template.
 *
 * @package GetOnTrack
 */

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

<article <?php post_class( 'got-article' ); ?>>
	<header class="got-page-header">
		<div class="got-container">
			<p class="got-article__meta">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				<?php if ( has_category() ) : ?>
					<span class="got-article__cats"><?php the_category( ', ' ); ?></span>
				<?php endif; ?>
			</p>
			<h1 class="got-page-header__title"><?php the_title(); ?></h1>
		</div>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="got-article__featured">
			<div class="got-container">
				<?php the_post_thumbnail( 'large', array( 'class' => 'got-article__image' ) ); ?>
			</div>
		</div>
	<?php endif; ?>

	<section class="got-section">
		<div class="got-container got-content">
			<?php the_content(); ?>
		</div>
	</section>
</article>

<?php endwhile; ?>

<?php
get_footer();

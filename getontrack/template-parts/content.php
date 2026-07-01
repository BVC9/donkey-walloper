<?php
/**
 * Post card template.
 *
 * @package GetOnTrack
 */
?>

<article <?php post_class( 'got-card got-card--post' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" class="got-card__image-link">
			<?php the_post_thumbnail( 'got-card', array( 'class' => 'got-card__image' ) ); ?>
		</a>
	<?php endif; ?>
	<div class="got-card__body">
		<p class="got-card__meta">
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
		</p>
		<h2 class="got-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>
		<p class="got-card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<a class="got-card__link" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Read more', 'getontrack' ); ?>
			<?php got_icon( 'arrow' ); ?>
		</a>
	</div>
</article>

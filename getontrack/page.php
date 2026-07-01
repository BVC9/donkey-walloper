<?php
/**
 * Page template.
 *
 * @package GetOnTrack
 */

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

<section class="got-page-header">
	<div class="got-container">
		<h1 class="got-page-header__title"><?php the_title(); ?></h1>
	</div>
</section>

<section class="got-section got-section--page">
	<div class="got-container got-content">
		<?php the_content(); ?>
	</div>
</section>

<?php endwhile; ?>

<?php
get_footer();

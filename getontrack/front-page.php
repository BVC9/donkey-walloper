<?php
/**
 * Front page template.
 *
 * @package GetOnTrack
 */

get_header();
?>

<?php get_template_part( 'template-parts/section', 'hero' ); ?>
<?php get_template_part( 'template-parts/section', 'trust' ); ?>
<?php get_template_part( 'template-parts/section', 'benefits' ); ?>
<?php get_template_part( 'template-parts/section', 'peptides' ); ?>
<?php get_template_part( 'template-parts/section', 'science' ); ?>
<?php get_template_part( 'template-parts/section', 'process' ); ?>
<?php get_template_part( 'template-parts/section', 'testimonials' ); ?>
<?php get_template_part( 'template-parts/section', 'faq' ); ?>
<?php get_template_part( 'template-parts/section', 'cta' ); ?>

<?php
get_footer();

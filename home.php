<?php
/**
 * AR2's home template.
 *
 * @package AR2
 * @since 1.0
 */
?>
<?php get_header(); ?>

<div id="content" class="section" role="main">
<?php ar2_above_content() ?>



<?php if ( !$paged ) : ?>

<?php ar2_render_zone( 'home' ) ?>

<?php if ( is_active_sidebar( 'Bottom Content #1' ) ) : ?>
<div class="bottom-sidebar" id="bottom-content-1" role="complementary">
	<?php if ( !dynamic_sidebar( 'Bottom Content #1' ) ) : ?>
	<?php endif; ?>
</div>
<?php endif ?>

<?php if ( is_active_sidebar( 'Bottom Content #2' ) ) : ?>
<div class="bottom-sidebar" id="bottom-content-2" role="complementary">
	<?php if ( !dynamic_sidebar( 'Bottom Content #2' ) ) : ?>
	<?php endif; ?>
</div>
<?php endif ?>

<?php else: ?>

<?php
// Retrieve latest news section and render the remaining.
$news_section = $ar2_postviews->get_section( 'news-posts' );
$news_section->settings[ 'title' ] = __( 'Blog Archives', 'ar2' );
$news_section->settings[ 'type' ] = ar2_get_theme_option( 'archive_display' );
$news_section->render();
?>

<?php endif; ?>

<?php ar2_below_content() ?>
</div><!-- #content -->
    
<?php get_sidebar(); ?>
<?php get_footer(); ?>
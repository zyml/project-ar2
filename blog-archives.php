<?php
/*
 * Template Name: Blog Archives
 */
?>

<?php get_header(); ?>

<div id="content" class="section" role="main">
<?php ar2_above_content() ?>

<?php 
$posts_per_page = get_option( 'posts_per_page' );
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$wp_query = new WP_Query( array ( 'paged' => $paged, 'posts_per_page' => $posts_per_page ) );
?>

<?php if ( $wp_query->have_posts() ) : ?>
<h1 class="archive-title"><?php _e( 'Blog Archives', 'ar2' ) ?></h1>

<div id="archive-posts">
<?php ar2_render_posts( null, array ( 'type' => ar2_get_theme_option( 'archive_display' ) ), true ) ?>
</div><!-- #archive-posts -->
    
<?php else: ?>

<?php ar2_post_notfound() ?>

<?php endif; ?>

<?php ar2_below_content() ?>

</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
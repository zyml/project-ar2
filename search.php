<?php get_header(); ?>

<div id="content" class="section" role="main">
<?php ar2_above_content() ?>

<h1 class="archive-title"><?php _e('Search Results', 'ar2') ?></h1>

<?php if ( have_posts() ) : ?>
<?php get_search_form(); ?>

<div id="archive-posts">
<?php ar2_render_posts( null, array ( 'type' => ar2_get_theme_option( 'archive_display' ) ), true ) ?>
</div><!-- #archive-posts -->

<?php else: ?>

<p class="no-results"><?php _e( "Sorry, we couldn't find any results based on your search query.", 'ar2' ) ?></p>
<?php get_search_form() ?>

<?php endif ?>

<?php ar2_below_content() ?>
</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
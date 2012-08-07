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

<h1 class="archive-title"><?php _e( 'Blog Archives', 'ar2' ) ?></h1>
<div id="archive-posts">
<?php
$section = new AR2_PostViews_Section( null, 'archive-posts', null, array (
	
	'type'				=> ar2_get_theme_option( 'archive_display' ),
	'title'				=> null,
	'use_query_posts'	=> true,
	'count'				=> get_option( 'posts_per_page' ),
	'enabled'			=> true,
	'persistent'		=> false,
	
) );
ar2_render_section( $section );

if ( $section->query->max_num_pages > 1 )
	ar2_post_navigation();
?>
</div><!-- #archive-posts -->

<?php endif; ?>

<?php ar2_below_content() ?>
</div><!-- #content -->
    
<?php get_sidebar(); ?>
<?php get_footer(); ?>
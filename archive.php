<?php get_header(); ?>

<div id="content" class="section" role="main">
<?php ar2_above_content() ?>

<?php if ( have_posts() ) : ?>
	<?php $post = $posts[ 0 ]; // Hack. Set $post so that the_date() works. ?>

	<?php if ( is_category() ) : ?>
        <h1 class="archive-title"><?php printf( __( '%s Archive', 'ar2'), single_cat_title( '', false) ) ?></h1>
    <?php elseif ( is_tag() ) : ?>
        <h1 class="archive-title"><?php printf( __('%s Archive', 'ar2' ), single_tag_title( '', false) ) ?></h1>
	<?php elseif ( is_tax() ) : $term = $wp_query->get_queried_object(); ?>
		<h1 class="archive-title"><?php printf( __( '%s Archive', 'ar2' ), $term->name ) ?></h1>
    <?php elseif ( is_day() ) : ?>
        <h1 class="archive-title"><?php printf( __( 'Archive for %s', 'ar2' ), get_the_time( __('F jS, Y', 'ar2') ) ) ?></h1>
    <?php elseif ( is_month() ) : ?>
        <h1 class="archive-title"><?php printf( __( 'Archive for %s', 'ar2' ), get_the_time( __('F, Y', 'ar2') ) ) ?></h1>
    <?php elseif ( is_year() ) : ?>
        <h1 class="archive-title"><?php printf( __( 'Archive for %s', 'ar2' ), get_the_time( __('Y', 'ar2') ) ) ?></h1>
    <?php elseif ( is_author() ) : ?>
        <h1 class="archive-title"><?php _e( 'Author Archive', 'ar2' ) ?></h1>
    <?php else : ?>
        <h1 class="archive-title"><?php _e( 'Blog Archives', 'ar2' ) ?></h1>
    <?php endif; ?>
    
	<div id="archive-posts">
	<?php ar2_render_posts( null, array ( 'type' => ar2_get_theme_option( 'archive_display' ) ), true ) ?>
	</div><!-- #archive-posts -->
	
<?php else : ?>
	<?php ar2_post_notfound() ?>
<?php endif; ?>

<?php ar2_below_content() ?>
</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
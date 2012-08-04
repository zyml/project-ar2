<?php get_header(); ?>

<div id="content" class="section" role="main">
<?php ar2_above_content() ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php ar2_above_post() ?>
	
    <?php get_template_part( 'content', get_post_format() ) ?>
    
	<?php ar2_below_post() ?>
    <?php comments_template( '', true ); ?>
	<?php ar2_below_comments() ?>
    
<?php endwhile; else: ?>

<?php ar2_post_notfound() ?>

<?php endif; ?>

<?php ar2_below_content() ?>
</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
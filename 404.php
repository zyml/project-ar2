<?php
/**
 * AR2's 404 template.
 *
 * @package AR2
 * @since 1.0
 */
?>
<?php get_header(); ?>

<div id="content" class="section" role="main">
<?php ar2_above_content() ?>

<?php get_template_part( 'section', '404' ) ?>

<?php ar2_below_content() ?>
</div><!-- #content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
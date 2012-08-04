<?php
/**
 * The template for displaying content in 'Node Based' post section.
 * @since 1.6
 */
?>
<div <?php post_class() ?>>
	<div class="entry-thumbnail clearfix">
		<a rel="bookmark" href="<?php the_permalink() ?>">
			<?php echo ar2_get_thumbnail( 'section-thumb', false, array( 'class' => 'section-thumb' ) ) ?>
			<span class="entry-comments"><?php echo get_comments_number() ?></span>
		</a>
	</div>
	
	<h3 class="entry-title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title() ?></a></h3>
	
	<?php if ( ar2_get_theme_option( 'nodebased_show_excerpts' ) ) : ?>
	<abbr class="published"><?php printf( __( 'Posted on %s', 'ar2' ), ar2_posted_on( false ) ) ?></abbr>
	<div class="entry-summary"><?php the_excerpt() ?></div>
	<?php endif ?>
	
</div>
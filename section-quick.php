<?php
/**
 * The template for displaying content in 'Quick Preview' post section.
 * @since 1.6
 */
?>

<li <?php post_class() ?>>
	<a class="entry-thumbnail" rel="bookmark" href="<?php the_permalink() ?>">
		<?php echo ar2_get_thumbnail( 'section-thumb', false, array( 'class' => 'section-thumb' ) ) ?>
		<span class="entry-comments"><?php echo get_comments_number() ?></span>
	</a>
	<?php if ( get_post_format() !== false ) : ?>
	<span class="entry-format" style="float: right"><?php echo get_post_format_string( get_post_format() ) ?></span>
	<?php endif ?>
	<h3 class="entry-title">
		<a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title() ?></a>
	</h3>
	
	<div class="entry-summary">
		<div class="entry-info">
			<abbr class="published"><?php printf( __( 'Posted on %s', 'ar2' ), ar2_posted_on( false ) ) ?></abbr>
		</div>
		<?php echo get_the_excerpt() ?>
		<p><a class="more-link" href="<?php the_permalink() ?>" title="<?php printf( __('Permalink to %s', 'ar2'), get_the_title() ) ?>">
		<?php _e('Continue Reading', 'ar2') ?>
		</a></p>
	</div>	
</li>
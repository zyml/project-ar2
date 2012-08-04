<?php
/**
 * The template for displaying content in 'Slideshow' post section.
 * @since 2.0
 */
?>

<li id="post-<?php the_ID() ?>">

	<a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo ar2_get_thumbnail( 'single-thumb' ) ?></a>
	<div class="entry-meta">
		<a class="entry-title" href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
		<div class="entry-summary"><?php the_excerpt() ?></div>
	</div>

</li>
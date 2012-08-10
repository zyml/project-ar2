<?php
/**
 * The template for displaying content in 'Traditional' post section.
 * @since 1.6
 */
?>

<article id="post-<?php the_ID() ?>" <?php post_class() ?>>
		
	<header class="entry-header clearfix">
		<?php 
		if ( ar2_get_theme_option( 'post_display[post_cats]' ) ) {
		 	$post_cats = array();
			$cats = get_the_category();
			
			foreach ( $cats as $c )
				$post_cats[] = '<a href="' . get_category_link( $c->cat_ID ) . '">' . $c->cat_name . '</a>';
			
			echo '<div class="entry-cats">' . implode( ', ', $post_cats ) . '</div>';
		}	
		?>
		
		<h1 class="entry-title">
		<?php if ( false !== get_post_format() ) : ?>
		<span class="entry-format"><?php echo get_post_format_string( get_post_format() ) ?></span>
		<?php endif ?>
		<a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title() ?></a>
		</h1>
		
		<?php if ( ar2_get_theme_option( 'post_display[post_author]' ) ) : ?>
		<div class="entry-author">
			<?php printf( __( 'Posted by %1$s %2$s', 'ar2' ), 
				'<address class="author vcard"><a rel="author" class="url fn n" href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . esc_attr( get_the_author() ) . '">' . get_the_author() . '</a></address>',
				'<abbr class="published">' . ar2_posted_on( false ) . '</abbr>'
			); ?>
			<?php edit_post_link( __( 'Edit', 'ar2' ) ) ?>
		</div>
		<?php else : ?>
		<div class="entry-author">
			<?php printf( __( 'Posted on %s', 'ar2' ), '<abbr class="published">' . ar2_posted_on( false ) . '</abbr>' ); ?>
			<?php edit_post_link( __( 'Edit', 'ar2' ) ) ?>
		</div>
		<?php endif ?>
		
		<?php if ( ar2_get_theme_option( 'post_display[excerpt]' ) && has_excerpt() ) : ?>
			<div class="entry-excerpt"><?php the_excerpt() ?></div>
		<?php endif ?>
		
		<?php if ( ar2_get_theme_option( 'post_display[single_thumbs]' ) && has_post_thumbnail( $post->ID ) ) : ?>
			<div class="entry-photo"><?php echo ar2_get_thumbnail( 'single-thumb' ) ?></div>
		<?php endif ?>
			
	</header><!-- .entry-header -->
    
    <div class="entry-content clearfix">
	<?php the_content( __( 'Read the rest of this entry', 'ar2' ) ); ?>  
    </div>

	<footer class="entry-footer clearfix">
	
		<?php if ( ar2_get_theme_option( 'post_display[post_tags]' ) && is_array( get_the_tags() ) ) : ?>
			<div class="entry-tags tags">
				<?php the_tags( '<strong>' . __( 'Tags: ', 'ar2' ) . '</strong>', ' ' ) ?>
			</div>
		<?php endif ?>
        
    </footer><!-- .entry-footer -->
    
</article>
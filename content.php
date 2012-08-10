<?php
/**
 * The default template for displaying content.
 * @since 2.0
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
			<?php printf( __( 'Posted %s', 'ar2' ), '<abbr class="published">' . ar2_posted_on( false ) . '</abbr>' ); ?>
			<?php edit_post_link( __( 'Edit', 'ar2' ) ) ?>
		</div>
		<?php endif ?>
		
		<?php if ( ar2_get_theme_option( 'post_display[excerpt]' ) && has_excerpt() ) : ?>
			<div class="entry-excerpt"><?php the_excerpt() ?></div>
		<?php endif ?>
		
		<?php if ( ar2_get_theme_option( 'post_display[single_thumbs]' ) && has_post_thumbnail( $post->ID ) ) : ?>
			<div class="entry-photo"><?php echo ar2_get_thumbnail( 'single-thumb' ) ?></div>
		<?php endif ?>
		
		<?php if ( ar2_get_theme_option( 'post_display[post_social]' ) ) : ?>
		<div class="entry-social">
				<div class="addthis_toolbox addthis_default_style" 
					addthis:url="<?php echo esc_attr( get_permalink( $post->ID ) ) ?>"
					addthis:title="<?php echo esc_attr( get_the_title() ) ?>"
					addthis:description="<?php the_excerpt_rss( 30, 2 ) ?>">
					<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
					<a class="addthis_button_tweet"></a>
					<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
					<a class="addthis_counter addthis_pill_style"></a>
				</div>
		</div>
		<?php endif ?>
			
	</header><!-- .entry-header -->
    
    <div class="entry-content clearfix">
	<?php the_content( __( '<p>Read the rest of this entry &raquo;</p>', 'ar2' ) ); ?>  
    <?php wp_link_pages( array( 'before' => '<p class="post-navigation"><strong>' . __( 'Pages:', 'ar2' ) . '</strong>', 
		'after' => '</p>', 'next_or_number' => 'number', 'pagelink' => '<span>%</span>' ) ); ?>
	</div>

	<footer class="entry-footer clearfix">
	
		<?php if ( ar2_get_theme_option( 'post_display[post_tags]' ) && is_array( get_the_tags() ) ) : ?>
			<div class="entry-tags tags">
				<?php the_tags( '<strong>' . __( 'Tags: ', 'ar2' ) . '</strong>', ' ' ) ?>
			</div>
		<?php endif ?>

        <?php if ( ar2_get_theme_option( 'post_display[display_author]' ) ) : ?>
        
			<?php $id = get_the_author_meta( 'ID' ); ?>
			<div class="about-author clearfix">
				<a class="author-avatar" href="<?php get_author_posts_url( $id ) ?>"><?php echo get_avatar( $id, 64 ) ?></a>
				<div class="author-meta">
					<h4><?php printf( __( 'About %s', 'ar2' ), get_the_author_meta( 'display_name' ) ) ?></h4>
					<?php 
					if ( the_author_meta( 'description' ) == '' )
						_e( 'No information is provided by the author.', 'ar2' );
					else
						the_author_meta( 'description' );
					?>
				</div>
			</div>
			
		<?php endif ?>
        
    </footer><!-- .entry-footer -->
</article>
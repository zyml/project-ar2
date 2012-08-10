<?php get_header(); ?>

<div id="content" class="section" role="main">
<?php ar2_above_content() ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php ar2_above_post() ?>
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
			
			<h1 class="entry-title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title() ?></a></h1>
			
			<?php if ( ar2_get_theme_option( 'post_display[post_author]' ) ) : ?>
			<div class="entry-author">
				<?php printf( __( 'Posted by %1$s on %2$s', 'ar2' ), 
					'<address class="author vcard"><a class="url fn n" href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . esc_attr( get_the_author() ) . '">' . get_the_author() . '</a></address>',
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
			
			
			<?php if ( ar2_get_theme_option( 'post_display[single_thumbs]' ) && has_post_thumbnail( $post->ID ) ) : ?>
				<div class="entry-photo"><?php echo ar2_get_thumbnail( 'single-thumb' ) ?></div>
			<?php endif ?>
			
			<?php if ( ar2_get_theme_option( 'post_display[post_social]' ) ) : ?>
			<div class="entry-social">
					<div class="addthis_toolbox addthis_default_style">
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
        <?php wp_link_pages( array( 'before' => __( '<p><strong>Pages:</strong> ', 'ar2' ), 
			'after' => '</p>', 'next_or_number' => 'number' ) ); ?>
		</div>

		<footer class="entry-footer clearfix">
		
			<?php if ( ar2_get_theme_option( 'post_display[post_social]' ) ) : ?>
			<div class="entry-social">
					<div class="addthis_toolbox addthis_default_style">
						<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
						<a class="addthis_button_tweet"></a>
						<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
						<a class="addthis_counter addthis_pill_style"></a>
					</div>
			</div>
			<?php endif ?>
		
        </footer><!-- .entry-footer -->
    </article>
    
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
<?php get_header(); ?>

<div id="content" class="section" role="main">
<?php ar2_above_content() ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php ar2_above_post() ?>
	<article id="post-<?php the_ID() ?>" <?php post_class() ?>>
		
		<header class="entry-header clearfix">
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
				<?php printf( __( 'Posted on %s', 'ar2' ), '<abbr class="published">' . ar2_posted_on( false ) . '</abbr>' ); ?>
				<?php edit_post_link( __( 'Edit', 'ar2' ) ) ?>
			</div>
			<?php endif ?>
			
			<div class="entry-caption"><?php the_excerpt() ?></div>
			
			<div class="entry-photo">
			<a href="<?php echo wp_get_attachment_url() ?>" title="<?php the_title_attribute(); ?>" rel="attachment">
			<?php echo wp_get_attachment_image( $post->ID, 'single-thumb' ) ?>
			</a>
			</div>
			
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
        <?php wp_link_pages( array( 'before' => '<p class="post-navigation"><strong>' . __( 'Pages:', 'ar2' ) . '</strong>', 
			'after' => '</p>', 'next_or_number' => 'number', 'pagelink' => '<span>%</span>' ) ); ?>
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
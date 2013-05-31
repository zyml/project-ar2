<?php get_header(); ?>

<div id="content" class="section" role="main">
<?php ar2_above_content() ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php ar2_above_post() ?>
	<article id="post-<?php the_ID() ?>" <?php post_class() ?>>
		
		<header class="entry-header clearfix">
				
			<h1 class="entry-title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title() ?></a></h1>
				
			<?php if ( ar2_get_theme_option( 'post_display[single_thumbs]' ) && has_post_thumbnail( $post->ID ) ) : ?>
				<div class="entry-photo"><?php echo ar2_get_thumbnail( 'single-thumb' ) ?></div>
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
            <div class="addthis_toolbox addthis_default_style" >
              <div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare">
              <span class="bds_more">分享到：</span>
                <a class="bds_qzone"></a>
                <a class="bds_tsina"></a>
                <a class="bds_tqq"></a>
                <a class="bds_renren"></a>
                <a class="bds_t163"></a>
                <a class="shareCount"></a>
              </div>
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

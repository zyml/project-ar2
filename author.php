<?php get_header(); ?>

<div id="content" class="section" role="main">
<?php ar2_above_content() ?>

<article id="post-0" class="article author-archive">
	<?php the_post(); // Get author information ?>
	
	<header class="entry-header">
		<h1 class="entry-title"><?php printf( __( 'About Author: %s', 'ar2' ), '<span class="vcard"><a class="url fn n" href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author_meta( 'display_name' ) . '</a></span>' ); ?></h1>
	</header>
	
	<div class="about-author clearfix">
		<div class="author-avatar">
			<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ) ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 48 ) ?></a>
		</div>
		<div class="author-meta">
			<?php 
			if ( the_author_meta( 'description' ) == '' )
				_e( 'No information is provided by the author.', 'ar2' );
			else
				the_author_meta( 'description' );
			?>
		</div>
	</div>

</article>

<h2 class="module-title"><?php printf( __( 'Posts by %s', 'ar2' ), '<span class="vcard"><a class="url fn n" href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author_meta( 'display_name' ) . '</a></span>' ); ?></h2>

<div id="archive-posts">
<?php 
ar2_render_posts( null, array ( 
	'type' => ar2_get_theme_option( 'archive_display' ), 
	'query_args' => array ( 'author' => get_the_author_meta( 'ID' ) ),
), true );
?>
</div><!-- #archive-posts -->


<?php ar2_below_content() ?>
</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
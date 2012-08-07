</div><!-- #container -->

<?php wp_reset_query() ?>

<div id="primary" class="aside main-aside sidebar" role="complementary">
<?php ar2_above_sidebar() ?>

	<?php if ( !dynamic_sidebar( 'primary-sidebar' ) ) : ?>
	
	<aside id="text-static" class="widget clearfix">
		<h3 class="widget-title"><?php _e( 'Welcome to Project AR2!', 'ar2' ) ?></h3>
		<p><?php _e( 'To remove this widget, simply go to the Widgets page in your WordPress admin and add a widget into this sidebar.', 'ar2') ?></p>
	</aside>
	
	<aside id="recent-posts-static" class="widget clearfix">
		<h3 class="widget-title"><?php _e( 'Recent Posts', 'ar2' ) ?></h3>
		<?php
		
		$r = new WP_Query( array (
			'showposts'				=> 10,
			'what_to_show'			=> 'posts',
			'nopaging'				=> 0,
			'post_status'			=> 'publish',
			'ignore-sticky_posts'	=> 1,
		) );
		
		if ( $r->have_posts() ) :
		
		?>
		<ul>
		<?php while ( $r->have_posts() ) : $r->the_post() ?>
		<li><a title="<?php if ( get_the_title() ) the_title(); else the_ID(); ?>" href="<?php the_permalink() ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a></li>
		<?php endwhile ?>
		</ul>
		
		<?php endif; ?>
		<?php wp_reset_query() ?>
		
	</aside>
	
	<aside id="tag-cloud-static" class="widget clearfix">
		<h3 class="widget-title"><?php _e( 'Tag Cloud', 'ar2' ) ?></h3>
		<div class="tags"><?php wp_tag_cloud() ?></div>
	</aside>
	
	<?php endif ?>
	
</div><!-- #primary-sidebar -->

<?php
/**
 * This file will eventually place the codes for theme widgets that is
 * compatible with WordPress 2.8.
 */
 
/**
 * AR2_Tabbed_Sidebar class.
 * @since 1.3
 * @extends WP_Widget
 */
class AR2_Tabbed_Sidebar extends WP_Widget {
	
	public $display_thumbs;
	public $commentcount, $postcount;

	/**
	 * Constructor.
	 * @since 1.3
	 */
	public function __construct() {
	
		$widget_args = array(
			'classname'		=> 'ar2_tabbed_sidebar',
			'description'	=> __( 'Sidebar containing tabs that displays posts, comments and tags.', 'ar2' ),
		);
		$this->WP_Widget( 'ar2_tabbed_sidebar', sprintf( __( '%s - Tabbed Sidebar', 'ar2' ), wp_get_theme()->get( 'Name' ) ), $widget_args );
		
		add_action( 'ar2_tabbed_sidebar_tab-latest', array( &$this, 'latest_tab' ) );
		add_action( 'ar2_tabbed_sidebar_tab-random', array( &$this, 'random_tab' ) );
		add_action( 'ar2_tabbed_sidebar_tab-comments', array( &$this, 'comments_tab' ) );
		add_action( 'ar2_tabbed_sidebar_tab-tags', array( &$this, 'tags_tab' ) );
		
		if ( is_active_widget( false, false, $this->id_base ) ) {
			add_action( 'wp_head', array( &$this, 'load_js' ) );
			add_action( 'ar2_custom_scripts', array( &$this, 'do_js' ) );
		}
		
	}
	
	/**
	 * load_js function.
	 * @since 1.3
	 */
	public function load_js() {
	
		wp_enqueue_script( 'jquery-ui-tabs', null, array( 'jquery-ui-core', 'jquery' ), null, false ); 
		
	}
	
	/**
	 * do_js function.
	 * @since 1.3
	 */
	public function do_js() {
		?>
		$('.multi-sidebar').tabs();
		<?php
	}
	
	/**
	 * get_tabs function.
	 * @since 1.3
	 */
	public function get_tabs() {
	
		$_default_tabs = array(
			'latest'		=> __( 'Latest', 'ar2' ),
			'random'		=> __( 'Random', 'ar2' ),
			'comments'		=> __( 'Comments', 'ar2' ), 
			'tags'			=> __( 'Tags', 'ar2' ),
		);
		
		return apply_filters( 'ar2_tabbed_sidebar_tabs', $_default_tabs );
		
	}
	
	/**
	 * widget function.
	 * @since 1.3
	 */
	public function widget( $args, $instance ) {
	
		global $wpdb;		
		extract( $args, EXTR_SKIP );
		
		$this->postcount = $instance[ 'postcount' ];
		$this->commentcount = $instance[ 'commentcount' ];
		$this->display_thumbs = $instance[ 'display_thumbs' ];
		
		if ( !$instance[ 'order' ] ) $instance[ 'order' ] = $this->get_tabs();

		if ( $instance[ 'display_home' ] && !is_home() )
			return false;
		?>
		<aside class="multi-sidebar-container">
			<div class="multi-sidebar clearfix">
			
				<ul class="tabs clearfix">
				<?php $this->render_sidebar_tabs( $instance[ 'order' ] ) ?>
				</ul>
				
				<?php
				foreach ( $instance['order'] as $tab ) {
					echo '<div id="s-' . $tab . '" class="widget clearfix">';
					do_action( 'ar2_tabbed_sidebar_tab-' . $tab );
					echo '</div><!-- #s-' . $tab . ' -->';
				}
				?>
			</div>
		</aside>
		<?php
		
	}
	
	public function latest_tab() {
	
		ar2_widgets_post_loop( 'sidebar-latest', array (
			'show_thumbs'		=> $this->display_thumbs,
			'show_excerpt'		=> false,
			'query'				=> array (
				'posts_per_page'	=> $this->postcount
			)
		) );
		
	}
	
	public function random_tab() {
	
		ar2_widgets_post_loop( 'sidebar-random', array(
			'show_thumbs'		=> $this->display_thumbs,
			'show_excerpt'		=> false,
			'query'				=> array (
				'posts_per_page'	=> $this->postcount,
				'orderby'			=> 'rand',
			)
		) );
		
	}
	
	public function comments_tab() {
	
		$comments = get_comments( array( 'status' => 'approve', 'number' => $this->commentcount ) );	
		if ($comments) {
			echo '<ul class="sidebar-comments">';
			foreach ($comments as $comment) {
				echo '<li class="recentcomments clearfix">';
				if ( $this->display_thumbs ) echo get_avatar( $comment->user_id, 36 );
				echo '<span class="entry-author">' . $comment->comment_author . '</span><br />';
				echo '<a class="entry-title" href="' . get_permalink( $comment->comment_post_ID ) . '">' . get_the_title( $comment->comment_post_ID ) . '</a>';
				echo '</li>';
			}
			echo '</ul>';
		}
		
	}
	
	public function tags_tab() {
	
		echo '<div class="tagcloud">';
		if ( function_exists( 'wp_cumulus_insert' ) ) {
			$args = array(
				'width'		=> 280,
				'height'	=> 280
			);
			wp_cumulus_insert( $args );
		} else {
			wp_tag_cloud();
		}
		echo '</div>';
		
	}
	
	public function update( $new_instance, $old_instance ) {
	
		$instance = $old_instance;
		$instance[ 'order' ] = $new_instance[ 'order' ];
		$instance[ 'display_home' ] = ( boolean )( $new_instance[ 'display_home' ] );
		$instance[ 'display_thumbs' ] = ( boolean )( $new_instance[ 'display_thumbs' ] );
		$instance[ 'postcount' ] = $new_instance[ 'postcount' ];
		$instance[ 'commentcount' ] = $new_instance[ 'commentcount' ];
		
		return $instance;
		
	}
	
	public function form( $instance ) {
	
		$instance = wp_parse_args( ( array )$instance, array ( 
			'order' => array ( 'latest', 'random', 'comments', 'tags' ), 
			'display_home' => false, 
			'display_thumbs' => true, 
			'postcount' => 8,
			'commentcount' => 8
		) );
		$order = $instance[ 'order' ];

		?>

		<p>
		<label for="<?php echo $this->get_field_id( 'order' ) ?>"><?php _e( 'Tabbed Sidebar Order:', 'ar2' ) ?></label><br />
		<select style="width: 200px" name="<?php echo $this->get_field_name( 'order' ) ?>[0]"><?php $this->get_tabbed_opts( $order[0], 'latest' ); ?></select><br />
		<select style="width: 200px" name="<?php echo $this->get_field_name( 'order' ) ?>[1]"><?php $this->get_tabbed_opts( $order[1], 'random' ); ?></select><br />
		<select style="width: 200px" name="<?php echo $this->get_field_name( 'order' ) ?>[2]"><?php $this->get_tabbed_opts( $order[2], 'comments' ); ?></select><br />
		<select style="width: 200px" name="<?php echo $this->get_field_name( 'order' ) ?>[3]"><?php $this->get_tabbed_opts( $order[3], 'tags' ); ?></select>
		</p>

		<p><label for="<?php echo $this->get_field_id( 'postcount' ) ?>"><?php _e( 'Post Count:', 'ar2' ) ?></label>
		<select id="<?php echo $this->get_field_id( 'postcount' ) ?>" name="<?php echo $this->get_field_name( 'postcount' ) ?>">
			<?php for ( $i = 1; $i <= 12; $i++ ) : ?>
			<option value="<?php echo $i ?>"<?php selected( $i, $instance[ 'postcount' ] ) ?>><?php echo $i ?>
			</option>
			<?php endfor; ?>
		</select><br />
		
		<label for="<?php echo $this->get_field_id( 'commentcount' ) ?>"><?php _e( 'Comments Count:', 'ar2' ) ?></label>
		<select id="<?php echo $this->get_field_id( 'commentcount' ) ?>" name="<?php echo $this->get_field_name( 'commentcount' ) ?>">
			<?php for ( $i = 1; $i <= 12; $i++ ) : ?>
			<option value="<?php echo $i ?>"<?php selected( $i, $instance[ 'commentcount' ] ) ?>><?php echo $i ?>
			</option>
			<?php endfor; ?>
		</select>
		</p>
		
		<p>
		<input type="checkbox" name="<?php echo $this->get_field_name( 'display_home' ) ?>" id="<?php echo $this->get_field_name( 'display_home' ) ?>" <?php checked( $instance[ 'display_home' ], 1 ) ?> />
		<label for="<?php echo $this->get_field_id( 'display_home' ) ?>"><?php _e( 'Display only in homepage', 'ar2' ) ?></label><br />
		<input type="checkbox" name="<?php echo $this->get_field_name( 'display_thumbs' ) ?>" id="<?php echo $this->get_field_name( 'display_thumbs' ) ?>" <?php checked( $instance[ 'display_thumbs' ], 1 ) ?> />
		<label for="<?php echo $this->get_field_id( 'display_thumbs' ) ?>"><?php _e('Display thumbnails', 'ar2') ?></label>
		</p>
		<?php
		
	}
	
	public function get_tabbed_opts( $selected, $default ) {
	
		$opts = $this->get_tabs();
		
		if ( !$selected ) $selected = $default;
		
		foreach ( $opts as $id => $val ) {
			echo '<option value="' . $id . '" ';
			selected( $selected, $id );
			echo '>';
			
			echo $val;
			echo '</option>';
		}
		
	}
	
	public function render_sidebar_tabs( $order ) {
	
		$order = array_unique( $order );
		$list = $this->get_tabs();
		
		foreach ( $order as $t => $id ) : ?>
			<li><a href="#s-<?php echo $id ?>"><?php echo $list[ $id ] ?></a></li>
		<?php endforeach;

	}
	
}

/**
 * AR2_Featured_Stories class.
 * @since 1.3
 * @extends WP_Widget
 */
class AR2_Featured_Stories extends WP_Widget {
	
	/**
	 * Constructor.
	 * @since 1.3
	 */
	public function __construct() {
	
		$widget_args = array (
			'classname'		=> 'ar2_featured_stories',
			'description'	=> __( 'Featured stories containing post thumbnails and the excerpt based on categories.', 'ar2' ),
		);
		$this->WP_Widget( 'ar2_featured_stories', sprintf( __( '%s - Featured Stories', 'ar2' ), wp_get_theme()->get( 'Name' ) ), $widget_args );
	}
	
	public function widget( $args, $instance ) {
		global $wpdb;		
		extract( $args, EXTR_SKIP );
		
		if ( $instance[ 'no_display_in_home' ] && is_home() ) {
			return false;
		}
		
		$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		
		echo $before_widget;
		echo $before_title . $title . $after_title;
		
		if ( !is_array( $instance[ 'featured_cat' ] ) )
			$instance[ 'featured_cat' ] = array( $instance[ 'featured_cat' ] );
		
		$zero_key = array_search( '0', $instance[ 'featured_cat' ] );
		if ( is_numeric( $zero_key ) ) unset( $instance[ 'featured_cat' ][ $zero_key ] );
		
		ar2_widgets_post_loop( 'featured-stories', array (
			'show_thumbs'		=> $instance[ 'show_thumbs' ],
			'show_excerpt'		=> $instance[ 'show_excerpts' ],
			'query'				=> array(
				'posts_per_page'	=> $instance[ 'postcount' ],
				'category__in'		=> $instance[ 'featured_cat' ],
			)
		) );
		
		echo $after_widget;
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'featured_cat' ] = $new_instance[ 'featured_cat' ];
		$instance[ 'postcount' ] = ( int )strip_tags( $new_instance[ 'postcount' ] );
		$instance[ 'no_display_in_home' ] = ( boolean )( $new_instance[ 'no_display_in_home' ] );
		$instance[ 'show_excerpts' ] = ( boolean )($new_instance[ 'show_excerpts' ] );
		$instance[ 'show_thumbs' ] = ( boolean )( $new_instance[ 'show_thumbs' ] );
		
		return $instance;
	}
	
	public function form( $instance ) {
	
		$instance = wp_parse_args( ( array )$instance, array (
			'title' 				=> __( 'Featured Stories', 'ar2' ), 
			'featured_cat' 			=> array(), 
			'postcount' 			=> 5, 
			'no_display_in_home' 	=> true, 
			'show_excerpts' 		=> true,
			'show_thumbs'			=> true
		) );
		
		if ( !is_array( $instance[ 'featured_cat' ] ) ) $instance[ 'featured_cat' ] = array( 0 );
		
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ) ?>"><?php _e('Title:', 'ar2') ?></label><br />
		<input type="text" id="<?php echo $this->get_field_id( 'title' ) ?>" name="<?php echo $this->get_field_name( 'title' ) ?>" size="33" value="<?php echo strip_tags( $instance[ 'title' ] ) ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'featured_cat' ) ?>"><?php _e( 'Featured Categories:', 'ar2' ) ?></label><br />
		<select multiple="multiple" style="width: 200px; height: 75px" name="<?php echo $this->get_field_name('featured_cat') ?>[]" id="<?php echo $this->get_field_name('featured_cat') ?>">
			<option<?php selected( in_array( 0, $instance[ 'featured_cat' ] ), true ) ?> value="0"><?php _e( 'All Categories', 'ar2' ) ?></option>
		<?php
		foreach( get_categories( 'hide_empty=0' ) as $c ) {
			$selected = '';
			echo '<option' . selected( in_array( $c->cat_ID, $instance[ 'featured_cat' ] ), true ) . ' value="' . $c->cat_ID . '">' . $c->cat_name . '</option>';
		}
		?>
		</select>
		</p>
		
		<p><label for="<?php echo $this->get_field_id( 'postcount' ) ?>"><?php _e( 'How many items would you like to display?', 'ar2' ) ?></label>
		<select id="<?php echo $this->get_field_id( 'postcount' ) ?>" name="<?php echo $this->get_field_name( 'postcount' ) ?>">
			<?php for ( $i = 1; $i <= 20; $i++ ) : ?>
			<option value="<?php echo $i ?>"<?php selected( $i, $instance[ 'postcount' ] ) ?>><?php echo $i ?>
			</option>
			<?php endfor; ?>
		</select>
		</p>
		
		<p>
		<input type="checkbox" name="<?php echo $this->get_field_name( 'no_display_in_home' ) ?>" id="<?php echo $this->get_field_name( 'no_display_in_home' ) ?>" <?php checked( $instance[ 'no_display_in_home' ], 1 ) ?> />
		<label for="<?php echo $this->get_field_id( 'no_display_in_home' ) ?>"><?php _e( 'Do not display in homepage', 'ar2' ) ?></label>
		<br />
		<input type="checkbox" name="<?php echo $this->get_field_name( 'show_excerpts' ) ?>" id="<?php echo $this->get_field_name( 'show_excerpts' ) ?>" <?php checked( $instance['show_excerpts'], 1 ) ?> />
		<label for="<?php echo $this->get_field_id( 'show_excerpts' ) ?>"><?php _e( 'Show post excerpts', 'ar2' ) ?></label>
		<br />
		<input type="checkbox" name="<?php echo $this->get_field_name( 'show_thumbs' ) ?>" id="<?php echo $this->get_field_name( 'show_thumbs' ) ?>" <?php checked( $instance['show_thumbs'], 1 ) ?> />
		<label for="<?php echo $this->get_field_id( 'show_thumbs' ) ?>"><?php _e( 'Show thumbnails', 'ar2' ) ?></label>
		</p>
		<?php
		
	}
	
}

/**
 * AR2_Facebook_Activity_Widget class.
 * @since 2.0
 * @extends WP_Widget
 */
class AR2_Facebook_Activity_Widget extends WP_Widget {
	
	/**
	 * Constructor.
	 * @since 2.0
	 */
	public function __construct() {
	
		$widget_args = array (
			'classname'		=> 'ar2_fb_activity_widget',
			'description'	=> __( 'The Activity Feed plugin displays the most interesting recent activity from Facebook taking place on your site.', 'ar2' ),
		);
		
		$this->WP_Widget( 'ar2_fb_activity_widget', sprintf( __( '%s - Facebook Activity Feed', 'ar2' ), wp_get_theme()->get( 'Name' ) ), $widget_args );
		
		if ( is_active_widget( false, false, $this->id_base ) && !has_action( 'ar2_body', 'ar2_load_facebook_sdk' ) )
			add_action( 'ar2_body', 'ar2_load_facebook_sdk' );
	}
	
	public function widget( $args, $instance ) {
	
		extract( $args, EXTR_SKIP );
		
		$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		
		echo $before_widget;
		echo $before_title . $title . $after_title;
		
		?>
		<div class="fb-activity" data-width="280" data-height="300" data-header="false" data-recommendations="<?php echo $instance[ 'recommendations' ] ? 'true' : 'false' ?>" data-border-color="#FFF"></div>
		<?php
		
		echo $after_widget;
	}
	
	public function update( $new_instance, $old_instance ) {
	
		$instance = $old_instance;
		
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'recommendations' ] = ( boolean )( $new_instance[ 'recommendations' ] );
		
		return $instance;
		
	}
	
	public function form( $instance ) {
	
		$instance = wp_parse_args( ( array )$instance, array (
			'title' => __( 'Facebook Activity', 'ar2' ),
			'recommendations' => false,
		) );
		
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ) ?>"><?php _e( 'Title:', 'ar2' ) ?></label><br />
		<input type="text" id="<?php echo $this->get_field_id( 'title' ) ?>" name="<?php echo $this->get_field_name( 'title' ) ?>" size="33" value="<?php echo strip_tags( $instance[ 'title' ] ) ?>" />
		</p>
		
		<p>
		<input type="checkbox" name="<?php echo $this->get_field_name( 'recommendations' ) ?>" id="<?php echo $this->get_field_name( 'recommendations' ) ?>" <?php checked( $instance[ 'recommendations' ], 1 ) ?> />
		<label for="<?php echo $this->get_field_id( 'recommendations' ) ?>"><?php _e( 'Show Recommendations', 'ar2' ) ?></label>
		</p>
		
		<?php
		
	}
	
}

/**
 * AR2_Facebook_Like_Widget class.
 * @since 2.0
 * @extends WP_Widget
 */
class AR2_Facebook_Like_Widget extends WP_Widget {
	
	/**
	 * Constructor.
	 * @since 2.0
	 */
	public function __construct() {
	
		$widget_args = array (
			'classname'		=> 'ar2_fb_like_widget',
			'description'	=> __( 'Widget that enables Facebook Page owners to attract and gain Likes from their own website.', 'ar2' ),
		);
		
		$this->WP_Widget( 'ar2_fb_like_widget', sprintf( __( '%s - Facebook Like Box', 'ar2' ), wp_get_theme()->get( 'Name' ) ), $widget_args );
		
		if ( is_active_widget( false, false, $this->id_base ) && !has_action( 'ar2_body', 'ar2_load_facebook_sdk' ) )
			add_action( 'ar2_body', 'ar2_load_facebook_sdk' );
	}
	
	public function widget( $args, $instance ) {
		
		$fb_id = ar2_get_theme_option( 'social_facebook' );
		if ( $fb_id == '' ) return false;
		
		extract( $args, EXTR_SKIP );
		
		$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		
		echo $before_widget;
		echo $before_title . $title . $after_title;
		
		?>
		<div class="fb-like-box" data-href="http://www.facebook.com/<?php echo $fb_id ?>" data-width="280" data-show-faces="<?php echo $instance[ 'faces' ] ? 'true' : 'false' ?>" data-border-color="#FFF" data-stream="<?php echo $instance[ 'stream' ] ? 'true' : 'false' ?>" data-header="false"></div>
		<?php

		echo $after_widget;
		
	}
	
	public function update( $new_instance, $old_instance ) {
	
		$instance = $old_instance;
		
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'faces' ] = ( boolean )( $new_instance[ 'faces' ] );
		$instance[ 'stream' ] = ( boolean )( $new_instance[ 'stream' ] );
		
		return $instance;
		
	}
	
	public function form( $instance ) {
	
		$instance = wp_parse_args( ( array )$instance, array (
			'title' => __( 'Like our Facebook Page!', 'ar2' ), 
			'faces'	=> true,
			'stream' => false,
		) );
		
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ) ?>"><?php _e( 'Title:', 'ar2' ) ?></label><br />
		<input type="text" id="<?php echo $this->get_field_id( 'title' ) ?>" name="<?php echo $this->get_field_name( 'title' ) ?>" size="33" value="<?php echo strip_tags( $instance[ 'title' ] ) ?>" />
		</p>
		
		<p>
		<input type="checkbox" name="<?php echo $this->get_field_name( 'faces' ) ?>" id="<?php echo $this->get_field_name( 'faces' ) ?>" <?php checked( $instance[ 'faces' ], 1 ) ?> />
		<label for="<?php echo $this->get_field_id( 'faces' ) ?>"><?php _e( 'Show Faces', 'ar2' ) ?></label>
		<br />
		<input type="checkbox" name="<?php echo $this->get_field_name( 'stream' ) ?>" id="<?php echo $this->get_field_name( 'stream' ) ?>" <?php checked( $instance[ 'stream' ], 1 ) ?> />
		<label for="<?php echo $this->get_field_id( 'stream' ) ?>"><?php _e( 'Show Stream', 'ar2' ) ?></label>
		</p>
		
		<p><?php _e( 'Your Facebook Page username must be specified via the theme options page for this widget to work.', 'ar2' ) ?></p>
		
		<?php
		
	}
	
}

/**
 * AR2_GPlus_Badge_Widget class.
 * @since 2.0
 * @extends WP_Widget
 */
class AR2_GPlus_Badge_Widget extends WP_Widget {
	
	/**
	 * Constructor.
	 * @since 2.0
	 */
	public function __construct() {
	
		$widget_args = array (
			'classname'		=> 'ar2_gplus_badge_widget',
			'description'	=> __( 'Widget that shows a Google+ Badge for your Google+ page.', 'ar2' ),
		);
		
		$this->WP_Widget( 'ar2_gplus_badge_widget', sprintf( __( '%s - Google+ Badge', 'ar2' ), wp_get_theme()->get( 'Name' ) ), $widget_args );
		
		if ( is_active_widget( false, false, $this->id_base ) && !has_action( 'wp_footer', 'ar2_load_gplus_sdk' ) )
			add_action( 'wp_footer', 'ar2_load_gplus_sdk' );
	}
	
	public function widget( $args, $instance ) {
		
		$gplus_id = ar2_get_theme_option( 'social_gplus' );
		if ( $gplus_id == '' ) return false;
		
		extract( $args, EXTR_SKIP );
		
		$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		
		echo $before_widget;
		echo $before_title . $title . $after_title;
			
		?> <div class="g-plus" data-width="272" data-href="https://plus.google.com/<?php echo $gplus_id ?>?rel=publisher"></div><?php

		echo $after_widget;
		
	}
	
	public function update( $new_instance, $old_instance ) {
	
		$instance = $old_instance;
		
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		
		return $instance;
		
	}
	
	public function form( $instance ) {
	
		$instance = wp_parse_args( ( array )$instance, array (
			'title' => __( 'Like our Google+ Page!', 'ar2' ), 
		) );
		
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ) ?>"><?php _e( 'Title:', 'ar2' ) ?></label><br />
		<input type="text" id="<?php echo $this->get_field_id( 'title' ) ?>" name="<?php echo $this->get_field_name( 'title' ) ?>" size="33" value="<?php echo strip_tags( $instance[ 'title' ] ) ?>" />
		</p>
		
		<p><?php _e( 'Your Google+ Page ID must be specified via the theme options page for this widget to work.', 'ar2' ) ?></p>
		
		<?php
		
	}
	
}

/**
 * AR2_Video_Widget class.
 * @since 2.0
 * @extends WP_Widget
 */
class AR2_Video_Widget extends WP_Widget {
	
	/**
	 * Constructor.
	 * @since 2.0
	 */
	public function __construct() {
	
		$widget_args = array (
			'classname'		=> 'ar2_video_widget',
			'description'	=> __( 'Widget that embeds a video from any supported video sites.', 'ar2' ),
		);
		$control_args = array ( 'width' => 300, );
		
		$this->WP_Widget( 'ar2_video_widget', sprintf( __( '%s - Video', 'ar2' ), wp_get_theme()->get( 'Name' ) ), $widget_args, $control_args );
	}
	
	public function widget( $args, $instance ) {
		
		global $wp_embed;
		
		extract( $args, EXTR_SKIP );
		
		if ( $instance[ 'video' ] == '' ) return false;
		
		$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		
		echo $before_widget;
		echo $before_title . $title . $after_title;
			
		echo $wp_embed->run_shortcode( '[embed width="272"]' . $instance[ 'video' ] . '[/embed]' );

		echo $after_widget;
		
	}
	
	public function update( $new_instance, $old_instance ) {
	
		$instance = $old_instance;
		
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'video' ] = esc_url( $new_instance[ 'video' ] );
		
		return $instance;
		
	}
	
	public function form( $instance ) {
	
		$instance = wp_parse_args( ( array )$instance, array (
			'title' => __( 'Video', 'ar2' ), 
			'video'	=> '',
		) );
		
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ) ?>"><?php _e( 'Title:', 'ar2' ) ?></label><br />
		<input type="text" id="<?php echo $this->get_field_id( 'title' ) ?>" name="<?php echo $this->get_field_name( 'title' ) ?>" size="33" value="<?php echo strip_tags( $instance[ 'title' ] ) ?>" />
		</p>
		
		<p><label for="<?php echo $this->get_field_id( 'video' ) ?>"><?php _e( 'Video URL:', 'ar2' ) ?></label><br />
		<input type="text" id="<?php echo $this->get_field_id( 'video' ) ?>" name="<?php echo $this->get_field_name( 'video' ) ?>" size="43" value="<?php echo esc_url( $instance[ 'video' ] ) ?>" />
		</p>
		
		<p><a href="http://codex.wordpress.org/Embeds"><?php _e( 'Supported Video Sites', 'ar2' ) ?></a></p>
		
		<?php
		
	}
	
}

/**
 * AR2_Twitter_Feed_Widget class.
 * Code based on: http://www.problogdesign.com/wordpress/add-a-backup-to-embedded-tweets-in-wordpress/.
 *
 * @since 2.0
 * @extends WP_Widget
 */
class AR2_Twitter_Feed_Widget extends WP_Widget {

	protected $transient_name;
	protected $backup_name;
	protected $cache_time;
	
	/**
	 * Constructor.
	 * @since 2.0
	 */
	public function __construct() {
	
		$this->transient_name = 'ar2-list-tweets';
		$this->backup_name = $this->transient_name . '-backup';
		$this->cache_time = 5;
	
		$widget_args = array (
			'classname'		=> 'ar2_twitter_feed_widget',
			'description'	=> __( 'Widget that shows a selected number of tweets.', 'ar2' ),
		);
		
		$this->WP_Widget( 'ar2_twitter_feed_widget', sprintf( __( '%s - Twitter Feed', 'ar2' ), wp_get_theme()->get( 'Name' ) ), $widget_args );

	}
	
	public function widget( $args, $instance ) {
		
		if ( ar2_get_theme_option( 'social_twitter' ) == '' ) return false;
			
		extract( $args, EXTR_SKIP );

		$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		
		echo $before_widget;
		echo $before_title . $title . '<a href="http://www.twitter.com/' . ar2_get_theme_option( 'social_twitter' ) . '">' . sprintf( __( ' (%s)', 'ar2' ), '@' . ar2_get_theme_option( 'social_twitter' ) ) . '</a>' . $after_title;
		
		$tweets = $this->get_tweets( ar2_get_theme_option( 'social_twitter' ), $instance[ 'number' ], $instance[ 'exclude_replies' ] );
		
		if ( is_array( $tweets ) ) : ?>
		<ul class="tweet-list">
		<?php foreach( $tweets as $t ) : ?>
		<li><?php echo $t[ 'text' ] ?><span class="tweet-time"><?php printf( __( '%s ago', 'ar2' ), human_time_diff( $t[ 'time' ], current_time( 'timestamp' ) ) ) ?></span></li>
		<?php endforeach ?>
		</ul><!-- .tweet-list -->
		<?php else : ?>
		<small><?php _e( 'There seems to be a problem communicating with Twitter at the moment. Please try again later.', 'ar2' ) ?></small>
		<?php
		endif;
		
		echo $after_widget;
		
	}
	
	public function get_tweets( $name, $count = 5, $exclude_replies = false ) {
	
		if ( false === $tweets = get_transient( $this->transient_name ) ) {
		
			$tweets = array();
			
			$response = wp_remote_get( 'http://api.twitter.com/1/statuses/user_timeline.json?screen_name=' . $name . '&count=' . $count . '&exclude_replies=' . $exclude_replies );
			
			if ( !is_wp_error( $response ) && $response[ 'response' ][ 'code' ] == 200 ) {
			
				$tweets_json = json_decode( $response[ 'body' ], true );
				
				foreach ( $tweets_json as $tweet ) {
					$name = $tweet[ 'user' ][ 'name' ];
					$permalink = 'http://twitter.com/#!/'. $name .'/status/'. $tweet[ 'id_str' ];
					
					// Message. Convert links to real links.
					$pattern = '/http:(\S)+/';
					$replace = '<a href="${0}" target="_blank" rel="nofollow">${0}</a>';
					$text = preg_replace( $pattern, $replace, $tweet[ 'text' ] );
					
					$time = $tweet[ 'created_at' ];
					$time = date_parse( $time );
					$utime = mktime( $time[ 'hour' ], $time[ 'minute' ], $time[ 'second' ], $time[ 'month' ], $time[ 'day' ], $time[ 'year' ] );
					  
					$tweets[] = array (
						'text'		=> $text,
						'name'		=> $name,
						'permalink'	=> $permalink,
						'time'		=> $utime,
					);
				 
				}
				
				set_transient( $this->transient_name, $tweets, 60 * $this->cache_time );
				update_option( $this->backup_name, $tweets );
				
			} else {
			
				$tweets = get_option( $this->backup_name );
				
			}
			
		}
	
		return $tweets;
		
	}
	
	public function update( $new_instance, $old_instance ) {
	
		$instance = $old_instance;
		
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'number' ] = intval( $new_instance[ 'number' ] );
		$instance[ 'exclude_replies' ] = ( boolean )( $new_instance[ 'exclude_replies' ] );
		
		delete_transient( $this->transient_name );
		
		return $instance;
		
	}
	
	public function form( $instance ) {
	
		$instance = wp_parse_args( ( array )$instance, array (
			'title' => __( 'Twitter Feed', 'ar2' ),
			'number' => 5,
			'exclude_replies' => false,
		) );
		
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ) ?>"><?php _e( 'Title:', 'ar2' ) ?></label><br />
		<input type="text" id="<?php echo $this->get_field_id( 'title' ) ?>" name="<?php echo $this->get_field_name( 'title' ) ?>" size="33" value="<?php echo strip_tags( $instance[ 'title' ] ) ?>" />
		</p>
		
		<p><label for="<?php echo $this->get_field_id( 'number' ) ?>"><?php _e( 'Number of Tweets:', 'ar2' ) ?></label>
		<select id="<?php echo $this->get_field_id( 'number' ) ?>" name="<?php echo $this->get_field_name( 'number' ) ?>">
			<?php for ( $i = 1; $i <= 12; $i++ ) : ?>
			<option value="<?php echo $i ?>"<?php selected( $i, $instance[ 'number' ] ) ?>><?php echo $i ?>
			</option>
			<?php endfor; ?>
		</select>
		</p>
		
		<p>
		<input type="checkbox" name="<?php echo $this->get_field_name( 'exclude_replies' ) ?>" id="<?php echo $this->get_field_name( 'exclude_replies' ) ?>" <?php checked( $instance[ 'exclude_replies' ], 1 ) ?> />
		<label for="<?php echo $this->get_field_id( 'exclude_replies' ) ?>"><?php _e( 'Exclude Replies', 'ar2' ) ?></label>
		</p>
		
		<p><?php _e( 'Your Twitter username must be specified via the theme options page for this widget to work.', 'ar2' ) ?></p>
		<p><?php _e( 'Any changes to the settings will take up to 5 minutes for it to take effect.', 'ar2' ) ?></p>
		
		<?php
		
	}
	
}

/**
 * AR2_Social_Buttons_Widget class.
 * @since 2.0
 * @extends WP_Widget
 */
class AR2_Social_Buttons_Widget extends WP_Widget {
	
	/**
	 * Constructor.
	 * @since 2.0
	 */
	public function __construct() {
	
		$widget_args = array (
			'classname'		=> 'ar2_social_buttons_widget',
			'description'	=> __( 'Widget that shows all social media icons specified in the theme options.', 'ar2' ),
		);
		
		$this->WP_Widget( 'ar2_social_buttons_widget', sprintf( __( '%s - Social Buttons', 'ar2' ), wp_get_theme()->get( 'Name' ) ), $widget_args );
	}
	
	public function widget( $args, $instance ) {
		
		extract( $args, EXTR_SKIP );
		
		$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		
		echo $before_widget;
		echo $before_title . $title . $after_title;
		?>
		<div class="social-nav clearfix">
		
		<a class="rss" href="<?php get_feed_link( 'rss2' ) ?>"><?php _e( 'RSS Feed', 'ar2' ) ?></a>
		
		<?php if ( '' != $twitter = ar2_get_theme_option( 'social_twitter' ) ) : ?>
		<a class="twitter" href="http://www.twitter.com/<?php echo $twitter ?>"><?php _e( 'Twitter', 'ar2' ) ?></a>
		<?php endif ?> 
		
		<?php if ( '' != $facebook = ar2_get_theme_option( 'social_facebook' ) ) : ?>
		<a class="facebook" href="http://www.facebook.com/<?php echo $facebook ?>"><?php _e( 'Facebook', 'ar2' ) ?></a>
		<?php endif ?> 
		
		<?php if ( '' != $gplus = ar2_get_theme_option( 'social_gplus' ) ) : ?>
		<a class="gplus" href="http://plus.google.com/<?php echo $gplus ?>/posts"><?php _e( 'Google+', 'ar2' ) ?></a>
		<?php endif ?>
		
		<?php if ( '' != $flickr = ar2_get_theme_option( 'social_flickr' ) ) : ?>
		<a class="flickr" href="http://www.flickr.com/photos/<?php echo $flickr ?>"><?php _e( 'Flickr', 'ar2' ) ?></a>
		<?php endif ?>
		
		<?php if ( '' != $youtube = ar2_get_theme_option( 'social_youtube' ) ) : ?>
		<a class="youtube" href="http://www.youtube.com/user/<?php echo $youtube ?>"><?php _e( 'YouTube', 'ar2' ) ?></a>
		<?php endif ?>
		
		</div>
		<?php

		echo $after_widget;
		
	}
	
	public function update( $new_instance, $old_instance ) {
	
		$instance = $old_instance;
		
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		
		return $instance;
		
	}
	
	public function form( $instance ) {
	
		$instance = wp_parse_args( ( array )$instance, array (
			'title' => __( 'Social Buttons', 'ar2' ), 
		) );
		
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ) ?>"><?php _e( 'Title:', 'ar2' ) ?></label><br />
		<input type="text" id="<?php echo $this->get_field_id( 'title' ) ?>" name="<?php echo $this->get_field_name( 'title' ) ?>" size="33" value="<?php echo strip_tags( $instance[ 'title' ] ) ?>" />
		</p>

		<p><?php _e( 'Social network IDs must be specified for their respective buttons to appear.', 'ar2' ) ?></p>
		
		<?php
		
	}
	
}

/**
 * Shared function to display certain posts in the widget.
 * @since 1.3
 */
function ar2_widgets_post_loop( $id, $args = array() ) {

	global $wp_query;
	
	$_defaults = array (
		'taxonomy'			=> 'category',
		'show_thumbs'		=> true,
		'show_excerpt'		=> true,
		'query'				=> array (
			'post_type'				=> 'post',
			'posts_per_page'		=> 5,
			'orderby'				=> 'date',
			'order'					=> 'DESC',
			'ignore_sticky_posts' 	=> 1,
		)
	);
	
	$args[ 'query' ] = wp_parse_args( $args[ 'query' ], $_defaults[ 'query' ] );
	$args = wp_parse_args( $args, $_defaults );
	
	$q = new WP_Query( $args[ 'query' ] );
	
	if ( $q->have_posts() ) {
		echo '<ul class="' . $id . '">';
		while( $q->have_posts() ) {
			
			$q->the_post();
			
			// hack for plugin authors who love to use $post = $wp_query->post
			$wp_query->post = $q->post;
			
			setup_postdata( $q->post );
			
			?><li <?php post_class() ?>> 
			
			<?php if ( $args[ 'show_thumbs' ] ) : ?>
			<a class="entry-thumbnail" href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php echo ar2_get_thumbnail( 'sidebar-thumb', get_the_ID() ) ?></a>
			<?php endif ?>
			
			<a class="entry-title" rel="bookmark" href="<?php the_permalink() ?>"><?php the_title() ?></a><br />
			<small><?php printf( __('Posted %s', 'ar2'), ar2_posted_on( false ) ) ?></small>
			
			<?php if ( $args[ 'show_excerpt' ] ) : ?>
			<p class="entry-content">
			<?php echo get_the_excerpt() ?>
			</p>
			<?php endif ?>
			
			</li>
			<?php
		}
		echo '</ul>';
	} else {
		echo '<small>' . __('No posts at the moment. Check back again later!', 'ar2') . '</small>';
	}
	
	wp_reset_query();
	
}

// Register Widgets
function ar2_widgets_init() {

	register_widget( 'AR2_Tabbed_Sidebar' );
	register_widget( 'AR2_Featured_Stories' );
	
	// Added in 2.0
	register_widget( 'AR2_Facebook_Activity_Widget' );
	register_widget( 'AR2_Facebook_Like_Widget' );
	register_widget( 'AR2_GPlus_Badge_Widget' );
	register_widget( 'AR2_Twitter_Feed_Widget' );
	register_widget( 'AR2_Video_Widget' );
	register_widget( 'AR2_Social_Buttons_Widget' );
	
}

add_action('widgets_init', 'ar2_widgets_init', 1);	
/* End of file widgets.php */
/* Location: ./library/widgets.php */
?>
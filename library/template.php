<?php
/**
 * Document title. 
 * 2.0 revision taken from Twenty Eleven theme.
 * @since 1.0
 */
function ar2_document_title() {
	
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'ar2' ), max( $paged, $page ) );
	
}


/**
 * ar2_add_header_js function.
 * @since 1.6
 */
function ar2_add_header_js() {
	?>
	<script type="text/javascript">
	/* <![CDATA[ */
	<?php @include get_template_directory() . '/js/header.js.php'; ?>
	/* ]]> */
	</script>
	<?php
}

/**
 * @todo
 * Function adapted from http://graveyard.maniacalrage.net/etc/relative/.
 * @since 1.6
 */
function ar2_posted_on( $echo = 1 ) {

	$result = '';
	
	if ( !ar2_get_theme_option( 'relative_dates' ) ) {
		$result = sprintf( __( '%s', 'ar2' ), get_the_time( get_option( 'date_format' ) ) );
	} else {
		$diff = current_time( 'timestamp' ) - get_the_time( 'U' );

		$months = floor( $diff / 2592000 );
		$diff -= $months * 2419200;
		
		$weeks = floor( $diff / 604800 );
		$diff -= $weeks * 604800;
		
		$days = floor( $diff / 86400 );
		$diff -= $days * 86400;
		
		$hours = floor( $diff / 3600 );
		$diff -= $hours * 3600;
		
		$minutes = floor( $diff / 60 );
		$diff -= $minutes * 60;
		
		if ( $months > 0 || $months < 0 ) {
			// over a month old, just show date
			$result = sprintf( __( '%s', 'ar2' ), get_the_time( get_option( 'date_format' ) ) );
		} else {
			if ( $weeks > 0 ) {
				// weeks
				if ( $weeks > 1 ) 
					$result = sprintf( __( '%s weeks ago', 'ar2' ), number_format_i18n( $weeks ) );
				else
					$result = __( '1 week ago', 'ar2' );
			} elseif ( $days > 0 ) {
				// days
				if ( $days > 1 ) 
					$result = sprintf( __( '%s days ago', 'ar2' ), number_format_i18n( $days ) );
				else
					$result = __( '1 day ago', 'ar2' );
			} elseif ( $hours > 0 ) {
				// hours
				if ( $hours > 1 ) 
					$result = sprintf( __( '%s hours ago', 'ar2' ), number_format_i18n( $hours ) );
				else
					$result = __( '1 hour ago', 'ar2' );
			} elseif ( $minutes > 0 ) {
				// minutes
				if ( $minutes > 1 ) 
					$result = sprintf( __( '%s minutes ago', 'ar2' ), number_format_i18n( $minutes ) );
				else
					$result = __( '1 minute ago', 'ar2' );
			} else {
				// seconds
				$result = __( 'less than a minute ago', 'ar2' );
			}
		}
		
	}
	
	if ( $echo ) echo $result;
	return $result;
	
}

function ar2_list_trackbacks( $comment, $args, $depth ) {

	$GLOBALS['comment'] = $comment;
?>
	<li <?php comment_class(); ?> id="li-trackback-<?php comment_ID() ?>">
		<div id="trackback-<?php comment_ID(); ?>">
		<?php echo get_comment_author_link() ?>
		</div>
<?php

}

function ar2_list_comments( $comment, $args, $depth ) {

	$GLOBALS[ 'comment' ] = $comment;
?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div class="comment-node" id="comment-<?php comment_ID(); ?>">
			
			<div class="comment-author vcard">
			<?php echo get_avatar($comment, $size = 32) ?>
			<cite class="fn"><?php echo get_comment_author_link() ?></cite>
			</div>
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<span class="comment-moderation"><?php _e('Your comment is awaiting moderation.', 'ar2') ?></span>	
			<?php endif; ?>
			<div class="comment-meta commentmetadata">
				<?php printf( __('Posted %1$s at %2$s', 'ar2'), '<abbr class="comment-datetime" title="' . get_comment_time( __('c', 'ar2') ) . '">' . get_comment_time( __('F j, Y', 'ar2') ), get_comment_time( __('g:i A', 'ar2') ) . '</abbr>' ); ?>
			</div>
			<div class="comment-content"><?php comment_text() ?></div>
			<div class="comment-controls">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args[ 'max_depth' ] ) ) ) ?>
			</div>
		</div>
<?php	

}

function ar2_nav_fallback_cb() {
	echo '<ul class="menu clearfix">';
	wp_list_categories( 'hierarchical=1&orderby=id&hide_empty=1&title_li=' );	
	echo '</ul>';
}

function ar2_footer_nav_fallback_cb() {
	echo '<ul class="menu clearfix">';
	wp_list_categories( 'hierarchical=1&orderby=id&hide_empty=1&title_li=' );	
	echo '</ul>';
}

function ar2_load_social_js() {

	if ( ar2_get_theme_option( 'post_display[post_social]' ) )
		wp_enqueue_script( 'addthis_js', 'http://s7.addthis.com/js/250/addthis_widget.js' );
	
}
add_action( 'wp_head', 'ar2_load_social_js' );

function ar2_post_navigation() {

	if ( function_exists( 'wp_pagenavi' ) ) : wp_pagenavi(); else : ?>
    	<div class="navigation clearfix">
    		<div class="prev"><?php previous_posts_link() ?></div>
			<div class="next"><?php next_posts_link() ?></div>
		</div>
	<?php endif;

}

function ar2_load_facebook_sdk() {

	?>
	<div id="fb-root"></div>
	<script type="text/javascript">
	/* <![CDATA[ */
	( function( d, s, id ) {
	  var js, fjs = d.getElementsByTagName( s )[ 0 ];
	  if ( d.getElementById( id ) ) return;
	  js = d.createElement( s ); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	  fjs.parentNode.insertBefore( js, fjs );
	}( document, 'script', 'facebook-jssdk' ) );
	/* ]]> */
	</script>
	<?php
	
}

function ar2_load_gplus_sdk() {

	?>
	<script type="text/javascript">
	/* <![CDATA[ */
	( function() {
	var po = document.createElement( 'script' ); po.type = 'text/javascript'; po.async = true;
	po.src = 'https://apis.google.com/js/plusone.js';
	var s = document.getElementsByTagName( 'script' )[ 0 ]; s.parentNode.insertBefore( po, s );
	} )();
	/* ]]> */
	</script>
	<?php

}

/* End of file template.php */
/* Location: ./library/template.php */

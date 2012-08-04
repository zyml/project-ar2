<?php
/**
 * AR2's comments template.
 *
 * @package AR2
 * @since 1.0
 */
?>

<?php if ( post_password_required() ) : ?>
	<div id="comments" class="comments">
		<h3 class="module-title"><?php _e('Password Required', 'ar2') ?></h3>
		<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'ar2' ) ?></p>
	</div>
	<?php return; ?>
<?php endif ?>

<div id="comments" class="comments">
<?php
$comments_by_type = &separate_comments( $comments );   
if ( have_comments() ) : 
?>

	<?php if ( !empty( $comments_by_type[ 'comment' ] ) ) : ?>  
	
	<h3 class="module-title"><?php comments_number( __( 'No Comments', 'ar2' ), __( '1 Comment', 'ar2' ), _n( '% Comment', '% Comments', get_comments_number(), 'ar2' ) ); ?></h3>
	
	<ol id="commentlist" class="clearfix"><?php wp_list_comments( 'type=comment&callback=ar2_list_comments' ) ?></ol>
	
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<div class="navigation clearfix">
			<?php paginate_comments_links() ?>
		</div>
		<?php endif ?>
	
	<?php endif ?>

	<?php if ( !empty( $comments_by_type[ 'pings' ] ) ) : ?>
	<h3 class="module-title"><?php _e( 'Trackbacks / Pings', 'ar2' ) ?></h3>
	<ol id="pingbacks" class="clearfix"><?php wp_list_comments( 'type=pings&callback=ar2_list_trackbacks' ) ?></ol>
	<?php endif; ?>
	
<?php elseif ( !comments_open()  && post_type_supports( get_post_type(), 'comments' ) ) : ?>
	<h3><?php _e( 'Comments Closed', 'ar2' ) ?></h3>
	<p class="nocomments"><?php _e( 'Comments are closed. You will not be able to post a comment in this post.', 'ar2' ) ?></p>
<?php endif ?>

<?php comment_form( null, get_the_ID() ) ?>

</div><!-- #comments -->
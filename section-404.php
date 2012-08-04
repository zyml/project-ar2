<?php
/**
 * The template for displaying content when posts are not found.
 * @since 1.6
 */
?>

<article id="post-0" class="article no-results not-found">
	<header class="entry-header">
		<h1 class="entry-title"><?php _e('Error 404 - Not Found', 'ar2') ?></h1>
	</header>
	<div class="entry-content clearfix">
		<p><strong><?php _e( "We're very sorry, but that page doesn't exist or has been moved.", 'ar2' ) ?></strong></p>
		<p><?php _e( "If you still can't find what you're looking for, try using the search form below.", 'ar2') ?></p>
		<?php get_search_form(); ?>
	</div>
</article>
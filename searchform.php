<?php
/**
 * AR2's search form template.
 *
 * @package AR2
 * @since 1.0
 */
?>

<form method="get" id="searchform" class="clearfix" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="text" class="field" name="s" id="s" placeholder="<?php esc_attr_e( 'Search the site...', 'ar2' ); ?>" <?php if ( '' != get_search_query() ) : ?>value="<?php echo get_search_query() ?>"<?php endif ?> />
	<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'ar2' ); ?>" />
</form>
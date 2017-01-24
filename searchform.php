<?php
/**
 * The template for displaying search forms in Squire
 *
 * @package Squire
 */
?>
<form role="search" method="get" class="search-form input-group" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php _ex( 'Search for:', 'label', 'squire' ); ?></span>
		<input type="search" class="search-field input-group-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'squire' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	</label>
	<div class="input-group-button">
		<button type="submit" class="search-submit button"><?php echo esc_attr_x( 'Search', 'submit button', 'squire' ); ?></button>
	</div>
</form>

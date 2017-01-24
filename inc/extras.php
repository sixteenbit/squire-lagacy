<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features.
 */

/**
 * Add Color Block Shortcode
 *
 * @param $atts
 *
 * @return string
 */
function squire_color_block_shortcode( $atts ) {
	$a = shortcode_atts( array(
		'color' => ''
	), $atts );

	return "<div class=\"color-block\" data-mh><span style=\"background:#{$a['color']}\"></span>#{$a['color']}</div>";
}

add_shortcode( 'color-block', 'squire_color_block_shortcode' );

/**
 * Add Button Shortcode
 *
 * @param $atts
 *
 * @return string
 */
function squire_button_shortcode( $atts, $content = null ) {
	$a = shortcode_atts( array(
		'style' => '',
		'url' => '#'
	), $atts );

	return "<a class=\"{$a['style']} button\" href=\"{$a['url']}\">{$content}</a>";
}

add_shortcode( 'button', 'squire_button_shortcode' );

/**
 * Renames sticky class.
 *
 * @param $classes
 *
 * @return array
 */
function squire_change_sticky_class( $classes ) {
	if ( in_array( 'sticky', $classes, TRUE ) ) {
		$classes   = array_diff( $classes, array( "sticky" ) );
		$classes[] = 'sticky-post';
	}

	return $classes;
}

add_filter( 'post_class', 'squire_change_sticky_class' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function squire_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}

add_filter( 'body_class', 'squire_body_classes' );

/**
 * Unregister Core Widgets
 */
function squire_default_widgets() {
	unregister_widget( 'WP_Widget_Pages' );
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Archives' );
	unregister_widget( 'WP_Widget_Links' );
	unregister_widget( 'WP_Widget_Meta' );
	unregister_widget( 'WP_Widget_Recent_Comments' );
	unregister_widget( 'WP_Widget_RSS' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
}

add_action( 'widgets_init', 'squire_default_widgets', 10 );

/**
 * Grab child pages
 */
function squire_theme_get_child_pages() {

	global $post;
	$parent_id = $post->ID;
	$pages     = array();

	// WP_Query arguments
	$args = array(
		'post_type'      => 'page',
		'post_parent'    => $parent_id,
		'order'          => 'ASC',
		'orderby'        => 'menu_order',
		'no_found_rows'  => TRUE,
		'nopaging'       => TRUE,
		'posts_per_page' => - 1
	);

	// The Query
	$wp_query = new WP_Query( $args );

	// The Loop
	if ( $wp_query->have_posts() ) {

		while ( $wp_query->have_posts() ) {

			$wp_query->the_post();
			$pages[] = $post; ?>

			<section
				id="post-<?php the_ID(); ?>" <?php post_class( 'child-section' ); ?>
				data-magellan-target="post-<?php the_ID(); ?>">
				<?php the_title( '<h2 class="section-title subheader">', '</h2>' ); ?>
				<?php the_content(); ?>

				<?php
				edit_post_link(
					sprintf(
					/* translators: %s: Name of current post */
						esc_html__( 'Edit %s', 'squire' ),
						the_title( '<span class="screen-reader-text">"', '"</span>', FALSE )
					),
					'<span class="edit-link">',
					'</span>'
				);
				?>
			</section><!-- #post-## -->

		<?php }

	}

	// Restore original Post Data
	wp_reset_postdata();
}

/**
 * Generate child page navigation
 */
function squire_theme_mobile_sub_nav() {

	$page_id  = get_queried_object_id();
	$children = get_pages( 'child_of=' . $page_id );

	if ( count( $children ) != 0 && ! is_404() && ! is_home() ) : ?>
		<h2 class="widget-title local-nav-title"><?php echo esc_html__( 'On this page:', 'squire' ); ?></h2>
		<nav class="sub-navigation">
			<ul class="menu vertical" data-magellan>
				<?php

				global $post;

				wp_reset_query();

				$args      = array(
					'post_parent'    => $page_id,
					'post_type'      => 'page',
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
					'nopaging'       => TRUE,
					'posts_per_page' => - 1
				);
				$sub_pages = new WP_Query( $args );
				if ( $sub_pages->have_posts() ) : while ( $sub_pages->have_posts() ) : $sub_pages->the_post();
					echo '<li><a href="#post-' . $post->ID . '">' . get_the_title() . '</a></li>';
				endwhile;
				endif;
				wp_reset_query();
				?>
			</ul>
		</nav>

	<?php endif;
}

/**
 * Redirect child pages to parent + hash
 */
function squire_redirect_child_pages() {
	global $post;
	if ( is_page() && $post->post_parent ) {
		wp_redirect( get_permalink( $post->post_parent ) . '#post-' . $post->ID );
	}
}

add_action( 'template_redirect', 'squire_redirect_child_pages' );

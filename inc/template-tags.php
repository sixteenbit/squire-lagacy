<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 */

if ( ! function_exists( 'squire_footer_text' ) ) :
	/**
	 * Displays custom footer text from customizer.
	 */
	function squire_footer_text() {
		$footer_text = get_theme_mod( 'footer_text' );

		if ( !empty( $footer_text ) ) {
			echo esc_html( $footer_text );
		} else {
			echo sprintf( __( '&copy; %s. All rights reserved.', 'squire' ), date( 'Y' ) . ' ' . esc_html( get_bloginfo( 'name' ) ) );
		}

	}
endif;

if ( ! function_exists( 'squire_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 *
	 * Create your own squire_post_thumbnail() function to override in a child theme.
	 */
	function squire_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>"
			   aria-hidden="true">
				<?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
			</a>

		<?php endif; // End is_singular()
	}
endif;

if ( ! function_exists( 'squire_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function squire_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			esc_html_x( 'Posted on %s', 'post date', 'squire' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		$byline = sprintf(
			esc_html_x( 'by %s', 'post author', 'squire' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'squire_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function squire_entry_footer() {
		edit_post_link(
			sprintf(
			/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'squire' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<span class="edit-link">',
			'</span>'
		);

		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'squire' ) );
		if ( $categories_list && squire_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'squire' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'squire' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'squire' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( esc_html__( 'Leave a comment', 'squire' ), esc_html__( '1 Comment', 'squire' ),
				esc_html__( '% Comments', 'squire' ) );
			echo '</span>';
		}
	}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function squire_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'squire_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'squire_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so squire_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so squire_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in squire_categorized_blog.
 */
function squire_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'squire_categories' );
}

add_action( 'edit_category', 'squire_category_transient_flusher' );
add_action( 'save_post', 'squire_category_transient_flusher' );

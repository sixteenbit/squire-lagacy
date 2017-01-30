<?php

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
		'url'   => '#'
	), $atts );

	return "<a class=\"{$a['style']} button\" href=\"{$a['url']}\">{$content}</a>";
}

add_shortcode( 'button', 'squire_button_shortcode' );

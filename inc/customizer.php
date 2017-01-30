<?php
/**
 * Squire Theme Customizer
 */


/**
 * Theme Options Customizer Implementation.
 *
 * Implement the Theme Customizer for Theme Settings.
 *
 * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
 *
 * @param WP_Customize_Manager $wp_customize Object that holds the customizer data.
 */
function squire_register_customizer_settings( $wp_customize ) {

	/*
	 * Failsafe is safe
	 */
	if ( ! isset( $wp_customize ) ) {
		return;
	}

	/**
	 * Footer Text setting.
	 *
	 * - Setting: Footer Text
	 * - Control: text
	 * - Sanitization: html
	 *
	 * Uses a text field to configure the user's text displayed in the site footer.
	 *
	 * @uses $wp_customize->add_setting() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_setting/
	 * @link $wp_customize->add_setting() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
	 */
	$wp_customize->add_setting(
	// $id
		'footer_text',
		// $args
		array(
			'default'           => sprintf( __( '&copy; %s. All rights reserved.', 'squire' ), date( 'Y' ) . ' ' . esc_html( get_bloginfo( 'name' ) ) ),
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'squire_sanitize_html',
			'transport'         => 'postMessage'
		)
	);
}

// Settings API options initialization and validation.
add_action( 'customize_register', 'squire_register_customizer_settings' );

/**
 * Theme Options Customizer Implementation
 *
 * Implement the Theme Customizer for Theme Settings.
 *
 * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
 *
 * @param WP_Customize_Manager $wp_customize Object that holds the customizer data.
 */
function squire_register_customizer_controls( $wp_customize ) {

	/**
	 * Failsafe is safe
	 */
	if ( ! isset( $wp_customize ) ) {
		return;
	}

	/**
	 * Footer Text control.
	 *
	 * - Control: Basic: Text
	 * - Setting: Footer Text
	 * - Sanitization: html
	 *
	 * Register the core "text" control to be used to configure the Footer Copyright Text setting.
	 *
	 * @uses $wp_customize->add_control() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_control/
	 * @link $wp_customize->add_control() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
	 */
	$wp_customize->add_control(
	// $id
		'footer_text',
		// $args
		array(
			'settings'    => 'footer_text',
			'section'     => 'title_tagline',
			'type'        => 'text',
			'label'       => __( 'Footer Text', 'squire' ),
			'description' => __( 'Copyright or other text to be displayed in the site footer. HTML allowed.', 'squire' ),
			'priority'    => '90'
		)
	);

}

// Settings API options initilization and validation.
add_action( 'customize_register', 'squire_register_customizer_controls' );

/**
 * HTML sanitization callback example.
 *
 * - Sanitization: html
 * - Control: text, textarea
 *
 * Sanitization callback for 'html' type text inputs. This callback sanitizes `$html`
 * for HTML allowable in posts.
 *
 * NOTE: wp_filter_post_kses() can be passed directly as `$wp_customize->add_setting()`
 * 'sanitize_callback'. It is wrapped in a callback here merely for example purposes.
 *
 * @see wp_filter_post_kses() https://developer.wordpress.org/reference/functions/wp_filter_post_kses/
 *
 * @param string $html HTML to sanitize.
 *
 * @return string Sanitized HTML.
 */
function squire_sanitize_html( $html ) {
	return wp_filter_post_kses( $html );
}

/**
 * Sets up the WordPress core custom header and custom background features.
 *
 * @see squire_header_style()
 */
function squire_custom_header_and_background() {
	$color_scheme             = squire_get_color_scheme();
	$default_background_color = trim( $color_scheme[0], '#' );
	$default_text_color       = trim( $color_scheme[3], '#' );

	/**
	 * Filter the arguments used when adding 'custom-background' support in Squire.
	 *
	 * @param array $args {
	 *     An array of custom-background support arguments.
	 *
	 * @type string $default -color Default color of the background.
	 * }
	 */
	add_theme_support( 'custom-background', apply_filters( 'squire_custom_background_args', array(
		'default-color' => $default_background_color,
	) ) );

	/**
	 * Filter the arguments used when adding 'custom-header' support in Squire.
	 *
	 * @param array $args {
	 *     An array of custom-header support arguments.
	 *
	 * @type string $default -text-color Default color of the header text.
	 * @type int $width Width in pixels of the custom header image. Default 1200.
	 * @type int $height Height in pixels of the custom header image. Default 280.
	 * @type bool $flex -height      Whether to allow flexible-height header images. Default true.
	 * @type callable $wp -head-callback Callback function used to style the header image and text
	 *                                      displayed on the blog.
	 * }
	 */
	add_theme_support( 'custom-header', apply_filters( 'squire_custom_header_args', array(
		'default-text-color' => $default_text_color,
		'width'              => 2000,
		'height'             => 1200,
		'flex-height'        => true,
		'video'              => true,
		'wp-head-callback'   => 'squire_header_style',
	) ) );
}

add_action( 'after_setup_theme', 'squire_custom_header_and_background' );

if ( ! function_exists( 'squire_header_style' ) ) :
	/**
	 * Styles the header text displayed on the site.
	 *
	 * Create your own squire_header_style() function to override in a child theme.
	 *
	 * @see squire_custom_header_and_background().
	 */
	function squire_header_style() {
		// If the header text option is untouched, let's bail.
		if ( display_header_text() ) {
			return;
		}

		// If the header text has been hidden.
		?>
		<style type="text/css" id="squire-header-css">
			.site-branding {
				margin: 0 auto 0 0;
			}

			.site-branding .site-title,
			.site-description {
				clip: rect(1px, 1px, 1px, 1px);
				position: absolute;
			}
		</style>
		<?php
	}
endif; // squire_header_style

/**
 * Adds postMessage support for site title and description for the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 */
function squire_customize_register( $wp_customize ) {
	$color_scheme = squire_get_color_scheme();

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	$wp_customize->get_setting( 'footer_text' )->transport     = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'            => '.site-title a',
			'container_inclusive' => false,
			'render_callback'     => 'squire_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'            => '.site-description',
			'container_inclusive' => false,
			'render_callback'     => 'squire_customize_partial_blogdescription',
		) );
		$wp_customize->selective_refresh->add_partial( 'footer_text', array(
			'selector'            => '.sq-info',
			'container_inclusive' => false,
			'render_callback'     => 'squire_customize_partial_footer_text',
		) );
	}

	// Add color scheme setting and control.
	$wp_customize->add_setting( 'color_scheme', array(
		'default'           => 'default',
		'sanitize_callback' => 'squire_sanitize_color_scheme',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'color_scheme', array(
		'label'    => __( 'Base Color Scheme', 'squire' ),
		'section'  => 'colors',
		'type'     => 'select',
		'choices'  => squire_get_color_scheme_choices(),
		'priority' => 1,
	) );

	// Remove the core header textcolor control, as it shares the main text color.
	$wp_customize->remove_control( 'header_textcolor' );

	// Add page background color setting and control.
	$wp_customize->add_setting( 'page_background_color', array(
		'default'           => $color_scheme[1],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'page_background_color', array(
		'label'   => __( 'Page Background Color', 'squire' ),
		'section' => 'colors',
	) ) );

	// Add link color setting and control.
	$wp_customize->add_setting( 'link_color', array(
		'default'           => $color_scheme[2],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
		'label'   => __( 'Link Color', 'squire' ),
		'section' => 'colors',
	) ) );

	// Add main text color setting and control.
	$wp_customize->add_setting( 'main_text_color', array(
		'default'           => $color_scheme[3],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'main_text_color', array(
		'label'   => __( 'Main Text Color', 'squire' ),
		'section' => 'colors',
	) ) );

	// Add secondary text color setting and control.
	$wp_customize->add_setting( 'secondary_text_color', array(
		'default'           => $color_scheme[4],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'secondary_text_color', array(
		'label'   => __( 'Secondary Text Color', 'squire' ),
		'section' => 'colors',
	) ) );

	// Add border color setting and control.
	$wp_customize->add_setting( 'border_color', array(
		'default'           => $color_scheme[5],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'border_color', array(
		'label'   => __( 'Border Color', 'squire' ),
		'section' => 'colors',
	) ) );
}

add_action( 'customize_register', 'squire_customize_register', 11 );

/**
 * Render the site title for the selective refresh partial.
 *
 * @see squire_customize_register()
 *
 * @return void
 */
function squire_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @see squire_customize_register()
 *
 * @return void
 */
function squire_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Registers color schemes for Squire.
 *
 * Can be filtered with {@see 'squire_color_schemes'}.
 *
 * The order of colors in a colors array:
 * 1. Main Background Color.
 * 2. Page Background Color.
 * 3. Link Color.
 * 4. Main Text Color.
 * 5. Secondary Text Color.
 * 6. Border Color.
 *
 * @return array An associative array of color scheme options.
 */
function squire_get_color_schemes() {
	/**
	 * Filter the color schemes registered for use with Squire.
	 *
	 * The default schemes include 'default', 'dark', 'gray', 'red', and 'yellow'.
	 *
	 * @param array $schemes {
	 *     Associative array of color schemes data.
	 *
	 * @type array $slug {
	 *         Associative array of information for setting up the color scheme.
	 *
	 * @type string $label Color scheme label.
	 * @type array $colors HEX codes for default colors prepended with a hash symbol ('#').
	 *                              Colors are defined in the following order: Main
	 *                              background, link, main text, secondary text, border color.
	 *     }
	 * }
	 */
	return apply_filters( 'squire_color_schemes', array(
		'default'    => array(
			'label'  => __( 'Default', 'squire' ),
			'colors' => array(
				'#fefefe', // Main Background Color.
				'#ffffff', // Page Background Color.
				'#0069ff', // Link Color.
				'#333333', // Main Text Color.
				'#f3f5f7', // Secondary Text Color.
				'#e6e6e6' // Border Color.
			),
		),
		'flatui'     => array(
			'label'  => __( 'Flat UI', 'squire' ),
			'colors' => array(
				'#ffffff',
				'#eff0f2',
				'#1abc9c',
				'#34495e',
				'#bdc3c7',
				'#ecf0f1'
			),
		),
		'foundation' => array(
			'label'  => __( 'Foundation', 'squire' ),
			'colors' => array(
				'#ffffff',
				'#ffffff',
				'#1779ba',
				'#0a0a0a',
				'#767676',
				'#e6e6e6'
			),
		),
		'luci'       => array(
			'label'  => __( 'Luci', 'squire' ),
			'colors' => array(
				'#ffffff',
				'#e7e7e7',
				'#da235b',
				'#424142',
				'#e7e7e7',
				'#e6e6e6'
			),
		),
	) );
}

if ( ! function_exists( 'squire_get_color_scheme' ) ) :
	/**
	 * Retrieves the current Squire color scheme.
	 *
	 * Create your own squire_get_color_scheme() function to override in a child theme.
	 *
	 * @return array An associative array of either the current or default color scheme HEX values.
	 */
	function squire_get_color_scheme() {
		$color_scheme_option = get_theme_mod( 'color_scheme', 'default' );
		$color_schemes       = squire_get_color_schemes();

		if ( array_key_exists( $color_scheme_option, $color_schemes ) ) {
			return $color_schemes[ $color_scheme_option ]['colors'];
		}

		return $color_schemes['default']['colors'];
	}
endif; // squire_get_color_scheme

if ( ! function_exists( 'squire_get_color_scheme_choices' ) ) :
	/**
	 * Retrieves an array of color scheme choices registered for Squire.
	 *
	 * Create your own squire_get_color_scheme_choices() function to override
	 * in a child theme.
	 *
	 * @return array Array of color schemes.
	 */
	function squire_get_color_scheme_choices() {
		$color_schemes                = squire_get_color_schemes();
		$color_scheme_control_options = array();

		foreach ( $color_schemes as $color_scheme => $value ) {
			$color_scheme_control_options[ $color_scheme ] = $value['label'];
		}

		return $color_scheme_control_options;
	}
endif; // squire_get_color_scheme_choices


if ( ! function_exists( 'squire_sanitize_color_scheme' ) ) :
	/**
	 * Handles sanitization for Squire color schemes.
	 *
	 * Create your own squire_sanitize_color_scheme() function to override
	 * in a child theme.
	 *
	 * @param string $value Color scheme name value.
	 *
	 * @return string Color scheme name.
	 */
	function squire_sanitize_color_scheme( $value ) {
		$color_schemes = squire_get_color_scheme_choices();

		if ( ! array_key_exists( $value, $color_schemes ) ) {
			return 'default';
		}

		return $value;
	}
endif; // squire_sanitize_color_scheme

/**
 * Enqueues front-end CSS for color scheme.
 *
 * @see wp_add_inline_style()
 */
function squire_color_scheme_css() {
	$color_scheme_option = get_theme_mod( 'color_scheme', 'default' );

	// Don't do anything if the default color scheme is selected.
	if ( 'default' === $color_scheme_option ) {
		return;
	}

	$color_scheme = squire_get_color_scheme();

	// If we get this far, we have a custom color scheme.
	$colors = array(
		'background_color'      => $color_scheme[0],
		'page_background_color' => $color_scheme[1],
		'link_color'            => $color_scheme[2],
		'main_text_color'       => $color_scheme[3],
		'secondary_text_color'  => $color_scheme[4],
		'border_color'          => $color_scheme[5]
	);

	$color_scheme_css = squire_get_color_scheme_css( $colors );

	wp_add_inline_style( 'squire-style', $color_scheme_css );
}

add_action( 'wp_enqueue_scripts', 'squire_color_scheme_css' );

/**
 * Binds the JS listener to make Customizer color_scheme control.
 *
 * Passes color scheme data as colorScheme global.
 */
function squire_customize_control_js() {
	wp_enqueue_script( 'color-scheme-control', get_template_directory_uri() . '/assets/js/color-scheme-control.js', array(
		'customize-controls',
		'iris',
		'underscore',
		'wp-util'
	), SQUIRE_VERSION, true );
	wp_localize_script( 'color-scheme-control', 'colorScheme', squire_get_color_schemes() );
}

add_action( 'customize_controls_enqueue_scripts', 'squire_customize_control_js' );

/**
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 */
function squire_customize_preview_js() {
	wp_enqueue_script( 'squire-customize-preview', get_template_directory_uri() . '/assets/js/customize-preview.js', array( 'customize-preview' ), SQUIRE_VERSION, true );
}

add_action( 'customize_preview_init', 'squire_customize_preview_js' );

/**
 * Returns CSS for the color schemes.
 *
 * @param array $colors Color scheme colors.
 *
 * @return string Color scheme CSS.
 */
function squire_get_color_scheme_css( $colors ) {
	$colors = wp_parse_args( $colors, array(
		'background_color'      => '',
		'page_background_color' => '',
		'link_color'            => '',
		'main_text_color'       => '',
		'secondary_text_color'  => '',
		'border_color'          => '',
	) );

	return <<<CSS
	/* Color Scheme */

	/* Background Color */
	body,
	.site-header,
	.main-navigation .menu > li > a,
	.widget_nav_menu .menu > li > a {
		background-color: {$colors['background_color']};
	}
	
	.sq-content {
		background-color: {$colors['page_background_color']};
	}

	/* Link Color */
	a,
	.screen-reader-text:focus,
	.main-navigation .menu > li > a:hover,
	.main-navigation .menu > li > a:active,
	.main-navigation .menu > li > a:focus,
	.widget_nav_menu .menu > li > a:hover,
	.widget_nav_menu .menu > li > a:active,
	.widget_nav_menu .menu > li > a:focus,
	.main-navigation .menu > li.current-menu-item > a,
	.widget_nav_menu .menu > li.current-menu-item > a,
	.main-navigation .menu.nested > li > a:hover,
	.main-navigation .menu.nested > li > a:active,
	.main-navigation .menu.nested > li > a:focus,
	.widget_nav_menu .menu.nested > li > a:hover,
	.widget_nav_menu .menu.nested > li > a:active,
	.widget_nav_menu .menu.nested > li > a:focus,
	.sub-navigation .menu > li > a:hover,
	.sub-navigation .menu > li > a:active,
	.sub-navigation .menu > li > a:focus {
		color: {$colors['link_color']};
	}

	.button,
	.comment-form input[type="submit"],
	.menu-toggle,
	.sub-navigation::before,
	.entry-title::after,
	.edit-link .post-edit-link {
		background-color: {$colors['link_color']};
	}

	input[type="date"]:focus,
	input[type="time"]:focus,
	input[type="datetime-local"]:focus,
	input[type="week"]:focus,
	input[type="month"]:focus,
	input[type="text"]:focus,
	input[type="email"]:focus,
	input[type="url"]:focus,
	input[type="password"]:focus,
	input[type="search"]:focus,
	input[type="tel"]:focus,
	input[type="number"]:focus,
	textarea:focus,
	.tagcloud a:hover,
	.tagcloud a:focus,
	.menu-toggle:hover,
	.menu-toggle:focus {
		border-color: {$colors['link_color']};
	}
	
	.is-accordion-submenu-parent>a:after,
	.dropdown.menu > li.is-dropdown-submenu-parent > a::after {
		border-color: {$colors['link_color']} transparent transparent;
	}
	
	.dropdown.menu.vertical > li.opens-left > a::after {
		border-color: transparent {$colors['link_color']} transparent transparent;
	}
	
	.dropdown.menu.vertical > li.opens-right > a::after {
		border-color: transparent transparent transparent {$colors['link_color']};
	}
	
	@media print, screen and (min-width: 40em) {
		.dropdown.menu.medium-horizontal > li.is-dropdown-submenu-parent > a::after {
			border-color: {$colors['link_color']} transparent transparent;
		}
	
		.dropdown.menu.medium-vertical > li.opens-left > a::after {
			border-color: transparent {$colors['link_color']} transparent transparent;
		}
	
		.dropdown.menu.medium-vertical > li.opens-right > a::after {
			border-color: transparent transparent transparent {$colors['link_color']};
		}
	}
	
	@media print, screen and (min-width: 64em) {	
		.dropdown.menu.large-horizontal > li.is-dropdown-submenu-parent > a::after {
			border-color: {$colors['link_color']} transparent transparent;
		}
	
		.dropdown.menu.large-vertical > li.opens-left > a::after {
			border-color: transparent {$colors['link_color']} transparent transparent;
		}
	
		.dropdown.menu.large-vertical > li.opens-right > a::after {
			border-color: transparent transparent transparent {$colors['link_color']};
		}
	}
	
	@media screen and (min-width: 92.5em) {
		.dropdown.menu.xlarge-horizontal > li.is-dropdown-submenu-parent > a::after {
			border-color: {$colors['link_color']} transparent transparent;
		}
	
		.dropdown.menu.xlarge-vertical > li.opens-left > a::after {
			border-color: transparent {$colors['link_color']} transparent transparent;
		}
	
		.dropdown.menu.xlarge-vertical > li.opens-right > a::after {
			border-color: transparent transparent transparent {$colors['link_color']};
		}
	}
	
	@media screen and (min-width: 100em) {
		.dropdown.menu.xxlarge-horizontal > li.is-dropdown-submenu-parent > a::after {
			border-color: {$colors['link_color']} transparent transparent;
		}
	
		.dropdown.menu.xxlarge-vertical > li.opens-left > a::after {
			border-color: transparent {$colors['link_color']} transparent transparent;
		}
	}
	
	.is-dropdown-submenu .is-dropdown-submenu-parent.opens-left > a::after {
		border-color: transparent {$colors['link_color']} transparent transparent;
	}
	
	.is-dropdown-submenu .is-dropdown-submenu-parent.opens-right > a::after {
		border-color: transparent transparent transparent {$colors['link_color']};
	}

	/* Main Text Color */
	body,
	abbr,
	code,
	kbd,
	[type='text'],
	[type='password'],
	[type='date'],
	[type='datetime'],
	[type='datetime-local'],
	[type='month'],
	[type='week'],
	[type='email'],
	[type='number'],
	[type='search'],
	[type='tel'],
	[type='time'],
	[type='url'],
	[type='color'],
	textarea,
	label,
	.help-text,
	.input-group-label,
	select,
	.button.secondary,
	.button.secondary:hover,
	.button.secondary:focus,
	.button.success,
	.button.success:hover,
	.button.success:focus,
	.button.warning,
	.button.warning:hover,
	.button.warning:focus,
	.accordion-content,
	.badge.secondary,
	.badge.success,
	.badge.warning,
	.breadcrumbs li,
	.button-group.secondary .button,
	.button-group.secondary .button:hover,
	.button-group.secondary .button:focus,
	.button-group.success .button,
	.button-group.success .button:hover,
	.button-group.success .button:focus,
	.button-group.warning .button,
	.button-group.warning .button:hover, .button-group.warning .button:focus,
	.callout,
	.callout.primary,
	.callout.secondary,
	.callout.success,
	.callout.warning,
	.callout.alert,
	.card,
	.close-button:hover,
	.close-button:focus,
	.label.secondary,
	.label.success,
	.label.warning,
	.pagination a,
	.pagination button,
	.pagination .ellipsis::after,
	thead,
	tfoot,
	.tabs-content,
	.main-navigation .menu > li > a,
	.widget_nav_menu .menu > li > a,
	.main-navigation .menu.nested > li > a,
	.widget_nav_menu .menu.nested > li > a,
	.sub-navigation .menu > li > a,
	a:hover,
	a:focus {
		color: {$colors['main_text_color']};
	}

	.comment-form input[type="submit"]:hover,
	.comment-form input[type="submit"]:focus,
	.button:hover,
	.button:focus,
	input[type="button"],
	input[type="button"][disabled]:hover,
	input[type="button"][disabled]:focus,
	input[type="reset"],
	input[type="reset"][disabled]:hover,
	input[type="reset"][disabled]:focus,
	input[type="submit"],
	input[type="submit"][disabled]:hover,
	input[type="submit"][disabled]:focus,
	.menu-toggle.toggled-on,
	.menu-toggle.toggled-on:hover,
	.menu-toggle.toggled-on:focus,
	.pagination:before,
	.pagination:after,
	.pagination .prev,
	.pagination .next,
	.page-links a {
		background-color: {$colors['main_text_color']};
	}

	/* Secondary Text Color */

	.button.hollow.secondary {
		color: {$colors['secondary_text_color']};
	}

	.button.secondary,
	.button.disabled.secondary:hover,
	.button.disabled.secondary:focus,
	.button[disabled].secondary:hover,
	.button[disabled].secondary:focus,
	.badge.secondary,
	.button-group.secondary .button,
	.label.secondary {
		background-color: {$colors['secondary_text_color']};
	}
	
	.button.hollow.secondary {
		border-color: {$colors['secondary_text_color']};
	}

	/* Border Color */
	.comment-metadata a {
		color: {$colors['border_color']};
	}
	
	.site,
	.sq-branding,
	.widget,
	.main-navigation .menu > li > a,
	.widget_nav_menu .menu > li > a,
	.sub-navigation,
	.sub-navigation::before,
	.sub-navigation .menu > li > a,
	.entry-title,
	.site-footer,
	.color-block {
		border-color: {$colors['border_color']};
	}
	
	@media screen and (min-width: 64em) {
		.sq-sidebar {
			border-color: {$colors['border_color']};
		}
	}

CSS;
}


/**
 * Outputs an Underscore template for generating CSS for the color scheme.
 *
 * The template generates the css dynamically for instant display in the
 * Customizer preview.
 */
function squire_color_scheme_css_template() {
	$colors = array(
		'background_color'      => '{{ data.background_color }}',
		'page_background_color' => '{{ data.page_background_color }}',
		'link_color'            => '{{ data.link_color }}',
		'main_text_color'       => '{{ data.main_text_color }}',
		'secondary_text_color'  => '{{ data.secondary_text_color }}',
		'border_color'          => '{{ data.border_color }}',
	);
	?>
	<script type="text/html" id="tmpl-squire-color-scheme">
		<?php echo squire_get_color_scheme_css( $colors ); ?>
	</script>
	<?php
}

add_action( 'customize_controls_print_footer_scripts', 'squire_color_scheme_css_template' );

/**
 * Enqueues front-end CSS for the page background color.
 *
 * @see wp_add_inline_style()
 */
function squire_page_background_color_css() {
	$color_scheme          = squire_get_color_scheme();
	$default_color         = $color_scheme[1];
	$page_background_color = get_theme_mod( 'page_background_color', $default_color );

	// Don't do anything if the current color is the default.
	if ( $page_background_color === $default_color ) {
		return;
	}

	$css = '
		/* Custom Page Background Color */
		.sq-content {
			background-color: %1$s;
		}
	';

	wp_add_inline_style( 'squire-style', sprintf( $css, $page_background_color ) );
}

add_action( 'wp_enqueue_scripts', 'squire_page_background_color_css', 11 );

/**
 * Enqueues front-end CSS for the link color.
 *
 * @see wp_add_inline_style()
 */
function squire_link_color_css() {
	$color_scheme  = squire_get_color_scheme();
	$default_color = $color_scheme[2];
	$link_color    = get_theme_mod( 'link_color', $default_color );

	// Don't do anything if the current color is the default.
	if ( $link_color === $default_color ) {
		return;
	}

	$css = '
		/* Custom Link Color */
		a,
		.screen-reader-text:focus,
		.main-navigation .menu > li > a:hover,
		.main-navigation .menu > li > a:active,
		.main-navigation .menu > li > a:focus,
		.widget_nav_menu .menu > li > a:hover,
		.widget_nav_menu .menu > li > a:active,
		.widget_nav_menu .menu > li > a:focus,
		.main-navigation .menu > li.current-menu-item > a,
		.widget_nav_menu .menu > li.current-menu-item > a,
		.main-navigation .menu.nested > li > a:hover,
		.main-navigation .menu.nested > li > a:active,
		.main-navigation .menu.nested > li > a:focus,
		.widget_nav_menu .menu.nested > li > a:hover,
		.widget_nav_menu .menu.nested > li > a:active,
		.widget_nav_menu .menu.nested > li > a:focus,
		.sub-navigation .menu > li > a:hover,
		.sub-navigation .menu > li > a:active,
		.sub-navigation .menu > li > a:focus {
			color: %1$s;
		}

		.button,
		.comment-form input[type="submit"],
		.menu-toggle,
		.sub-navigation::before,
		.entry-title::after,
		.edit-link .post-edit-link {
			background-color: %1$s;
		}

		.button.hollow,
		input[type="date"]:focus,
		input[type="time"]:focus,
		input[type="datetime-local"]:focus,
		input[type="week"]:focus,
		input[type="month"]:focus,
		input[type="text"]:focus,
		input[type="email"]:focus,
		input[type="url"]:focus,
		input[type="password"]:focus,
		input[type="search"]:focus,
		input[type="tel"]:focus,
		input[type="number"]:focus,
		textarea:focus,
		.tagcloud a:hover,
		.tagcloud a:focus,
		.menu-toggle:hover,
		.menu-toggle:focus {
			border-color: %1$s;
		}
		
		.is-accordion-submenu-parent>a:after,
		.dropdown.menu > li.is-dropdown-submenu-parent > a::after {
			border-color: %1$s transparent transparent;
		}
		
		.dropdown.menu.vertical > li.opens-left > a::after {
			border-color: transparent %1$s transparent transparent;
		}
		
		.dropdown.menu.vertical > li.opens-right > a::after {
			border-color: transparent transparent transparent %1$s;
		}
		
		@media print, screen and (min-width: 40em) {
			.dropdown.menu.medium-horizontal > li.is-dropdown-submenu-parent > a::after {
				border-color: %1$s transparent transparent;
			}
		
			.dropdown.menu.medium-vertical > li.opens-left > a::after {
				border-color: transparent %1$s transparent transparent;
			}
		
			.dropdown.menu.medium-vertical > li.opens-right > a::after {
				border-color: transparent transparent transparent %1$s;
			}
		}
		
		@media print, screen and (min-width: 64em) {	
			.dropdown.menu.large-horizontal > li.is-dropdown-submenu-parent > a::after {
				border-color: %1$s transparent transparent;
			}
		
			.dropdown.menu.large-vertical > li.opens-left > a::after {
				border-color: transparent %1$s transparent transparent;
			}
		
			.dropdown.menu.large-vertical > li.opens-right > a::after {
				border-color: transparent transparent transparent %1$s;
			}
		}
		
		@media screen and (min-width: 92.5em) {
			.dropdown.menu.xlarge-horizontal > li.is-dropdown-submenu-parent > a::after {
				border-color: %1$s transparent transparent;
			}
		
			.dropdown.menu.xlarge-vertical > li.opens-left > a::after {
				border-color: transparent %1$s transparent transparent;
			}
		
			.dropdown.menu.xlarge-vertical > li.opens-right > a::after {
				border-color: transparent transparent transparent %1$s;
			}
		}
		
		@media screen and (min-width: 100em) {
			.dropdown.menu.xxlarge-horizontal > li.is-dropdown-submenu-parent > a::after {
				border-color: %1$stransparent transparent;
			}
		
			.dropdown.menu.xxlarge-vertical > li.opens-left > a::after {
				border-color: transparent %1$s transparent transparent;
			}
		}
		
		.is-dropdown-submenu .is-dropdown-submenu-parent.opens-left > a::after {
			border-color: transparent %1$s transparent transparent;
		}
		
		.is-dropdown-submenu .is-dropdown-submenu-parent.opens-right > a::after {
			border-color: transparent transparent transparent %1$s;
		}
	';

	wp_add_inline_style( 'squire-style', sprintf( $css, $link_color ) );
}

add_action( 'wp_enqueue_scripts', 'squire_link_color_css', 11 );

/**
 * Enqueues front-end CSS for the main text color.
 *
 * @see wp_add_inline_style()
 */
function squire_main_text_color_css() {
	$color_scheme    = squire_get_color_scheme();
	$default_color   = $color_scheme[3];
	$main_text_color = get_theme_mod( 'main_text_color', $default_color );

	// Don't do anything if the current color is the default.
	if ( $main_text_color === $default_color ) {
		return;
	}

	$css = '
		/* Custom Main Text Color */
		body,
		abbr,
		code,
		kbd,
		[type=\'text\'],
		[type=\'password\'],
		[type=\'date\'],
		[type=\'datetime\'],
		[type=\'datetime-local\'],
		[type=\'month\'],
		[type=\'week\'],
		[type=\'email\'],
		[type=\'number\'],
		[type=\'search\'],
		[type=\'tel\'],
		[type=\'time\'],
		[type=\'url\'],
		[type=\'color\'],
		textarea,
		label,
		.help-text,
		.input-group-label,
		select,
		.button.secondary,
		.button.secondary:hover,
		.button.secondary:focus,
		.button.success,
		.button.success:hover,
		.button.success:focus,
		.button.warning,
		.button.warning:hover,
		.button.warning:focus,
		.accordion-content,
		.badge.secondary,
		.badge.success,
		.badge.warning,
		.breadcrumbs li,
		.button-group.secondary .button,
		.button-group.secondary .button:hover,
		.button-group.secondary .button:focus,
		.button-group.success .button,
		.button-group.success .button:hover,
		.button-group.success .button:focus,
		.button-group.warning .button,
		.button-group.warning .button:hover,
		.button-group.warning .button:focus,
		.callout,
		.callout.primary,
		.callout.secondary,
		.callout.success,
		.callout.warning,
		.callout.alert,
		.card,
		.close-button:hover,
		.close-button:focus,
		.label.secondary,
		.label.success,
		.label.warning,
		.pagination a,
		.pagination button,
		.pagination .ellipsis::after,
		thead,
		tfoot,
		.tabs-content,
		.main-navigation .menu > li > a,
		.widget_nav_menu .menu > li > a,
		.main-navigation .menu.nested > li > a,
		.widget_nav_menu .menu.nested > li > a,
		.sub-navigation .menu > li > a {
			color: %1$s
		}

		.comment-form input[type="submit"]:hover,
		.comment-form input[type="submit"]:focus,
		.button:hover,
		.button:focus,
		input[type="button"],
		input[type="button"][disabled]:hover,
		input[type="button"][disabled]:focus,
		input[type="reset"],
		input[type="reset"][disabled]:hover,
		input[type="reset"][disabled]:focus,
		input[type="submit"],
		input[type="submit"][disabled]:hover,
		input[type="submit"][disabled]:focus,
		.menu-toggle.toggled-on,
		.menu-toggle.toggled-on:hover,
		.menu-toggle.toggled-on:focus,
		.pagination:before,
		.pagination:after,
		.pagination .prev,
		.pagination .next,
		.page-links a {
			background-color: %1$s;
		}
	';

	wp_add_inline_style( 'squire-style', sprintf( $css, $main_text_color ) );
}

add_action( 'wp_enqueue_scripts', 'squire_main_text_color_css', 11 );

/**
 * Enqueues front-end CSS for the secondary text color.
 *
 * @see wp_add_inline_style()
 */
function squire_secondary_text_color_css() {
	$color_scheme         = squire_get_color_scheme();
	$default_color        = $color_scheme[4];
	$secondary_text_color = get_theme_mod( 'secondary_text_color', $default_color );

	// Don't do anything if the current color is the default.
	if ( $secondary_text_color === $default_color ) {
		return;
	}

	$css = '
		/* Custom Secondary Text Color */

		.button.hollow.secondary {
			color: %1$s;
		}

		.button.secondary,
		.button.disabled.secondary:hover,
		.button.disabled.secondary:focus,
		.button[disabled].secondary:hover,
		.button[disabled].secondary:focus,
		.badge.secondary,
		.button-group.secondary .button,
		.label.secondary {
			background-color: %1$s;
		}
		
		.button.hollow.secondary {
			border-color: %1$s;
		}
	';

	wp_add_inline_style( 'squire-style', sprintf( $css, $secondary_text_color ) );
}

add_action( 'wp_enqueue_scripts', 'squire_secondary_text_color_css', 11 );

/**
 * Enqueues front-end CSS for the border color.
 *
 * @see wp_add_inline_style()
 */
function squire_border_color_css() {
	$color_scheme  = squire_get_color_scheme();
	$default_color = $color_scheme[5];
	$border_color  = get_theme_mod( 'border_color', $default_color );

	// Don't do anything if the current color is the default.
	if ( $border_color === $default_color ) {
		return;
	}

	$css = '
		/* Custom Border Color */

		.comment-metadata a {
			color: %1$s;
		}
		
		.site,
		.sq-branding,
		.widget,
		.main-navigation .menu > li > a,
		.widget_nav_menu .menu > li > a,
		.sub-navigation,
		.sub-navigation::before,
		.sub-navigation .menu > li > a,
		.entry-title,
		.site-footer,
		.color-block {
			border-color: %1$s;
		}
		
		@media screen and (min-width: 64em) {
			.sq-sidebar {
				border-color: %1$s;
			}
		}
	';

	wp_add_inline_style( 'squire-style', sprintf( $css, $border_color ) );
}

add_action( 'wp_enqueue_scripts', 'squire_border_color_css', 11 );

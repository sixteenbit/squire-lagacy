<?php
/**
 * Squire functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 */

/**
 * Squire only works in WordPress 4.7 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';

	return;
}

$theme_data    = wp_get_theme( get_option( 'template' ) );
$theme_version = $theme_data->Version;

define( 'SQUIRE_VERSION', $theme_version );

if ( ! function_exists( 'squire_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function squire_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Squire, use a find and replace
		 * to change 'squire' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'squire', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		set_post_thumbnail_size( 1280, 9999 );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary-menu' => esc_html__( 'Primary Menu', 'squire' ),
			'social-links' => esc_html__( 'Social Links', 'squire' )
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'audio',
		) );

		// Add theme support for Custom Logo.
		add_theme_support( 'custom-logo', array(
			'width'      => 250,
			'height'     => 250,
			'flex-width' => true,
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'squire_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/*
		 * This theme styles the visual editor to resemble the theme style,
		 * specifically font, colors, icons, and column width.
		 */
		add_editor_style( array(
			'assets/css/main.css',
			'assets/css/editor-style.css'
		) );
	}
endif;

add_action( 'after_setup_theme', 'squire_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function squire_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'squire_content_width', 1280 );
}

add_action( 'after_setup_theme', 'squire_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function squire_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Top', 'squire' ),
		'id'            => 'sidebar-top',
		'description'   => esc_html__( 'Add widgets here.', 'squire' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Bottom', 'squire' ),
		'id'            => 'sidebar-bottom',
		'description'   => esc_html__( 'Add widgets here.', 'squire' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}

add_action( 'widgets_init', 'squire_widgets_init' );

/**
 * Show kitchen sink by default
 *
 * @param $args
 *
 * @return mixed
 */
function squire_unhide_kitchensink( $args ) {
	$args['wordpress_adv_hidden'] = false;

	return $args;
}

add_filter( 'tiny_mce_before_init', 'squire_unhide_kitchensink' );

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Continue reading' link.
 *
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function squire_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}

	$link = sprintf( '<p class="link-more"><a href="%1$s" class="secondary button more-link">%2$s</a></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Name of current post */
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'squire' ), get_the_title( get_the_ID() ) )
	);

	return ' &hellip; ' . $link;
}

add_filter( 'excerpt_more', 'squire_excerpt_more' );

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 */
function squire_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}

add_action( 'wp_head', 'squire_javascript_detection', 0 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function squire_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}

add_action( 'wp_head', 'squire_pingback_header' );

/**
 * Enqueue scripts and styles.
 */
function squire_scripts() {

	$suffix = is_rtl() ? '-rtl' : '';

	// Load Font Awesome, used in the main stylesheet.
	wp_enqueue_style( 'squire-font-awesome', get_theme_file_uri( '/assets/css/font-awesome' . $suffix . '.css' ), array( 'squire-main' ), SQUIRE_VERSION );

	// Load our main stylesheet.
	wp_enqueue_style( 'squire-main', get_theme_file_uri( '/assets/css/main' . $suffix . '.css' ), array(), SQUIRE_VERSION );

	// Theme stylesheet.
	wp_enqueue_style( 'squire-style', get_stylesheet_uri(), array( 'squire-main' ), SQUIRE_VERSION );

	// Helps with accessibility for keyboard only users.
	wp_enqueue_script( 'squire-skip-link-focus-fix', get_theme_file_uri( '/assets/js/skip-link-focus-fix.js' ), array(), SQUIRE_VERSION, true );

	// Load our Foundation scripts.
	wp_enqueue_script( 'squire-foundation', get_theme_file_uri( '/assets/js/foundation.js' ), array( 'jquery' ), SQUIRE_VERSION, true );

	// Load our theme functions scripts.
	wp_enqueue_script( 'squire-theme', get_theme_file_uri( '/assets/js/theme.js' ), array( 'jquery' ), SQUIRE_VERSION, true );

	// Load the html5 shiv.
	wp_enqueue_script( 'html5', get_theme_file_uri( '/assets/js/html5shiv.js' ), array(), SQUIRE_VERSION );
	wp_script_add_data( 'html5', 'conditional', 'lt IE 9' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'squire_scripts' );

/**
 * Converts a HEX value to RGB.
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 *
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function squire_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ) . substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ) . substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ) . substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

/**
 * Filter the `sizes` value in the header image markup.
 *
 * @param string $html The HTML image tag markup being filtered.
 * @param object $header The custom header object returned by 'get_custom_header()'.
 * @param array $attr Array of the attributes for the image tag.
 *
 * @return string The filtered header image HTML.
 */
function squire_header_image_tag( $html, $header, $attr ) {
	if ( isset( $attr['sizes'] ) ) {
		$html = str_replace( $attr['sizes'], '100vw', $html );
	}

	return $html;
}

add_filter( 'get_header_image_tag', 'squire_header_image_tag', 10, 3 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails.
 *
 * @param array $attr Attributes for the image markup.
 * @param int $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 *
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function squire_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( is_archive() || is_search() || is_home() ) {
		$attr['sizes'] = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
	} else {
		$attr['sizes'] = '100vw';
	}

	return $attr;
}

add_filter( 'wp_get_attachment_image_attributes', 'squire_post_thumbnail_sizes_attr', 10, 3 );

/**
 * Check for theme updates
 */
require get_parent_theme_file_path( '/inc/theme-update-checker.php' );

$SquireThemeUpdateChecker = new ThemeUpdateChecker(
	'squire',
	'https://sixteenbit.com/updates/?action=get_metadata&slug=squire' //Metadata URL.
);

/**
 * Custom template tags for this theme.
 */
require get_parent_theme_file_path( '/inc/template-tags.php' );

/**
 * Custom functions that act independently of the theme templates.
 */
require get_parent_theme_file_path( '/inc/extras.php' );

/**
 * Customizer additions.
 */
require get_parent_theme_file_path( '/inc/customizer.php' );

/**
 * Load Jetpack compatibility file.
 */
require get_parent_theme_file_path( '/inc/jetpack.php' );

/**
 * Load custom menu walkers.
 */
require get_parent_theme_file_path( '/inc/menu-walkers.php' );

/**
 * Load custom shortcodes.
 */
require get_parent_theme_file_path( '/inc/shortcodes.php' );

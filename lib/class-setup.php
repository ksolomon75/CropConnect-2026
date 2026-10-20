<?php
/**
 * Theme Setup
 *
 * Ported from the original Roots/Sage `lib/setup.php`. Everything the previous
 * theme registered is reproduced here under the BasicWP namespace so the
 * converted theme has an identical feature surface.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

/**
 * Theme setup.
 *
 * @return void
 */
function setup() {
	// Enable features from Soil when the plugin is activated.
	add_theme_support( 'soil-clean-up' );
	add_theme_support( 'soil-nav-walker' );
	add_theme_support( 'soil-nice-search' );
	add_theme_support( 'soil-jquery-cdn' );
	add_theme_support( 'soil-relative-urls' );

	// Make the theme available for translation.
	load_theme_textdomain( 'sage', get_template_directory() . '/lang' );

	// Let plugins manage the document title.
	add_theme_support( 'title-tag' );

	// Register wp_nav_menu() menus.
	register_nav_menus(
		array(
			'primary_navigation' => __( 'Primary Navigation', 'sage' ),
		)
	);

	// Enable post thumbnails.
	add_theme_support( 'post-thumbnails' );

	/*
	 * Opt in to WordPress's admin-bar spacing.
	 *
	 * Since WP 6.4, core only emits the `html { margin-top: 32px }` bump that
	 * keeps the fixed admin bar from overlapping page content when a theme
	 * declares support for 'admin-bar'. Without this the toolbar renders at
	 * top:0 and sits on top of the site header for logged-in users.
	 */
	add_theme_support( 'admin-bar' );

	// Enable post formats.
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio' ) );

	// Enable HTML5 markup support.
	add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

	// Use the main stylesheet for the visual editor.
	add_editor_style( 'static/dist/theme.css' );
}

add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup' );

/**
 * Register sidebars.
 *
 * Sidebar IDs are part of the persisted `sidebars_widgets` option, so they
 * must match the previous theme exactly or existing widget placements are
 * dropped.
 *
 * @return void
 */
function widgets_init() {
	$args = array(
		'before_widget' => '<section class="widget %1$s %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	);

	register_sidebar(
		array(
			'name' => __( 'Primary', 'sage' ),
			'id'   => 'sidebar-primary',
		) + $args
	);

	register_sidebar(
		array(
			'name' => __( 'Footer 1', 'sage' ),
			'id'   => 'sidebar-footer-1',
		) + $args
	);

	register_sidebar(
		array(
			'name' => __( 'Footer 2', 'sage' ),
			'id'   => 'sidebar-footer-2',
		) + $args
	);

	register_sidebar(
		array(
			'name' => __( 'Footer 3', 'sage' ),
			'id'   => 'sidebar-footer-3',
		) + $args
	);
}

add_action( 'widgets_init', __NAMESPACE__ . '\\widgets_init' );

/**
 * Determine which pages should NOT display the sidebar.
 *
 * @return bool
 */
function display_sidebar() {
	static $display;

	if ( ! isset( $display ) ) {
		$display = ! in_array(
			true,
			array(
				// The sidebar will NOT be displayed if ANY of the following return true.
				is_404(),
				is_front_page(),
				is_page_template( 'template-custom.php' ),
				is_page_template( 'page-contact.php' ),
			),
			true
		);
	}

	return apply_filters( 'sage/display_sidebar', $display );
}
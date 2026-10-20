<?php
/**
 * Custom functions ported from the original theme.
 *
 * Reproduces `lib/extras.php` from the previous Roots/Sage theme, plus the
 * `cropconnect_*` helpers that lived in its `functions.php`.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

/**
 * Add <body> classes.
 *
 * Deliberately NOT named `body_class()`: WordPress defines that function in the
 * global namespace and template files call it unqualified, so a same-named
 * function in this namespace would shadow it and fatal on every page.
 *
 * @param array $classes Existing body classes.
 * @return array
 */
function sageBodyClass( $classes ) {
	// Add the page slug if it doesn't exist.
	if ( is_single() || ( is_page() && ! is_front_page() ) ) {
		if ( ! in_array( basename( get_permalink() ), $classes, true ) ) {
			$classes[] = basename( get_permalink() );
		}
	}

	// Add a class if the sidebar is active.
	if ( display_sidebar() ) {
		$classes[] = 'sidebar-primary';
	}

	return $classes;
}

add_filter( 'body_class', __NAMESPACE__ . '\\sageBodyClass' );

/**
 * Clean up the_excerpt().
 *
 * @return string
 */
function excerpt_more() {
	return ' &hellip; <a href="' . get_permalink() . '">' . __( 'Continued', 'sage' ) . '</a>';
}

add_filter( 'excerpt_more', __NAMESPACE__ . '\\excerpt_more' );

/**
 * Helper to return sponsors limited to a single type.
 *
 * @param string $type Sponsor type taxonomy slug.
 * @return array
 */
function cropconnect_get_sponsors_by_type( $type ) {
	$args = array(
		'numberposts'      => -1,
		'offset'           => 0,
		'orderby'          => 'title',
		'order'            => 'ASC',
		'post_type'        => 'sponsor',
		'post_status'      => 'publish',
		'suppress_filters' => true,
		'tax_query'        => array(
			array(
				'taxonomy' => 'sponsor_type',
				'field'    => 'slug',
				'terms'    => $type,
			),
		),
	);

	return wp_get_recent_posts( $args, ARRAY_A );
}

/**
 * Strips MOGA (Manitoba Oat Growers Association) markup out of homepage copy.
 *
 * Historically applied inline on the home template; kept as a filter so any
 * call site gets identical output.
 *
 * @param string $content Post content.
 * @return string
 */
function cropconnect_remove_moga_homepage_content( $content ) {
	if ( empty( $content ) ) {
		return $content;
	}

	$patterns = array(
		'#<li\b(?:(?!<li\b).)*?(?:poga\.ca|MOGA_logo_colour|Manitoba\s+Oat)(?:(?!<li\b).)*?</li>#is',
		'#,\s*Manitoba\s+Oat\s+Growers(?:\s+Association)?#i',
		'#Manitoba\s+Oat\s+Growers(?:\s+Association)?\s*,\s*#i',
		'#Manitoba\s+Oat\s+Growers(?:\s+Association)?#i',
	);

	return preg_replace( $patterns, '', $content );
}
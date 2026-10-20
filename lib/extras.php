<?php
/**
 * Filters. etc
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

/** Get child pages of the current page, sorted by menu order.
 *
 * @return array Array of child page objects, with URL added to each.
 */
function getChildrenPages() {
	$children = get_pages(
		array(
			'child_of'    => get_the_ID(),
			'sort_order'  => 'ASC',
			'sort_column' => 'menu_order',
		)
	);

	foreach ( $children as &$child ) {
		$child->url = get_page_link( $child->ID );
	}

	return $children;
}

// Modify which pages should render the sidebar.
add_filter(
	'hasSidebar',
	function ( $has_sidebar ) {
		// Add post types that should never have a sidebar.

		if ( is_page() && ! get_field( 'has_sidebar' ) ) {
			return false;
		}

		return $has_sidebar;
	}
);

/** Helper to check whether or not the sidebar should be rendered
 * (to add/remove a sidebar from a page, edit the filter instead
 * of modifying this function).
 */
function hasSidebar() {
	return apply_filters( 'hasSidebar', true );
}


/**
 * Checks if the page should render a page header.
 *
 * @return bool true if page header should be rendered, false otherwise
 */
function hasPageHeader() {
	global $post;

	if ( get_field( 'hero_style' ) !== 'none' ) {
		return false;
	}

	return true;
}

/** Create the Owner role.
 *
 * This function creates a new role named "Owner" with all the capabilities of the
 * Administrator role, except for the following:
 *
 * - activate_plugins
 * - delete_plugins
 * - edit_plugins
 * - install_plugins
 * - update_plugins
 * - switch_themes
 * - edit_themes
 * - delete_themes
 * - install_themes
 * - update_themes
 * - update_core
 * - manage_options
 *
 * This role is meant to be used by a person who should have almost all the same
 * capabilities as an Administrator, but should not have the ability to update
 * the WordPress core software, manage plugins or themes, or edit other site
 * options.
 *
 * @return void
 */
function createOwnerRole() {
	// First, remove the role if it exists.
	remove_role( 'owner' );

	// Get the administrator role.
	$admin_role         = get_role( 'administrator' );
	$admin_capabilities = $admin_role->capabilities;

	// Remove specific capabilities.
	$capabilities_to_remove = array(
		'activate_plugins',
		'delete_plugins',
		'edit_plugins',
		'install_plugins',
		'update_plugins',
		'switch_themes',
		'edit_themes',
		'delete_themes',
		'install_themes',
		'update_themes',
		'update_core',
		'manage_options',
	);

	foreach ( $capabilities_to_remove as $capability ) {
		unset( $admin_capabilities[ $capability ] );
	}

	// Add the Owner role with the modified capabilities.
	add_role( 'owner', 'Owner', $admin_capabilities );
}
add_action( 'init', __NAMESPACE__ . '\\createOwnerRole' );

/** Retrieves the appropriate title for the current page context.
 *
 * The function determines the type of page being viewed and returns
 * the corresponding title. It handles different page types, including
 * the home page, single posts, archives, search results, and 404 pages.
 * If none of these conditions apply, it defaults to fetching the page's title.
 *
 * @return string The title relevant to the current page context.
 */
function getTheTitle() {
	$title = '';

	if ( is_home() || is_single() ) {
		$title = get_the_title( get_option( 'page_for_posts', true ) );
	} elseif ( is_archive() ) {
		$title = get_the_archive_title();
	} elseif ( is_search() ) {
		$title = sprintf(
		/* translators: %s is replaced with the search query */
			__( 'Search Results for "%s"', 'basicwp' ),
			get_search_query()
		);
	} elseif ( is_404() ) {
		$title = 'Page Not Found (error 404)';
	} else {
		$title = get_the_title();
	}

	return $title;
}

/** Wraps iframes and embed elements in a div with a specific class.
 *
 * This function searches for iframe and embed elements within the provided
 * content and wraps each found element in a div with the class "embed".
 * It is useful for applying consistent styling or responsive behavior
 * to embedded media elements.
 *
 * @param string $content The HTML content containing iframes or embeds.
 * @return string The modified content with wrapped iframes and embeds.
 */
function divWrapper( $content ) {
	// match any iframes.
	$pattern = '~<iframe.*</iframe>|<embed.*</embed>~';
	preg_match_all( $pattern, $content, $matches );

	foreach ( $matches[0] as $match ) {
		// wrap matched iframe with div.
		$wrappedframe = '<div class="embed">' . $match . '</div>';

		// replace original iframe with new in content.
		$content = str_replace( $match, $wrappedframe, $content );
	}

	return $content;
}

add_filter( 'the_content', __NAMESPACE__ . '\\divWrapper' );

/** Selectively add sidebar to page.
 *
 * This function adds a custom field group to the WordPress editor for
 * pages, allowing users to specify whether a page should have a sidebar.
 * Default is no sidebar.
 *
 * @return void
 */
add_action(
	'acf/include_fields',
	function () {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_local_field_group(
			array(
				'key'                   => 'group_6817d79573087',
				'title'                 => 'Page Sidebar',
				'fields'                => array(
					array(
						'key'               => 'field_6817d7954a168',
						'label'             => '',
						'name'              => 'has_sidebar',
						'aria-label'        => '',
						'type'              => 'true_false',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'message'           => 'Should this page have a sidebar?',
						'default_value'     => 0,
						'allow_in_bindings' => 0,
						'ui'                => 0,
						'ui_on_text'        => '',
						'ui_off_text'       => '',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'page',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'side',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
				'show_in_rest'          => 0,
			)
		);
	}
);

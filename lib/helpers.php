<?php
/**
 * BasicWP Theme Helpers
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

// Define global variables for theme and views folder paths.
global $theme, $views;

$theme = get_template_directory();
$views = $theme . '/views';

/** Retrieves a nested value from an ACF field.
 *
 * @param string $field_path The dot-notated path to the value. For example, 'contact_info.phone'.
 *
 * @return mixed The value at the specified path.
 */
function getFieldValue( $field_path ) {
	$parts = explode( '.', $field_path );
	$field = get_field( array_shift( $parts ), 'option' );

	foreach ( $parts as $part ) {
		$field = $field[ $part ] ?? '';
	}

	return $field;
}

/**
 * Returns wrapper attributes for a block for both preview and front-end contexts.
 *
 * @param string $classes    Space separated class list.
 * @param bool   $is_preview Whether the block is being previewed in the editor.
 *
 * @return string Wrapper attributes ready for output.
 */
function blockWrapperAttributes( $classes, $is_preview ) {
	if ( $is_preview ) {
		return 'class="' . $classes . '"';
	}

	return get_block_wrapper_attributes(
		array(
			'class' => $classes,
		)
	);
}

// Add Global Fields options page.
if ( function_exists( 'acf_add_options_page' ) ) {
	add_action(
		'init',
		function () {
			acf_add_options_page(
				array(
					'page_title' => 'Global Fields',
					'menu_title' => 'Global Fields',
					'menu_slug'  => 'global-fields',
					'icon_url'   => 'dashicons-admin-site',
				)
			);
		}
	);
}

/** Customizes the order of the admin menu items in WordPress.
 *
 * This function modifies the default menu order in the WordPress admin dashboard
 * by specifying a custom sequence for menu items, separators, and additional
 * options. If the menu order is not specified, it returns true to allow the
 * default order to be used.
 *
 * @param bool $menu_ord Indicates whether the menu order has been specified.
 *
 * @return array|bool An array specifying the custom menu order, or true if the
 *                    menu order is not specified.
 */
function customMenuOrder( $menu_ord ) {
	if ( ! $menu_ord ) {
		return true;
	}

	return array(
		'index.php', // Dashboard.
		'global-fields', // Global Theme Fields.
		'edit.php?post_type=acf-field-group', // ACF Field Groups.
		'separator1', // First separator.
		'edit.php', // Posts.
		'edit.php?post_type=page', // Pages.
		'edit.php?post_type=resources', // Resources.
		'upload.php', // Media.
		'separator2', // Second separator.
		'edit.php?post_type=page-template', // Page Templates.
		'edit.php?post_type=wp_block', // Reusable Blocks.
		'edit.php?post_type=block-pattern', // Block Patterns.
		'edit.php?post_type=element', // Elements.
		'separator3', // Third separator.
		'link-manager.php', // Links.
		'edit-comments.php', // Comments.
		'gf_edit_forms', // Gravity Forms.
		'themes.php', // Appearance.
		'plugins.php', // Plugins.
		'separator-last', // Last separator.
		'users.php', // Users.
		'tools.php', // Tools.
		'options-general.php', // Settings.
	);
}

add_filter( 'custom_menu_order', __NAMESPACE__ . '\\customMenuOrder', 10, 1 );
add_filter( 'menu_order', __NAMESPACE__ . '\\customMenuOrder', 10, 1 );

/** Add custom block category for our blocks
 *
 * @param array $categories The existing block categories.
 * @return array
 */
function blockCategories( $categories ) {
	$vdi_cat = array(
		'slug'  => 'vdi-blocks',
		'title' => 'VDI Custom Blocks',
		'icon'  => 'dashicons-admin-customizer',
	);

	array_unshift( $categories, $vdi_cat );
	return $categories;
}

add_filter( 'block_categories_all', __NAMESPACE__ . '\\blockCategories', 10, 2 );

/**
 * Creates a escaping function to allowed certain HTML for embed content.
 * Needed for when echoing the innerblock HTML.
 *
 * @return array An array of HTML elements allowed.
 */
function escEmbeds() {
	/**
	 * Return the allowed html
	 * These are the elements in the rendered embed block for youtube and vimeo videos.
	 * Therefore we need to allow these to keep the same structure.
	 */
	return array(
		'iframe' => array(
			'role'            => true, // Add role="presentation" to iframes.
			'presentation'    => true, // Add role="presentation" to iframes.
			'src'             => true,
			'height'          => true,
			'width'           => true,
			'frameborder'     => true,
			'allowfullscreen' => true,
		),
		'figure' => array(
			'class' => true,
		),
		'div'    => array(
			'class' => true,
		),
	);
}

/**
 * Finds the numeric position of the first occurrence of a needle in a haystack array.
 *
 * @param string $haystack The string to search in.
 * @param mixed  $needles The array of strings to search for.
 * @param int    $offset (Optional) The position to start the search from.
 * @return int|false The numeric position of the first occurrence of a needle in the haystack array.
 */
function strposArray( $haystack, $needles, $offset = 0 ) {
	if ( is_array( $needles ) ) {
		$positions = array();

		foreach ( $needles as $str ) {
			$pos = strpos( $haystack, $str, $offset );

			if ( $pos !== false ) {
				$positions[] = $pos; }
		}

		return count( $positions ) ? min( $positions ) : false;
	} else {
		return strpos( $haystack, $needles, $offset );
	}
}

/**
 * Generates a custom excerpt for the given text.
 *
 * @param string      $text The text to generate the excerpt from.
 * @param int         $number_of_words The maximum number of words in the excerpt. Default is 55.
 * @param string|null $more The string to append to the end of the excerpt if it is truncated. Default is null.
 * @return void
 */
function customExcerpt( $text, $number_of_words = 55, $more = null ) {
    $allowed_end           = array( '.', '!', '?', '...' );
    $text_no_html          = wp_strip_all_tags( $text );
    $trimmed_text          = wp_trim_words( $text, $number_of_words, $more );
    $trimmed_text_length   = strlen( $trimmed_text );
    $sentence_end_position = strposArray( $text_no_html, $allowed_end, $trimmed_text_length );
    $text_with_html        = ( ( $sentence_end_position !== false ) ? substr( $text_no_html, 0, ( $sentence_end_position + 1 ) ) : $trimmed_text );

    echo wp_kses_post( wpautop( $text_with_html ) );
}

/** Print a variable to the console for debugging purposes.
 *
 * @param mixed $data The data to print to the console.
 */
function consoleLog( $data ) {
	echo '<script>';
	echo 'console.log(' . wp_json_encode( $data ) . ')';
	echo '</script>';
}

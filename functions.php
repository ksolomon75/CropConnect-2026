<?php
/**
 * Functions file for the CropConnect theme.
 *
 * This file initializes the theme by including necessary dependencies
 * and loading additional function files.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

// Load functions.
foreach ( glob( __DIR__ . '/lib/*.php' ) as $filename ) {
	include_once $filename;
}

/**
 * Registers custom ACF (Advanced Custom Fields) blocks.
 *
 * This theme's content is built from classic page templates rather than
 * Gutenberg blocks, but any ACF block dropped into `views/blocks/` is still
 * registered so editors are not blocked from using them.
 *
 * @return void
 */
function regACFBlocks() {
	$blocks_dir = get_stylesheet_directory() . '/views/blocks';

	if ( ! is_dir( $blocks_dir ) ) {
		return;
	}

	foreach ( scandir( $blocks_dir ) as $folder ) {
		if ( '.' !== $folder && '..' !== $folder && 'boilerplate' !== $folder && is_dir( $blocks_dir . '/' . $folder ) ) {
			register_block_type( $blocks_dir . '/' . $folder );
		}
	}
}

add_action( 'init', __NAMESPACE__ . '\\regACFBlocks', 5 );
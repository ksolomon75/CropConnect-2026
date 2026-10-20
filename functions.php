<?php
/**
 * Functions file for the BasicWP theme.
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

/** Registers custom ACF (Advanced Custom Fields) blocks for use in the WordPress theme.
 *
 * This function is intended to define and register custom Gutenberg blocks
 * using ACF, allowing for dynamic and reusable content blocks within the theme.
 *
 * @return void
 */
function regACFBlocks() {
	define( 'BLOCKS_DIR', get_stylesheet_directory() . '/views/blocks' );

	if ( is_dir( BLOCKS_DIR ) ) {
		foreach ( scandir( BLOCKS_DIR ) as $folder ) {
			if ( ( '.' !== $folder && '..' !== $folder && 'boilerplate' !== $folder ) && is_dir( BLOCKS_DIR . '/' . $folder ) ) {
				register_block_type( BLOCKS_DIR . '/' . $folder );
			}
		}
	}
}

add_action( 'init', __NAMESPACE__ . '\\regACFBlocks', 5 );

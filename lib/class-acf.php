<?php
/**
 * ACF (Advanced Custom Fields) support class & functions
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

/**
 * Class ACF
 *
 * This class serves as a wrapper or utility for handling Advanced Custom Fields (ACF) functionality.
 * It is part of the Basic-WP project and is located in the `lib` directory.
 *
 * @package Basic-WP
 */
class ACF {
	/**
	 * Variable to hold the file path.
	 *
	 * @var string $path The file path associated with the class.
	 */
	public $path;

	/** Constructor.
	 *
	 * This constructor initializes the class by setting the file path and
	 * adding filters for loading and saving JSON files related to ACF.
	 *
	 * The CropConnect field groups live in the database (they predate this
	 * theme and are shared with the legacy theme), so JSON sync is only wired
	 * up when an `acf/` directory is actually present. Pointing ACF at a
	 * non-existent directory would let the theme's (absent) JSON shadow the
	 * real field groups on the next admin save.
	 */
	public function __construct() {
		$this->path = get_stylesheet_directory() . '/acf';

		if ( is_dir( $this->path ) ) {
			add_filter( 'acf/settings/load_json', array( $this, 'loadJson' ) );
			add_filter( 'acf/settings/save_json', array( $this, 'saveJson' ) );
		}

		add_filter( 'acf/blocks/default_block_version', array( $this, 'blocksV3' ), 10, 2 );
	}

	/** Save JSON.
	 *
	 * @param mixed $path The path to save the JSON file.
	 */
	// phpcs:ignore
	public function saveJson( $path ) {
		return $this->path;
	}

	/** Load JSON.
	 *
	 * @param mixed $paths The paths to load the JSON file.
	 */
	// phpcs:ignore
	public function loadJson( $paths ) {
		return array( $this->path );
	}

	/** Force all ACF blocks to use block API version 3.
	 *
	 * @param int   $version The default block version.
	 * @param array $block   The block settings.
	 */
	// phpcs:ignore
	public function blocksV3( $version, $block ) {
		return 3;
	}
}

if ( function_exists( 'get_fields' ) ) {
	$acfInstance = new ACF();
}

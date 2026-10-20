<?php
/**
 * BasicWP Theme Enqueue Class
 *
 * Rewired for the CropConnect port: the previous theme loaded a single
 * compiled stylesheet and one jQuery-dependent script, plus Google Fonts and
 * the Slideout library (the latter is referenced directly in header.php).
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

/**
 * Class Enqueue
 *
 * Handles the enqueueing of scripts and styles.
 *
 * @package BasicWP
 * @since 1.0.0
 */
class Enqueue {
	/**
	 * Initialize hooks to enqueue scripts and styles.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqFEAssets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqBEAssets' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqEditorAssets' ) );
	}

	/**
	 * Enqueue frontend CSS and JS files.
	 */
	public function enqFEAssets() {
		$theme_dir = get_stylesheet_directory();
		$theme_uri = get_stylesheet_directory_uri();

		/**
		 * CSS
		 */
		$css_path = '/static/dist/theme.css';
		if ( file_exists( $theme_dir . $css_path ) ) {
			$version = filemtime( $theme_dir . $css_path );
			wp_enqueue_style( 'basicwp-theme', $theme_uri . $css_path, array(), $version );
		}

		/**
		 * JS
		 *
		 * theme.js is a plain classic script (not an ES module) because it
		 * depends on jQuery-free globals and the Slideout instance, matching
		 * the previous theme's behaviour.
		 */
		$js_path = '/static/js/theme.js';
		if ( file_exists( $theme_dir . $js_path ) ) {
			$version = filemtime( $theme_dir . $js_path );
			// The previous theme's bundle was jQuery-dependent and several
			// active plugins declare jQuery as a dependency; keep it queued.
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'sage/js', $theme_uri . $js_path, array(), $version, true );
		}

		if ( is_single() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Enqueue backend (admin/editor) CSS and JS files.
	 */
	public function enqBEAssets() {
		$theme_dir = get_stylesheet_directory();
		$theme_uri = get_stylesheet_directory_uri();

		/**
		 * Admin CSS
		 */
		$admin_css_path = '/styles/backend/admin.css';
		if ( file_exists( $theme_dir . $admin_css_path ) ) {
			$version = filemtime( $theme_dir . $admin_css_path );
			wp_enqueue_style( 'basicwp-admin', $theme_uri . $admin_css_path, array(), $version );
		}
	}

	/**
	 * Enqueue block editor CSS (scoped to editor to avoid leaking into wp-admin UI).
	 */
	public function enqEditorAssets() {
		$theme_dir = get_stylesheet_directory();
		$theme_uri = get_stylesheet_directory_uri();

		$editor_css_path = '/styles/backend/editor.css';

		if ( file_exists( $theme_dir . $editor_css_path ) ) {
			$version = filemtime( $theme_dir . $editor_css_path );
			wp_enqueue_style( 'basicwp-editor', $theme_uri . $editor_css_path, array(), $version );
		}
	}
}

// Initialize the Enqueue class.
$enqueue = new Enqueue();
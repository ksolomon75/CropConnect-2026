<?php
/**
 * BasicWP Theme Enqueue Class
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

/**
 * Class Enqueue
 *
 * Handles the enqueueing of scripts and styles in a WordPress theme or plugin.
 *
 * @package Basic-WP
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

        $font_ver = gmdate( 'U' );
        wp_enqueue_style( 'raleway', 'https://fonts.googleapis.com/css2?family=Raleway:wght@100..900&display=swap', false, $font_ver );

        /**
         * JS
         */
        $js_path = '/static/js/theme.js';

        if ( file_exists( $theme_dir . $js_path ) ) {
            $version = filemtime( $theme_dir . $js_path );
            wp_enqueue_script( 'jquery' ); // Needed by downstream scripts; modules can't depend on classic scripts.
            wp_enqueue_script_module( 'basicwp-theme', $theme_uri . $js_path, array(), $version );
            wp_enqueue_script_module( 'basicwp-button', $theme_uri . '/static/js/components/button.js', array( 'basicwp-theme' ), $version );
        }
    }

    /**
     * Enqueue backend (admin/editor) CSS and JS files.
     */
    public function enqBEAssets() {
        $theme_dir = get_stylesheet_directory();
        $theme_uri = get_stylesheet_directory_uri();

        $font_ver = gmdate( 'U' );
        wp_enqueue_style( 'raleway', 'https://fonts.googleapis.com/css2?family=Raleway:wght@100..900&display=swap', false, $font_ver );

        /**
         * Admin CSS
         */
        $admin_css_path = '/styles/backend/admin.css';
        if ( file_exists( $theme_dir . $admin_css_path ) ) {
            $version = filemtime( $theme_dir . $admin_css_path );
            wp_enqueue_style( 'basicwp-admin', $theme_uri . $admin_css_path, array(), $version );
        }

        /**
         * Admin JS
         */
        $admin_js_path = '/static/js/admin.js';
        if ( file_exists( $theme_dir . $admin_js_path ) ) {
            $version = filemtime( $theme_dir . $admin_js_path );
            wp_enqueue_script( 'jquery' ); // Needed by downstream scripts; modules can't depend on classic scripts.
            wp_enqueue_script_module( 'basicwp-admin', $theme_uri . $admin_js_path, array(), $version );
            wp_enqueue_script_module( 'basicwp-button', $theme_uri . '/static/js/components/button.js', array( 'basicwp-admin' ), $version );
        }
    }

    /**
     * Enqueue block editor CSS (scoped to editor to avoid leaking into wp-admin UI).
     */
    public function enqEditorAssets() {
        $theme_dir = get_stylesheet_directory();
        $theme_uri = get_stylesheet_directory_uri();

        $editor_css_path = '/styles/backend/editor.css';

        $font_ver = gmdate( 'U' );
        wp_enqueue_style( 'raleway', 'https://fonts.googleapis.com/css2?family=Raleway:wght@100..900&display=swap', false, $font_ver );

        if ( file_exists( $theme_dir . $editor_css_path ) ) {
            $version = filemtime( $theme_dir . $editor_css_path );
            wp_enqueue_style( 'basicwp-editor', $theme_uri . $editor_css_path, array(), $version );
        }
    }
}

// Initialize the Enqueue class.
$enqueue = new Enqueue();

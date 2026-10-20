<?php
/**
 * Theme Wrapper
 *
 * Ported from the original Roots/Sage `lib/wrapper.php`. The previous theme
 * wrapped every page body in:
 *
 *     <div id="js-main">
 *       <div class="wrap" role="document">
 *         <div class="content">
 *           {template}
 *
 * Those wrappers are part of the published markup (the CSS targets `.content`),
 * so they are reproduced here rather than hand-written into each template.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

/**
 * Returns the template WordPress selected, so the wrapper can include it.
 *
 * @return string
 */
function templatePath() {
	return Wrapper::$main_template;
}

/**
 * Returns a new wrapper instance for the sidebar.
 *
 * @return Wrapper
 */
function sidebarPath() {
	return new Wrapper( 'templates/sidebar.php' );
}

/**
 * Class Wrapper
 *
 * @link https://roots.io/sage/docs/theme-wrapper/
 */
class Wrapper {

	/**
	 * Full path to the main template file.
	 *
	 * @var string
	 */
	public static $main_template;

	/**
	 * Basename of the template file.
	 *
	 * @var string
	 */
	public $slug;

	/**
	 * Array of candidate templates.
	 *
	 * @var array
	 */
	public $templates;

	/**
	 * Base name of the template file, e.g. 'page' for 'page.php'.
	 *
	 * @var string|false
	 */
	public static $base;

	/**
	 * Constructor.
	 *
	 * @param string $template Template file.
	 */
	public function __construct( $template = 'templates/sidebar.php' ) {
		$this->slug      = basename( $template, '.php' );
		$this->templates = array( $template );

		if ( self::$base ) {
			$str = substr( $template, 0, -4 );
			array_unshift( $this->templates, sprintf( $str . '-%s.php', self::$base ) );
		}
	}

	/**
	 * Allow the candidate list to be filtered, then resolve it.
	 *
	 * @return string
	 */
	public function __toString() {
		$this->templates = apply_filters( 'sage/wrap_' . $this->slug, $this->templates );

		return locate_template( $this->templates );
	}

	/**
	 * Filter hook for template_include: records the chosen template, then
	 * redirects rendering to wrapper.php.
	 *
	 * @param string $main Template filename supplied by WordPress.
	 * @return string
	 */
	// phpcs:ignore
	public static function wrap( $main ) {
		// Check for other filters returning null.
		if ( ! is_string( $main ) ) {
			return $main;
		}

		self::$main_template = $main;
		self::$base          = basename( self::$main_template, '.php' );

		if ( 'index' === self::$base ) {
			self::$base = false;
		}

		// Render through the theme's document wrapper rather than directly.
		$wrapper = new self( 'wrapper.php' );

		return (string) $wrapper;
	}
}

add_filter( 'template_include', array( __NAMESPACE__ . '\\Wrapper', 'wrap' ), 109 );
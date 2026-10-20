<?php
/**
 * Add a comment to show which template is being used on the current page.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

/**
 * Class ShowTemplate
 *
 * Displays the active WordPress template in the footer for debugging purposes.
 * Determines which template WordPress has chosen to use and outputs it as an HTML comment.
 *
 * @package BasicWP
 * @since 1.0.0
 */
class ShowTemplate {
	/**
	 * The template file path that WordPress has chosen to use.
	 *
	 * @var string|false
	 */
	private $template = false;

	/**
	 * Constructor for ShowTemplate class.
	 *
	 * Initializes the template checking functionality by hooking into template_redirect action.
	 * Does nothing if called from admin area.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( is_admin() ) {
			return;
		}

        add_action( 'template_redirect', array( &$this, 'checkTemplate' ), 0 );
	}

	/**
	 * Using the same logic used by WordPress determine the template to be used
	 *
	 * @since 1.0.0
	 */
	public function checkTemplate() {
		if ( is_404() ) {
			$template = get_404_template();

            if ( $template ) {
				$this->template = $template;
			}
		} elseif ( is_search() ) {
			$template = get_search_template();

            if ( $template ) {
				$this->template = $template;
			}
		} elseif ( is_tax() ) {
			$template = get_taxonomy_template();

            if ( $template ) {
				$this->template = $template;
			}
		} elseif ( is_home() ) {
			$template = get_home_template();

            if ( $template ) {
				$this->template = $template;
			}
		} elseif ( is_front_page() ) {
			$template = get_front_page_template();

            if ( $template ) {
				$this->template = $template;
			}
		} elseif ( is_attachment() ) {
			$template = get_attachment_template();

            if ( $template ) {
				$this->template = $template;
			}
		} elseif ( is_single() ) {
			$template = get_single_template();

            if ( $template ) {
				$this->template = $template;
			}
		} elseif ( is_page() ) {
			$template = get_page_template();

            if ( $template ) {
				$this->template = $template;
			}
		} elseif ( is_category() ) {
			$template = get_category_template();

            if ( $template ) {
				$this->template = $template;
			}
		} elseif ( is_tag() ) {
			$template = get_tag_template();

            if ( $template ) {
				$this->template = $template;
			}
		} elseif ( is_author() ) {
			$template = get_author_template();

            if ( $template ) {
				$this->template = $template;
			}
		} elseif ( is_date() ) {
			$template = get_date_template();

            if ( $template ) {
				$this->template = $template;
			}
		} elseif ( is_archive() ) {
			$template = get_archive_template();

            if ( $template ) {
				$this->template = $template;
			}
		} else {
			$this->template = function_exists( 'get_index_template' ) ? get_index_template() : get_template_directory() . '/index.php';
		}

        $this->template = apply_filters( 'template_include', $this->template );

        // Hook into the footer so we can echo the active template
		add_action( 'wp_footer', array( &$this, 'show_template' ), 100 );
	}

	/**
	 * Echo the active template to the footer
	 * Try to catch when a plugin or otherwise hooks template_redirect to include a different template
	 *
	 * @since 1.0.0
	 */
	public function show_template() {
		$fudge = false;

		foreach ( debug_backtrace() as $trace ) { // phpcs:ignore
			switch ( $trace['function'] ) {
				case 'wp_footer':
					$wp_footer = $trace['file'];
					break;
				case 'get_footer':
					$get_footer = $trace['file'];
					break;
			}
		}

		$fudge = isset( $get_footer ) ? $get_footer : $wp_footer;

		if ( $fudge === $this->template || $fudge === false ) {
			echo wp_kses_post( "<!-- Active Template: {$this->template} -->\n" );
		} else {
			echo esc_html( "<!--\n" );
			echo esc_html( "The template loader logic has chosen a different template than what was used.\n\n" );
			echo esc_html( "Chosen Template: {$this->template}\n" );
			echo esc_html( "Actual Template: $fudge\n\n" );
			echo esc_html( "This will usually occur if the template file was overriden using an action on template_redirect.\n" );
			echo esc_html( "This is a best effort guess to catch such scenarios as mentioned above but can be incorrect.\n" );
			echo esc_html( "-->\n" );
		}
	}
}

$ShowTemplate = new ShowTemplate();

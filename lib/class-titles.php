<?php
/**
 * Page Titles
 *
 * Ported from the original Roots/Sage `lib/titles.php`. The old theme's
 * page-header partial calls `Roots\Sage\Titles\title()`; this theme calls
 * `BasicWP\Titles\title()` from the equivalent place.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

/**
 * Returns the title appropriate for the current context.
 *
 * Mirrors the original Sage helper one-for-one so the page hero markup is
 * byte-identical to the previous theme.
 *
 * @return string
 */
function title() {
	if ( is_home() ) {
		if ( get_option( 'page_for_posts', true ) ) {
			return get_the_title( get_option( 'page_for_posts', true ) );
		}

		return __( 'Latest Posts', 'sage' );
	} elseif ( is_archive() ) {
		return get_the_archive_title();
	} elseif ( is_search() ) {
		return sprintf(
			/* translators: %s: search query */
			__( 'Search Results for %s', 'sage' ),
			get_search_query()
		);
	} elseif ( is_404() ) {
		return __( 'Not Found', 'sage' );
	}

	return get_the_title();
}
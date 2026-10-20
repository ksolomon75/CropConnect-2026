<?php
/**
 * Search features for BasicWP theme.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

/**
 * Modifies the WordPress query object for page search functionality.
 *
 * @param WP_Query $query The WordPress query object.
 *
 * @return void
 */
function pageSearch( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_search ) {
		$query->set( 'paged', ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1 );
		$query->set( 'posts_per_page', -1 );
	}
}

add_action( 'pre_get_posts', __NAMESPACE__ . '\\pageSearch' );

/**
 * Sort WP_Post objects in reverse order (most recent first).
 *
 * @param string $key WP_Post object property used for sorting. 'post_date' is assumed.
 *
 * @return int
 */
function postSort( $key ) {
	return function ( $a, $b ) use ( $key ) {
		if ( $a->$key < $b->$key ) {
			return 1;
		} elseif ( $a->$key > $b->$key ) {
			return -1;
		} else {
			// If first comparison is equal, use title as secondary sort key.
			return strnatcasecmp( $a->post_title, $b->post_title );
		}
	};
}

/**
 * Remove duplicate posts in combined list from default and secondary queries.
 *
 * @param array  $posts Array of WP_Post objects.
 * @param string $key   Search key used to identify duplicate objects. Post ID is used.
 *
 * @return array
 */
function dedupe( $posts, $key ) {
	$unique_posts = array();
	$ids          = array();

	foreach ( $posts as $post ) {
		if ( ! in_array( $post->$key, $ids, true ) ) {
			$ids[]          = $post->$key;
			$unique_posts[] = $post;
		}
	}

	return $unique_posts;
}

/**
 * Posts_results filter hook callback.
 *
 * @param array  $posts Array of WP_Post objects.
 * @param object $query WP_Query object from default search.
 *
 * @return array
 */
function searchResultFilter( $posts, $query ) {
	if ( \is_search() && $query->is_search() && ! \is_admin() ) {
		$args = array(
			'post_type'      => array( 'post', 'page' ),
			'posts_per_page' => -1,
			'tax_query'      => array(
				'relation' => 'OR', // Include both tags and categories.
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'name',
					'terms'    => $query->get( 's' ),
				),
				array(
					'taxonomy' => 'category',
					'field'    => 'name',
					'terms'    => $query->get( 's' ),
				),
			),
		);

		// Remove callback to avoid infinite loop.
		remove_filter( 'posts_results', __NAMESPACE__ . '\\searchResultFilter', 10 );

		$secondary_query = new \WP_Query( $args );

		$tagged_posts = $secondary_query->get_posts();

		// Combine default search results with secondary query.
		$all_posts = array_merge( $posts, $tagged_posts );

		// Remove duplicate posts.
		$unique_posts = dedupe( $all_posts, 'ID' );

		// Sort by reverse post_date order (most recent first).
		usort( $unique_posts, postSort( 'post_date' ) );

		// Restore posts_results callback.
		add_filter( 'posts_results', __NAMESPACE__ . '\\searchResultFilter', 10, 2 );

		return $unique_posts;
	} else {
		return $posts;
	}
}

add_filter( 'posts_results', __NAMESPACE__ . '\\searchResultFilter', 10, 2 );

<?php
namespace BasicWP;

/**
 * Class Breadcrumbs
 *
 * This class is responsible for generating and managing breadcrumb navigation
 * for the WordPress theme. Breadcrumbs provide a navigational aid that helps
 * users understand their location within the website's hierarchy.
 *
 * @package Basic-WP
 * @subpackage Breadcrumbs
 */
class Breadcrumbs {
	/**
	 * Generates the breadcrumb navigation for the website.
	 *
	 * This method is responsible for creating and returning the breadcrumb
	 * trail, which helps users navigate the website by showing their current
	 * location within the site's hierarchy.
	 *
	 * @return array An array of breadcrumb items, where each item is typically an
	 *               associative array containing 'title' and 'url' keys.
	 */
	public static function generate() {
		global $post;

		$breadcrumbs = array();

		// Always start with Home
		$breadcrumbs[] = self::getHomeBreadcrumb();

		if ( is_front_page() ) {
			// Front Page - do nothing
			return $breadcrumbs;
		} elseif ( is_home() ) {
			// Blog Index - add the Blog Posts Index page title
			$breadcrumbs[] = self::getBlogPostsIndexBreadcrumb();
		} elseif ( is_singular( 'post' ) ) {
			// Single Post - add the category and post title
			$breadcrumbs = array_merge( $breadcrumbs, self::getSinglePostBreadcrumbs() );
		} elseif ( is_singular() && ! is_page() ) {
			// Single Custom Post Type - add the post type archive and post title
			$breadcrumbs = array_merge( $breadcrumbs, self::getCustomPostTypeBreadcrumbs() );
		} elseif ( is_page() ) {
			// Static Page - add the parent pages if any, and the page title
			$breadcrumbs = array_merge( $breadcrumbs, self::getStaticPageBreadcrumbs( $post ) );
		} elseif ( is_category() || is_tag() || is_tax() ) {
			// Taxonomy Archive - add the taxonomy term name
			$breadcrumbs[] = self::getTaxonomyArchiveBreadcrumb();
		} elseif ( is_post_type_archive() ) {
			// Post Type Archive - add the post type name
			$breadcrumbs[] = self::getPostTypeArchiveBreadcrumb();
		} elseif ( is_day() ) {
			// Daily Archive - add the month and day links
			$breadcrumbs = array_merge( $breadcrumbs, self::getDateArchiveBreadcrumbs() );
		} elseif ( is_month() ) {
			// Monthly Archive - add the month link
			$breadcrumbs[] = self::getMonthArchiveBreadcrumb();
		} elseif ( is_year() ) {
			// Yearly Archive - add the year link
			$breadcrumbs[] = self::getYearArchiveBreadcrumb();
		} elseif ( is_author() ) {
			// Author Archive - add the author name
			$breadcrumbs[] = self::getAuthorArchiveBreadcrumb();
		} elseif ( is_search() ) {
			// Search Results - add the search query
			$breadcrumbs[] = self::getSearchBreadcrumb();
		} elseif ( is_404() ) {
			// 404 Not Found - add a 404 message
			$breadcrumbs[] = self::get404Breadcrumb();
		}

		return $breadcrumbs;
	}

	/** Generates the breadcrumb for the home page.
	 *
	 * This method is responsible for creating the breadcrumb
	 * link or label that represents the home page in the breadcrumb
	 * navigation trail.
	 *
	 * @return string The HTML or text representation of the home breadcrumb.
	 */
	private static function getHomeBreadcrumb() {
		return array(
			'url'   => home_url(),
			'label' => 'Home',
		);
	}

	/** Generates the breadcrumb for the blog posts index page.
	 *
	 * This method is responsible for creating a breadcrumb entry
	 * that represents the blog posts index page in the breadcrumb trail.
	 *
	 * @return array An associative array representing the breadcrumb for the blog posts index page.
	 */
	private static function getBlogPostsIndexBreadcrumb() {
		return array( 'label' => get_the_title( get_option( 'page_for_posts' ) ) );
	}

	/** Generates the breadcrumb trail for a single post.
	 *
	 * This method is responsible for creating the breadcrumb navigation
	 * specific to single post views. It typically includes links to the
	 * homepage, category (or categories) the post belongs to, and the
	 * post title itself.
	 *
	 * @return array An array representing the breadcrumb trail, where each
	 *               element is a breadcrumb item (e.g., URL and label).
	 */
	private static function getSinglePostBreadcrumbs() {
		$breadcrumbs = array();
		$categories  = get_the_category();

		$breadcrumbs[] = array(
			'url'   => get_permalink( get_option( 'page_for_posts' ) ),
			'label' => get_the_title( get_option( 'page_for_posts' ) ),
		);

		if ( ! empty( $categories ) ) {
			$cat           = $categories[0];
			$breadcrumbs[] = array(
				'url'   => get_category_link( $cat ),
				'label' => $cat->name,
			);
		}

		$breadcrumbs[] = array( 'label' => get_the_title() );
		return $breadcrumbs;
	}

	/** Generates breadcrumbs for custom post types.
	 *
	 * This method is responsible for creating breadcrumb navigation
	 * specific to custom post types. It retrieves and formats the
	 * breadcrumb trail based on the custom post type's hierarchy
	 * and structure.
	 *
	 * @return array An array representing the breadcrumb trail for the custom post type.
	 */
	private static function getCustomPostTypeBreadcrumbs() {
		$breadcrumbs = array();
		$postType    = get_post_type_object( get_post_type() );
		if ( $postType && ! in_array( get_post_type(), array( 'post', 'page' ), true ) ) {
			$breadcrumbs[] = array(
				'url'   => get_post_type_archive_link( $postType->name ),
				'label' => $postType->labels->name,
			);
		}
		$breadcrumbs[] = array( 'label' => get_the_title() );
		return $breadcrumbs;
	}

	/** Generates breadcrumbs for a static page.
	 *
	 * This method is responsible for creating breadcrumb navigation
	 * for static pages in a WordPress theme. It utilizes the provided
	 * post object to determine the breadcrumb structure.
	 *
	 * @param WP_Post $post The WordPress post object representing the static page.
	 * @return array An array of breadcrumb items, where each item is typically
	 *               an associative array containing 'title' and 'url' keys.
	 */
	private static function getStaticPageBreadcrumbs( $post ) {
		$breadcrumbs = array();
		if ( $post->post_parent ) {
			$ancestors = array_reverse( get_post_ancestors( $post->ID ) );
			foreach ( $ancestors as $ancestor ) {
				$breadcrumbs[] = array(
					'url'   => get_permalink( $ancestor ),
					'label' => get_the_title( $ancestor ),
				);
			}
		}
		$breadcrumbs[] = array( 'label' => get_the_title() );
		return $breadcrumbs;
	}

	/** Generates a breadcrumb for taxonomy archive pages.
	 *
	 * This method is responsible for creating breadcrumb navigation
	 * specific to taxonomy archive pages, providing users with a clear
	 * path to navigate back to the taxonomy or related sections.
	 *
	 * @return array An array representing the breadcrumb trail for the taxonomy archive.
	 */
	private static function getTaxonomyArchiveBreadcrumb() {
		$term = get_queried_object();
		return $term && isset( $term->name ) ? array( 'label' => $term->name ) : array();
	}

	/** Generates a breadcrumb for the archive page of a custom post type.
	 *
	 * This method is responsible for creating a breadcrumb link that points
	 * to the archive page of a specific custom post type. It is typically used
	 * in breadcrumb navigation to provide users with a way to navigate back
	 * to the archive page of the post type they are currently viewing.
	 *
	 * @return array An array containing the breadcrumb data for the custom post type archive.
	 */
	private static function getPostTypeArchiveBreadcrumb() {
		$postType = get_post_type_object( get_post_type() );
		return $postType ? array( 'label' => $postType->labels->name ) : array();
	}

	/** Generates breadcrumbs for date-based archives.
	 *
	 * This method is responsible for creating breadcrumb navigation
	 * for WordPress date archive pages, such as year, month, or day archives.
	 *
	 * @return array An array representing the breadcrumb trail for the date archive.
	 */
	private static function getDateArchiveBreadcrumbs() {
		return array(
			array(
				'url'   => get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) ),
				'label' => get_the_date( 'F Y' ),
			),
			array( 'label' => get_the_date() ),
		);
	}

	/** Generates a breadcrumb for a monthly archive page.
	 *
	 * This method is responsible for creating a breadcrumb
	 * specific to WordPress monthly archive pages. It typically
	 * includes the year and month as part of the breadcrumb trail.
	 *
	 * @return string The HTML markup for the monthly archive breadcrumb.
	 */
	private static function getMonthArchiveBreadcrumb() {
		return array( 'label' => get_the_date( 'F Y' ) );
	}

	/** Generates a breadcrumb for the year-based archive page.
	 *
	 * This method is responsible for creating a breadcrumb link
	 * specific to the year archive in a WordPress site. It is typically
	 * used to provide navigation for users when they are viewing posts
	 * filtered by a specific year.
	 *
	 * @return string The HTML markup for the year archive breadcrumb.
	 */
	private static function getYearArchiveBreadcrumb() {
		return array( 'label' => get_the_date( 'Y' ) );
	}

	/** Generates the breadcrumb for the author archive page.
	 *
	 * This method is responsible for creating a breadcrumb trail
	 * specific to the author archive, providing navigation context
	 * for pages that display posts by a particular author.
	 *
	 * @return string The HTML markup for the author archive breadcrumb.
	 */
	private static function getAuthorArchiveBreadcrumb() {
		$author = get_queried_object();
		return array( 'label' => 'Author: ' . $author->display_name );
	}

	/** Generates the breadcrumb for search results.
	 *
	 * This method is responsible for creating a breadcrumb trail
	 * specific to search result pages. It helps users navigate back
	 * to the search context or other parts of the site.
	 *
	 * @return string The HTML markup for the search breadcrumb.
	 */
	private static function getSearchBreadcrumb() {
		return array( 'label' => 'Search: ' . get_search_query() );
	}

	/** Generates the breadcrumb trail for a 404 error page.
	 *
	 * This method is responsible for creating a breadcrumb structure
	 * specifically for 404 error pages, providing users with a navigational
	 * context when a requested page is not found.
	 *
	 * @return array An array representing the breadcrumb trail for the 404 page.
	 */
	private static function get404Breadcrumb() {
		return array( 'label' => '404 Not Found' );
	}

	/** Renders the breadcrumb navigation.
	 *
	 * This method generates and outputs the breadcrumb trail for the current page.
	 *
	 * @return void
	 */
	public static function render() {
		$items = self::generate();

		$metadata = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => array(),
		);

		ob_start();
		?>

		<nav aria-label="Breadcrumbs" class="flex items-center py-2 -mx-2 leading-none" vocab="https://schema.org/" typeof="BreadcrumbList">
			<?php foreach ( $items as $index => $item ) : ?>
				<?php
				$ldItem = array(
					'@type'    => 'ListItem',
					'position' => $index + 1,
					'name'     => htmlspecialchars( $item['label'], ENT_QUOTES, 'UTF-8' ),
				);

				$isActive    = ( $index === count( $items ) - 1 );
				$activeClass = $isActive ? ' active' : '';
				?>
			<span class="p-2 breadcrumb-item<?php echo esc_attr( $activeClass ); ?>"
				<?php
				if ( $isActive ) :
					?>
				aria-current="page"<?php endif; ?>>
				<?php if ( ! empty( $item['url'] ) ) : ?>
				<a href="<?php echo esc_url( $item['url'] ); ?>">
					<?php if ( $index === 0 ) : ?>
					<svg class="flex-shrink-0 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" aria-label="Home">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
					</svg>
					<span class="sr-only"><?php echo esc_attr( $item['label'] ); ?></span>
				<?php else : ?>
					<?php echo esc_attr( $item['label'] ); ?>
				<?php endif; ?>
				</a>
					<?php $ldItem['item'] = $item['url']; ?>
				<?php else : ?>
					<?php echo esc_attr( $item['label'] ); ?>
				<?php endif; ?>
			</span>

				<?php $metadata['itemListElement'][] = $ldItem; ?>

				<?php if ( ! $isActive ) : ?>
				<svg class="flex-shrink-0 w-5 h-5 text-light" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
				</svg>
			<?php endif; ?>
			<?php endforeach; ?>
		</nav>

		<script type="application/ld+json"><?php echo esc_js( wp_json_encode( $metadata ) ); ?></script>

		<?php
		echo wp_kses(
			ob_get_clean(),
			array(
				'a'      => array(
					'class'        => array(),
					'href'         => array(),
					'title'        => array(),
					'rel'          => array(),
					'target'       => array(),
					'aria-label'   => array(),
					'aria-current' => array(),
				),
				'nav'    => array(
					'aria-label' => array(),
					'class'      => array(),
					'vocab'      => array(),
					'typeof'     => array(),
				),
				'span'   => array(
					'class'        => array(),
					'aria-current' => array(),
				),
				'svg'    => array(
					'class'       => array(),
					'xmlns'       => array(),
					'viewBox'     => array(),
					'fill'        => array(),
					'aria-hidden' => array(),
					'aria-label'  => array(),
				),
				'path'   => array(
					'd'           => array(),
					'fill-rule'   => array(),
					'clip-rule'   => array(),
					'fill'        => array(),
					'xmlns:xlink' => array(),
				),
				'script' => array(
					'type' => array(),
					'src'  => array(),
				),
			)
		);
	}
}

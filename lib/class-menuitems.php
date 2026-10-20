<?php
/**
 * BasicWP MenuItems Class
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

/**
 * Class MenuItems
 *
 * This class is responsible for managing menu items within the Basic-WP theme.
 *
 * @package Basic-WP
 * @since 1.0.0
 */
class MenuItems {
	/**
	 * Accepts menu name to populate navigation items.
	 *
	 * @var string $displayLocation
	 */
	protected $displayLocation;

	/**
	 * First level menu items (menu_item_parent = 0).
	 *
	 * @var array $topLevelNavItems
	 */
	public $topLevelNavItems;

	/**
	 * True if nav item has children items.
	 *
	 * @var bool $hasChildren
	 */
	public $hasChildren;

	/**
	 * Children navigation items.
	 *
	 * @var array $nestedNavItems
	 */
	public $nestedNavItems;

	/**
	 * True if the current page is the same as the menu item.
	 *
	 * @var bool $currentPage
	 */
	public $currentPage;

	/**
	 * Index of children keyed by parent menu_item_parent string.
	 *
	 * Built once per instance from getNavItems(), so hasChildren() and
	 * getNestedNavItems() become O(1) / O(k) lookups instead of full scans.
	 *
	 * @var array $childrenByParent
	 */
	public $childrenByParent;

	/**
	 * Per-request memoization of getNavItems() keyed by menu ID.
	 *
	 * Avoids repeated wp_get_nav_menu_items() calls when more than one
	 * MenuItems instance is constructed for the same display location
	 * (e.g. desktop + mobile in header.php).
	 *
	 * @var array $navItemsCache
	 */
	protected static $navItemsCache = array();

	/**
	 * Constructor method for initializing the class with a specific menu name.
	 *
	 * @param string $menuName The name of the menu to be used. Defaults to 'main_navigation'.
	 */
	public function __construct( $menuName = 'main_navigation' ) {
		$this->displayLocation = $menuName;

		$this->childrenByParent = $this->buildChildrenIndex();
		$this->topLevelNavItems = $this->getTopLevelNavItems();
		$this->hasChildren      = function ( $item ) {
			return $this->hasChildren( $item );
		};
		$this->nestedNavItems   = function ( $parentItem ) {
			return $this->getNestedNavItems( $parentItem );
		};
		$this->currentPage      = function ( $item ) {
			return $this->currentPage( $item );
		};
	}

	/**
	 * Retrieves the list of menus.
	 *
	 * @return array An array containing menu items.
	 */
	public function getMenus() {
		$menus = get_nav_menu_locations();
		return empty( $menus ) ? array() : $menus;
	}

	/**
	 * Retrieves the navigation items.
	 *
	 * Per-request memoized via the static $navItemsCache, so repeated
	 * calls (or repeated MenuItems instances for the same display
	 * location) only fetch the menu items once.
	 *
	 * @return array An array of navigation items.
	 */
	public function getNavItems() {
		$locations = $this->getMenus();

		// If the menu location doesn't exist, return empty array
		if ( ! isset( $locations[ $this->displayLocation ] ) ) {
			return array();
		}

		// Get the menu ID for this location
		$menuId = $locations[ $this->displayLocation ];

		if ( ! isset( self::$navItemsCache[ $menuId ] ) ) {
			// Direct call to wp_get_nav_menu_items with the menu ID
			$items = wp_get_nav_menu_items( $menuId );

			// Return empty array if no items
			self::$navItemsCache[ $menuId ] = is_array( $items ) ? $items : array();
		}

		return self::$navItemsCache[ $menuId ];
	}

	/**
	 * Build a parent-id => [child_items] index in a single pass.
	 *
	 * The '0' group holds top-level items. Templates consume
	 * $childrenByParent[(string) $item->ID] to look up children of
	 * any given item.
	 *
	 * @return array
	 */
	protected function buildChildrenIndex() {
		$index = array();
		foreach ( $this->getNavItems() as $item ) {
			$index[ (string) $item->menu_item_parent ][] = $item;
		}
		return $index;
	}

	/**
	 * Checks if the given parent item has child items.
	 *
	 * O(1) lookup against the prebuilt children index.
	 *
	 * @param mixed $parentItem The parent item to check for children.
	 * @return bool Returns true if the parent item has children, false otherwise.
	 */
	public function hasChildren( $parentItem ) {
		return ! empty( $this->childrenByParent[ (string) $parentItem->ID ] );
	}

	/**
	 * Retrieves the top-level navigation items.
	 *
	 * @return array An array of top-level navigation items.
	 */
	public function getTopLevelNavItems() {
		return isset( $this->childrenByParent['0'] ) ? $this->childrenByParent['0'] : array();
	}

	/**
	 * Retrieves the nested navigation items for a given parent item.
	 *
	 * O(k) lookup against the prebuilt children index (k = number of
	 * children of the given parent).
	 *
	 * @param mixed $parentItem The parent navigation item for which nested items are to be retrieved.
	 * @return array The list of nested navigation items.
	 */
	public function getNestedNavItems( $parentItem ) {
		return isset( $this->childrenByParent[ (string) $parentItem->ID ] )
			? $this->childrenByParent[ (string) $parentItem->ID ]
			: array();
	}

	/**
	 * Determines the current page based on the provided item.
	 *
	 * @param mixed $item The item used to determine the current page.
	 * @return mixed The current page information.
	 */
	public function currentPage( $item ) {
		global $wp;
		return ( $item->url === home_url( $wp->request ) . '/' ) ? 'true' : 'false';
	}

	/**
	 * Renders the output for the current context.
	 *
	 * This method is responsible for generating and returning the
	 * appropriate output for the current context or request.
	 *
	 * @return void
	 */
	public function render() {
		global $views;

		// Extract class properties to local variables
		$location         = $this->displayLocation;
		$topLevelNavItems = $this->topLevelNavItems;
		$hasChildren      = $this->hasChildren;
		$nestedNavItems   = $this->nestedNavItems;
		$currentPage      = $this->currentPage;

		include $views . '/components/menu-items/index.php';
	}
}

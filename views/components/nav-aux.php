<?php
/**
 * Main Navigation - Auxiliary
 *
 * Please review documentation upon first use, and, as-needed:
 * https://docs.vincentdevelopment.ca/docs/starter-v3-enhancements/navigation-aux.html
 */

namespace BasicWP;

// Init Variables
global $wp, $views;

$menus = get_nav_menu_locations();

if ( isset( $menus['aux_navigation'] ) ) {
	$navItems = wp_get_nav_menu_items( $menus['aux_navigation'] );
}

$currentPageURL = home_url( $wp->request ) . '/';
?>

<div class="nav-aux__container hidden lg:block bg-dark w-full h-12 py-2">
	<div class="container flex items-center justify-end gap-x-4 gap-y-0 flex-wrap">
		<nav class="nav-aux" role="navigation" aria-label="Auxiliary">
			<?php
			if ( has_nav_menu( 'aux_navigation' ) ) {
				// Initialize and render menu items
				$menuItems = new MenuItems( 'aux_navigation' );
				$menuItems->render();
			}
			?>
		</nav>

		<?php get_template_part( 'views/partials/social-media' ); ?>

		<?php get_template_part( 'views/forms/search' ); ?>
	</div>
</div>

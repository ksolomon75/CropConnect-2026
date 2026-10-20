<?php
/**
 * Main Navigation Component
 *
 * Please review documentation upon first use, and, as-needed:
 * https://docs.vincentdevelopment.ca/docs/starter-v3-enhancements/navigation/
 */

namespace BasicWP;

global $views;
?>

<nav class="nav-main" role="navigation" aria-label="Main">
	<?php require_once __DIR__ . '/nav-main__toggle.php'; ?>

	<?php
	if ( has_nav_menu( 'main_navigation' ) ) {
		// Initialize and render menu items
		$menuItems = new MenuItems( 'main_navigation' );
		$menuItems->render();
	}
	?>
</nav>

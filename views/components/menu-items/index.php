<?php
/**
 * Main Navigation - Menu Items outer list
 *
 * Please review documentation upon first use, and, as-needed:
 * https://docs.vincentdevelopment.ca/docs/starter-v3-enhancements/navigation/
 */

namespace BasicWP;

// Variables available from MenuItems component:
// $topLevelNavItems, $hasChildren, $nestedNavItems, $currentPage, $location

if ( $location === 'main_navigation' ) {
	$menuID = 'menu-container';
} else {
	$menuID = 'menu-container-aux';
}
?>

<ul id="<?php echo esc_attr( $menuID ); ?>" class="menu-vdi">
	<li class="menu-item menu-item--search px-4 lg:hidden">
		<?php get_template_part( 'views/forms/search' ); ?>
	</li>

	<?php foreach ( $topLevelNavItems as $item ) : ?>
		<?php $itemHasChildren = $hasChildren( $item ); ?>
		<?php if ( $itemHasChildren ) : ?>
			<?php include __DIR__ . '/has-children.php'; ?>
		<?php else : ?>
			<?php include __DIR__ . '/single.php'; ?>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>

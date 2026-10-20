<?php
/**
 * Main Navigation - Menu Item with children
 *
 * Please review documentation upon first use, and, as-needed:
 * https://docs.vincentdevelopment.ca/docs/starter-v3-enhancements/navigation/
 */

namespace BasicWP;

// Variables available:
// $item from parent template
// $nestedNavItems, $hasChildren, $currentPage from MenuItems component

// Generate item classes
$itemClasses = array(
	'menu-vdi__item',
	'menu-vdi__item--parent',
	'menu-vdi__item--' . str_replace( ' ', ' -', strtolower( $item->title ) ),
);
if ( ! empty( $item->classes ) ) {
	$itemClasses = array_merge( $itemClasses, $item->classes );
}

// Generate toggle classes
$toggleClasses = array( 'menu-vdi__toggle' );
if ( ! empty( $item->classes ) ) {
	$toggleClasses = array_merge( $toggleClasses, $item->classes );
}
?>

<li id="menuVdiItem<?php echo esc_attr( $item->ID ); ?>" class="<?php echo esc_attr( implode( ' ', $itemClasses ) ); ?>">
	<button aria-expanded="false" class="<?php echo esc_attr( implode( ' ', $toggleClasses ) ); ?>">
	<?php echo esc_html( $item->title ); ?>
	<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
		<path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
	</svg>
	</button>

	<ul class="menu-vdi__submenu">
	<?php foreach ( $nestedNavItems( $item ) as $child ) : ?>
		<?php
		$childHasChildren    = $hasChildren( $child );
		$childNestedNavItems = $childHasChildren ? $nestedNavItems( $child ) : array();
		?>
		<?php if ( $childHasChildren ) : ?>
		<li id="menuVdiItem<?php echo esc_attr( $child->ID ); ?>">
			<span class="menu-vdi__item menu-vdi__item--child <?php echo ! empty( $item->classes ) ? esc_attr( implode( ' ', $item->classes ) ) : ''; ?>">
			<?php echo esc_html( $child->title ); ?>
			</span>

			<ul>
			<?php foreach ( $childNestedNavItems as $grandChild ) : ?>
				<li id="menuVdiItem<?php echo esc_attr( $grandChild->ID ); ?>" class="menu-vdi__item menu-vdi__item--grandchild <?php echo ! empty( $item->classes ) ? esc_attr( implode( ' ', $item->classes ) ) : ''; ?>">
				<a href="<?php echo esc_url( $grandChild->url ); ?>" aria-current="<?php echo esc_attr( $currentPage( $grandChild ) ); ?>"
					<?php echo isset( $grandChild->target ) ? esc_attr( "target='$grandChild->target'" ) : ''; ?> class="sub-menu-item">
					<?php echo esc_html( $grandChild->title ); ?>
				</a>
				</li>
			<?php endforeach; ?>
			</ul>
		</li>
		<?php else : ?>
		<li id="menuVdiItem<?php echo esc_attr( $child->ID ); ?>" class="menu-vdi__item menu-vdi__item--child <?php echo ! empty( $item->classes ) ? esc_attr( implode( ' ', $item->classes ) ) : ''; ?>">
			<a href="<?php echo esc_url( $child->url ); ?>" class="sub-menu-item">
			<?php echo esc_html( $child->title ); ?>
			</a>
		</li>
		<?php endif; ?>
	<?php endforeach; ?>
	</ul>
</li>

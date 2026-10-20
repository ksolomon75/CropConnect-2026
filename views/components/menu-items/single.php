<?php
/**
 * Main Navigation - Menu Item without children
 *
 * Please review documentation upon first use, and, as-needed:
 * https://docs.vincentdevelopment.ca/docs/starter-v3-enhancements/navigation/
 */

namespace BasicWP;

// Variables available:
// $item from parent template
// $currentPage from MenuItems component

// Generate classes
$itemClasses = array(
	'menu-vdi__item',
	'menu-vdi__item--single',
	'menu-vdi__item--' . str_replace( ' ', '-', strtolower( $item->title ) ),
);

if ( ! empty( $item->classes ) ) {
	$itemClasses = array_merge( $itemClasses, $item->classes );
}
?>

<li id="menuVdiItem<?php echo esc_attr( $item->ID ); ?>" class="<?php echo esc_attr( implode( ' ', $itemClasses ) ); ?>">
	<a href="<?php echo esc_url( $item->url ); ?>" aria-current="<?php echo esc_attr( $currentPage( $item ) ); ?>"
	<?php echo ! empty( $item->target ) ? esc_attr( "target='$item->target'" ) : ''; ?> class="menu-vdi__link">
	<?php echo esc_html( $item->title ); ?>
	</a>
</li>

<?php
/**
 * Block Name: Grid
 *
 * This is the template that displays the grid block.
 *
 * @package BasicWP
 */

namespace BasicWP;

$allowedBlocks  = array( 'acf/grid-cell' );
$default_blocks = array(
	array( 'acf/gric-cell' ),
);

// Initialize variables
$anchor    = ! empty( $block['anchor'] ) ? $block['anchor'] : 'grid-' . $block['id'];
$className = ! empty( $block['className'] ) ? $block['className'] : '';
$colBPs    = get_field( 'columns_breakpoints' );
$gapX      = get_field( 'gap_x' );
$gapY      = get_field( 'gap_y' );

$gridClasses = 'grid grid-cols-' . get_field( 'columns' );

// Add breakpoint-specific column classes
if ( $colBPs ) {
	foreach ( $colBPs as $bp ) {
		$gridClasses .= ' ' . $bp . ':grid-cols-' . get_field( 'columns_' . $bp );
	}
}

// Add gap classes
if ( $gapX ) {
	$gridClasses .= ' gap-x-' . $gapX;
}
if ( $gapY ) {
	$gridClasses .= ' gap-y-' . $gapY;
}

// Combine all classes
$classes = trim( $className . ' ' . $gridClasses );
?>

<div id="<?php echo esc_attr( $anchor ); ?>">
	<InnerBlocks className="<?php echo esc_attr( $classes ); ?>" />
</div>

<?php
/**
 * Block Name: Grid Cell
 *
 * This is the template that displays the Grid Cell block.
 *
 * @package BasicWP
 */

namespace BasicWP;

// Initialize variables
$className = ! empty( $block['className'] ) ? $block['className'] : '';

$span    = get_field( 'col_span' );
$spanBPs = get_field( 'col_span_breakpoints' );

$className .= $className . ' grid-cell px-4 py-2';

$gridClasses = '';

// Add column span classes
if ( $span && $span !== 'auto' ) {
	$gridClasses .= ' col-span-' . $span;
}

// Add breakpoint-specific column span classes
if ( $spanBPs ) {
	foreach ( $spanBPs as $bp ) {
		$gridClasses .= ' ' . $bp . ':col-span-' . get_field( 'col_span_' . $bp );
	}
}

// Combine all classes
$className .= ' ' . trim( $gridClasses );

if ( ! $is_preview ) {
	$wrapper = get_block_wrapper_attributes(
		array( 'class' => trim( $className ) )
	);
} else {
	$wrapper = '';
}
?>

<div <?php echo wp_kses_post( $wrapper ); ?>>
	<InnerBlocks className="" />
</div>

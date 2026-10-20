<?php
/**
 * Block Name: Buttons
 *
 * This is the template that renders the Buttons block.
 *
 * @package BasicWP
 */

namespace BasicWP;

$ibClasses = 'flex flex-wrap gap-4 w-full justify-center sm:justify-start';

$classes = 'align-with-content my-[1.2em]';
$wrapper = blockWrapperAttributes( $classes, $is_preview );
?>

<div id="<?php echo esc_attr( $block['id'] ); ?>" <?php echo esc_attr( $wrapper ); ?>>
	<InnerBlocks className="<?php echo esc_attr( $ibClasses ); ?>" />
</div>

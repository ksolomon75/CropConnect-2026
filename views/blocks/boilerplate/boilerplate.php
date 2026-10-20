<?php
/**
 * Block Name: Boilerplate
 *
 * This is the template for building your own custom blocks.
 *
 * @package BasicWP
 */

namespace BasicWP;

$classes = 'boilerplate';

/**
 * NOTE: DO NOT remove this function call - it is required to avoid editor issues.
 * $is_preview is a WordPress global when in the editor.
 */
$wrapper = blockWrapperAttributes( $classes, $is_preview );
?>

<section <?php echo wp_kses_post( $wrapper ); ?>>
	<!-- Your block code will go here -->
</section>

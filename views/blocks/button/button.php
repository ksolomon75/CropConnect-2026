<?php
/**
 * Button block
 *
 * @package BasicWP
 */

namespace BasicWP;

// Retrieve ACF fields
$element  = get_field( 'element' ) ? get_field( 'element' ) : 'a';
$btnLink  = get_field( 'link' );
$url      = isset( $btnLink['url'] ) ? $btnLink['url'] : '';
$target   = isset( $btnLink['target'] ) ? $btnLink['target'] : '';
$btnTitle = isset( $btnLink['title'] ) ? $btnLink['title'] : 'Button';
$btnAria  = get_field( 'aria_label' ) ? get_field( 'aria_label' ) : '';
$color    = get_field( 'color' );
$variant  = get_field( 'variant' );
$size     = get_field( 'size' );
$width    = get_field( 'width' );

// Handle admin preview
if ( is_admin() && $url ) {
	$url = '#';
}

// Set wrapper classes
$classes = 'button text-center';

if ( ! $is_preview ) {
	$wrapper = get_block_wrapper_attributes(
		array( 'class' => $classes )
	);

	$wrapper = str_replace( 'class="', '', $wrapper );
	$wrapper = str_replace( '"', '', $wrapper );
} else {
	$wrapper = $classes;
}
?>

<x-button
	btnClasses="<?php echo esc_attr( $wrapper ); ?>"
	element="<?php echo esc_attr( $element ); ?>"
	url="<?php echo esc_url( $url ); ?>"
	target="<?php echo esc_attr( $target ); ?>"
	title="<?php echo esc_attr( $btnTitle ); ?>"
	ariaLabel="<?php echo esc_attr( $btnAria ); ?>"
	color="<?php echo esc_attr( $color ); ?>"
	variant="<?php echo esc_attr( $variant ); ?>"
	size="<?php echo esc_attr( $size ); ?>"
	width="<?php echo esc_attr( $width ); ?>"
></x-button>

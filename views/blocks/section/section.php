<?php
/**
 * Block Name: Section
 *
 * This is the template that displays the section block.
 *
 * @package BasicWP
 */

namespace BasicWP;

// Retrieve ACF fields
$contentWidth = get_field( 'content_width' );
$isDark       = get_field( 'is_dark' );
$fontColor    = get_field( 'font_color' );
$bgColor      = get_field( 'background_color' );
$bgImage      = get_field( 'background_image' );
$ovlColor     = get_field( 'overlay_color' );
$ovlImage     = get_field( 'overlay_image' );
$ovlOpacity   = get_field( 'overlay_opacity' ) ? get_field( 'overlay_opacity' ) / 100 : 1;

// Set classes
$classes = 'section';

if ( $contentWidth === 'full' ) {
	$classes .= ' mx-break-out';
}
if ( $isDark ) {
	$classes .= ' dark text-light';
}
if ( $bgColor || $bgImage ) {
	$classes .= ' has-background bg-no-repeat';
}
if ( $ovlColor || $ovlImage ) {
	$classes .= ' has-overlay';
}
if ( $fontColor ) {
	$classes .= ' has-font-color';
}

// Set styles
$styles = '';

if ( $bgColor ) {
	$styles .= "background-color: $bgColor;";
}
if ( $bgImage ) {
	$styles .= ' background-image: url(' . esc_url( $bgImage['url'] ) . ');';
}
if ( $fontColor ) {
	$styles .= " color: $fontColor;";
}

// Set overlay styles
$overlayStyles = '';

if ( $ovlColor ) {
	$overlayStyles .= "background-color: $ovlColor;";
}
if ( $ovlImage ) {
	$overlayStyles .= ' background-image: url(' . esc_url( $ovlImage['url'] ) . ');';
}

$overlayStyles .= " opacity: $ovlOpacity;";

// Set wrapper attributes
$wrapper = blockWrapperAttributes( $classes, $is_preview );
?>

<section <?php echo wp_kses_post( $wrapper ); ?> style="<?php echo esc_attr( $styles ); ?>">
	<?php if ( $ovlColor || $ovlImage ) : ?>
		<div aria-hidden="true" class="section-overlay absolute inset-0 bg-center bg-cover bg-no-repeat" style="<?php echo esc_attr( $overlayStyles ); ?>"></div>
	<?php endif; ?>

	<?php if ( $contentWidth === 'full' ) : ?>
		<InnerBlocks />
	<?php else : ?>
		<div class="container content-wrapper">
			<InnerBlocks />
		</div>
	<?php endif; ?>
</section>

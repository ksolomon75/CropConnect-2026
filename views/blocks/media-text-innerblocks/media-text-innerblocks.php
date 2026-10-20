<?php
/**
 * Block Name: Media Text Inner Blocks
 *
 * This is the template that displays the media text inner blocks.
 *
 * @package BasicWP
 */

namespace BasicWP;

// Retrieve ACF fields
$bgColor   = get_field( 'background_color' ) ? get_field( 'background_color' ) : '#c5c5c5';
$isDark    = get_field( 'is_dark' ) ? get_field( 'is_dark' ) : false;
$mediaLeft = get_field( 'media_on_left' ) ? get_field( 'media_on_left' ) : false;
$vidBlock  = get_field( 'video' ) ? get_field( 'video' ) : '';
$imgBlock  = get_field( 'image' ) ? get_field( 'image' ) : array(
	'url' => '',
	'alt' => '',
);

// Set classes and styles
$style      = "background-color: $bgColor;";
$clsMedia   = $mediaLeft ? 'order-first' : 'order-last';
$clsContent = $mediaLeft ? 'order-last pr-8' : 'order-first pl-8';
$classes    = 'media-cols grid grid-cols-1 lg:grid-cols-2 gap-8 items-center mb-8 p-0';

if ( $isDark ) {
	$classes .= ' dark text-light';
}

// Set wrapper attributes
$wrapper = blockWrapperAttributes( $classes, $is_preview );

// if the video block contains an iframe, add the role="presentation" attribute
if ( $vidBlock && strpos( $vidBlock, '<iframe' ) !== false ) {
	$vidBlock = str_replace( '<iframe', '<iframe role="presentation"', $vidBlock );
}
?>

<div <?php echo wp_kses_post( $wrapper ); ?> style="<?php echo esc_attr( $style ); ?>">
	<div class="<?php echo esc_attr( $mediaClass ); ?> <?php echo $vidBlock ? 'aspect-video' : ''; ?>">
	<?php if ( $vidBlock ) : ?>
		<?php echo wp_kses( $vidBlock, escEmbeds() ); ?>
	<?php else : ?>
		<img src="<?php echo esc_url( $imgBlock['url'] ); ?>" alt="<?php echo esc_attr( $imgBlock['alt'] ); ?>" class="">
	<?php endif; ?>
	</div>

	<div class="content-wrapper text-balance <?php echo esc_attr( $contentClass ); ?>">
		<InnerBlocks />
	</div>
</div>

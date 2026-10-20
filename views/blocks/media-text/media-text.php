<?php
/**
 * Block Name: Media With Text
 *
 * This is the template that displays the Media With Text block.
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
$heading   = get_field( 'heading' ) ? get_field( 'heading' ) : '';
$subHead   = get_field( 'subheading' ) ? get_field( 'subheading' ) : '';
$content   = get_field( 'content' ) ? get_field( 'content' ) : '';
$ctas      = get_field( 'calls_to_action' ) ? get_field( 'calls_to_action' ) : array();

// Set classes and styles
$style        = "background-color: $bgColor;";
$mediaClass   = $mediaLeft ? 'order-first' : 'order-last';
$contentClass = $mediaLeft ? 'order-last pr-8' : 'order-first pl-8';
$classes      = 'media-cols grid grid-cols-1 lg:grid-cols-2 gap-8 items-center mb-8 p-0';

if ( $isDark ) {
	$classes .= ' dark text-white';
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
	<?php if ( ! empty( $heading ) ) : ?>
		<h2 class=""><?php echo esc_html( $heading ); ?></h2>
	<?php endif; ?>

	<?php if ( ! empty( $subHeading ) ) : ?>
		<h3 class=""><?php echo esc_html( $subHeading ); ?></h3>
	<?php endif; ?>

	<?php if ( ! empty( $content ) ) : ?>
		<div><?php echo wp_kses_post( $content ); ?></div>
	<?php endif; ?>

	<?php if ( ! empty( $ctas ) ) : ?>
		<div class="reset flex flex-wrap justify-center lg:justify-start gap-2">
		<?php foreach ( $ctas as $cta ) : ?>
			<?php
			$ctaLink    = $cta['link'];
			$ctaUrl     = $ctaLink['url'] ?? '#';
			$ctaTarget  = $ctaLink['target'] ?? '_self';
			$ctaTitle   = $ctaLink['title'] ?? '';
			$ctaColor   = $cta['color'] ?? '';
			$ctaVariant = $cta['variant'] ?? '';
			$ctaSize    = $cta['size'] ?? '';
			$ctaWidth   = $cta['width'] ?? '';

					// Handle admin preview
			if ( is_admin() && $ctaURL ) {
				$ctaURL = '#';
			}
			?>
					<x-button
						btnclasses="button text-center"
						element="a"
						url="<?php echo esc_url( $ctaURL ); ?>"
						target="<?php echo esc_attr( $ctaTarget ); ?>"
						title="<?php echo esc_attr( $ctaTitle ); ?>"
						color="<?php echo esc_attr( $ctaColor ); ?>"
						variant="<?php echo esc_attr( $ctaVariant ); ?>"
						size="<?php echo esc_attr( $ctaSize ); ?>"
						width="<?php echo esc_attr( $ctaWidth ); ?>"
					></x-button>
		<?php endforeach; ?>
		</div>
	<?php endif; ?>
	</div>
</div>

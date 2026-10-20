<?php
/**
 * Block Name: Homepage Hero
 *
 * @package BasicWP
 */

namespace BasicWP;

// Retrieve ACF fields
$heading = get_field( 'heading' );
$intro   = get_field( 'intro' );
$ctas    = get_field( 'calls_to_action' );

$classes = 'homepage-hero mx-break-out bg-black bg-cover bg-no-repeat text-light py-12 lg:py-16 overflow-hidden';
$wrapper = blockWrapperAttributes( $classes, $is_preview );
?>

<section <?php echo wp_kses_post( $wrapper ); ?>>
	<div class="container content-wrapper">
	<div class="max-w-lg sm:text-center lg:text-left lg:items-center ml-0">
		<?php if ( ! empty( $heading ) ) : ?>
		<h1 class="text-4xl lg:text-5xl font-bold leading-tight mb-4">
			<?php echo esc_html( $heading ); ?>
		</h1>
		<?php endif; ?>

		<?php if ( ! empty( $intro ) ) : ?>
		<div class="mt-3 text-base text-light sm:mt-5 sm:text-xl lg:text-lg xl:text-xl with:max-w-full">
			<?php echo wp_kses_post( $intro ); ?>
		</div>
		<?php endif; ?>

		<?php if ( ! empty( $ctas ) ) : ?>
		<div class="reset mt-4 sm:mt-6 flex flex-wrap justify-center lg:justify-start gap-2">
			<?php foreach ( $ctas as $index => $cta ) : ?>
				<?php
				$ctaLink   = $cta['link'];
				$ctaURL    = $ctaLink['url'] ?? '#';
				$ctaTarget = $ctaLink['target'] ?? '_self';
				$ctaTitle  = $ctaLink['title'] ?? '';

				// Handle admin preview
				if ( is_admin() && $ctaURL ) {
					$ctaURL = '#';
				}
				?>
				<x-button
					btnclasses="button text-center min-w-32"
					element="a"
					url="<?php echo esc_url( $ctaURL ); ?>"
					target="<?php echo esc_attr( $ctaTarget ); ?>"
					title="<?php echo esc_attr( $ctaTitle ); ?>"
					<?php if ( $index === 0 ) { ?>
						color="primary"
					<?php } else { ?>
						color="primary" variant="outline"
					<?php } ?>
					width="small"
				></x-button>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
	</div>
</section>

<?php
/**
 * Page Hero Partial
 *
 * @package BasicWP
 */

namespace BasicWP;

// Set variables
$bgColor = get_field( 'background_color' );
$isDark  = get_field( 'is_dark' );
$heading = get_field( 'heading' );
$intro   = get_field( 'intro' );

// Fallback for heading
if ( ! $heading ) {
	$heading = getTheTitle();
}

// Additional logic for dark mode (if needed)
if ( is_home() || is_single() || is_archive() || is_search() || is_404() ) {
	$isDark = true;
}
?>

<div class="bg-cover bg-no-repeat mb-12 py-12 lg:py-16 bg-dark text-light overflow-hidden <?php echo $isDark ? 'dark' : ''; ?>" <?php echo $bgColor ? 'style="background-color: ' . esc_attr( $bgColor ) . '"' : ''; ?>>

	<div class="container mx-auto">
		<div id="breadcrumbs">
			<?php Breadcrumbs::render(); ?>
		</div>

		<div class="sm:text-center lg:items-start lg:text-left content-wrapper">
			<?php
			// Heading
			if ( apply_filters( 'include_page_title_in_hero', true ) ) {
				echo '<h1 class="mx-auto text-center text-light font-normal text-4xl sm:text-5xl lg:text-6xl xl:text-7xl">';
				echo wp_kses_post( $heading );
				echo '</h1>';
			} else {
				echo '<span class="mx-auto block text-center text-light font-normal text-4xl sm:text-5xl lg:text-6xl xl:text-7xl">';
				echo wp_kses_post( $heading );
				echo '</span>';
			}

			// Intro
			if ( $intro ) {
				echo '<p class="mt-3 text-base text-light sm:mt-5 sm:text-xl lg:text-lg xl:text-xl text-center">';
				echo wp_kses_post( $intro );
				echo '</p>';
			}
			?>
		</div>
	</div>
</div>

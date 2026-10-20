<?php
/**
 * Main Navigation - Toggle Button
 *
 * Please review documentation upon first use, and, as-needed:
 * https://docs.vincentdevelopment.ca/docs/starter-v3-enhancements/navigation/
 */

namespace BasicWP;

// Init Variables
$navIcon = isset( get_field( 'header', 'option' )['nav_icon'] ) ? get_field( 'header', 'option' )['nav_icon'] : '';

// Generate toggle classes
$toggleClasses = array( 'nav-main__toggle' );
if ( $navIcon ) {
	$toggleClasses[] = 'custom-icon';
}
?>

<button
	id="navMainToggle"
	aria-label="Menu"
	aria-expanded="false"
	class="<?php echo esc_attr( implode( ' ', $toggleClasses ) ); ?>"
>
	<?php if ( $navIcon ) : ?>
		<?php echo wp_get_attachment_image( $navIcon, 'full' ); ?>
	<?php else : ?>
		<!-- Hamburger Icon -->
		<span class="nav-toggle-icon nav-toggle-hamburger">
			<svg class="text-black fill-black" viewBox="0 0 100 80" width="40" height="40">
				<rect y="0"  width="100" height="15"></rect>
				<rect y="30" width="100" height="15"></rect>
				<rect y="60" width="100" height="15"></rect>
			</svg>
		</span>
		<!-- X Icon -->
		<span class="nav-toggle-icon nav-toggle-x">
			<svg class="text-black fill-black" viewBox="0 0 100 80" width="40" height="40">
				<line x1="20" y1="20" x2="80" y2="60" stroke="black" stroke-width="15" stroke-linecap="round" />
				<line x1="80" y1="20" x2="20" y2="60" stroke="black" stroke-width="15" stroke-linecap="round" />
			</svg>
		</span>
	<?php endif; ?>
</button>

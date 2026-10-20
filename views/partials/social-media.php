<?php
/**
 * Social Media Links Partial
 *
 * @package BasicWP
 */

namespace BasicWP;

$classes = $args['classes'] ?? '';
$circle  = $args['circle'] ?? '';

// Define social media sites and their URLs
$sites = array(
	'facebook'  => getFieldValue( 'social_media.facebook' ) ? getFieldValue( 'social_media.facebook' ) : '',
	'twitter'   => getFieldValue( 'social_media.twitter' ) ? getFieldValue( 'social_media.twitter' ) : '',
	'pinterest' => getFieldValue( 'social_media.pinterest' ) ? getFieldValue( 'social_media.pinterest' ) : '',
	'instagram' => getFieldValue( 'social_media.instagram' ) ? getFieldValue( 'social_media.instagram' ) : '',
	'youtube'   => getFieldValue( 'social_media.youtube' ) ? getFieldValue( 'social_media.youtube' ) : '',
	'linkedin'  => getFieldValue( 'social_media.linkedin' ) ? getFieldValue( 'social_media.linkedin' ) : '',
);

// Add circle class if the circle option is enabled
if ( $circle ) {
	$classes .= ' circular-icon';
}

// Loop through the social media sites and output links
foreach ( $sites as $name => $url ) {
	if ( $url ) {
		?>
	<a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $classes ); ?>">
		<?php get_template_part( 'views/icons/' . $name ); ?>
		<span class="sr-only bg-white text-black">Visit our <?php echo esc_html( $name ); ?> page</span>
	</a>
		<?php
	}
}
?>

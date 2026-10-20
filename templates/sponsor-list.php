<?php
/**
 * Sponsor list partial.
 *
 * Expects `$sponsor_posts`, `$sponsor_type` and `$sponsor_class` in scope.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

if ( empty( $sponsor_posts ) || empty( $sponsor_type ) ) {
	return;
}

$recent_posts         = $sponsor_posts;
$sponsor_level_term   = get_term_by( 'slug', $sponsor_type, 'sponsor_type' );
$sponsor_level_label = $sponsor_level_term ? $sponsor_level_term->name : ucwords( str_replace( '-', ' ', $sponsor_type ) );

if ( $recent_posts ) :

	foreach ( $recent_posts as $recent ) :
		$sponsor_link  = get_field( 'link', $recent['ID'] );
		$thumbnail_id  = get_post_thumbnail_id( $recent['ID'] );
		$caption       = '';

		if ( $thumbnail_id ) {
			if ( function_exists( 'wp_get_attachment_caption' ) ) {
				$caption = wp_get_attachment_caption( $thumbnail_id );
			} else {
				$attachment = get_post( $thumbnail_id );
				$caption    = $attachment ? $attachment->post_excerpt : '';
			}
		}

		$listing_text = get_field( 'listing', $recent['ID'] );
		if ( empty( $listing_text ) ) {
			$listing_text = get_field( 'listing_text', $recent['ID'] );
		}
		if ( empty( $listing_text ) ) {
			$listing_text = get_the_excerpt( $recent['ID'] );
		}
		$listing_text = wp_strip_all_tags( $listing_text );
		?>

		<a class="sponsor__item flex items-center justify-center <?php echo esc_attr( $sponsor_class ); ?>"<?php if ( $sponsor_link ) : ?> href="<?php echo esc_url( $sponsor_link ); ?>"<?php endif; ?>>
			<figure class="sponsor__figure text-center">
				<?php echo get_the_post_thumbnail( $recent['ID'], 'full', array( 'class' => 'sponsor__logo' ) ); ?>
				<?php if ( $caption ) : ?>
					<figcaption class="sponsor__caption"><?php echo esc_html( $caption ); ?></figcaption>
				<?php endif; ?>
			</figure>
			<?php if ( $listing_text ) : ?>
				<p class="sponsor__listing"><?php echo esc_html( $listing_text ); ?></p>
			<?php endif; ?>
		</a>

	<?php endforeach;

else :
	?>
	<p class="text-center">Thank you to the 2025 CropConnect Conference sponsors</p>
	<?php
endif;

wp_reset_query();
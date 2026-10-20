<?php
/**
 * Speaker list item partial.
 *
 * Expects `$recent` (a post row from wp_get_recent_posts) in scope.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

?>
<!-- <a href="" class="home__speakers__item"> -->
<a href="<?php echo esc_url( get_permalink( $recent['ID'] ) ); ?>" class="home__speakers__item">
	<div class="home__speakers__overlay"></div>

	<div class="recent-post__image">
		<?php echo get_the_post_thumbnail( $recent['ID'] ); ?>
	</div>

	<?php if ( in_category( 'keynote-speaker', $recent['ID'] ) ) { ?>
		<h5>Keynote Speaker</h5>
	<?php } elseif ( in_category( 'banquet-speaker', $recent['ID'] ) ) { ?>
		<h5>Banquet Speaker</h5>
	<?php } ?>

	<h3><?php echo esc_html( $recent['post_title'] ); ?></h3>

	<p><?php the_field( 'position', $recent['ID'] ); ?></p>
</a>
<?php
/**
 * Site footer partial.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

?>
<footer class="footer">

	<div class="footer__main">
		<div class="container">
			<div class="footer__item">
				<?php dynamic_sidebar( 'sidebar-footer-1' ); ?>
			</div>

			<div class="footer__item">
				<div class="sidebar__block" style="display: none;">

					<h3 >Recent News</h3>
					<?php
					$args = array(
						'numberposts'      => 3,
						'offset'           => 0,
						'category'         => 0,
						'orderby'          => 'post_date',
						'order'            => 'DESC',
						'include'          => '',
						'exclude'          => '',
						'meta_key'         => '',
						'meta_value'       => '',
						'post_type'        => 'post',
						'post_status'      => 'publish',
						'suppress_filters' => true,
					);

					$recent_posts = wp_get_recent_posts( $args, ARRAY_A );
					?>

					<ul class="recent-posts">

					<?php foreach ( $recent_posts as $recent ) : ?>

						<li class="recent-post">

							<a href="<?php echo esc_url( get_permalink( $recent['ID'] ) ); ?>" class="recent-post__image">
								<?php if ( has_post_thumbnail( $recent['ID'] ) ) : ?>

									<img class="" src="<?php echo esc_url( get_the_post_thumbnail_url( $recent['ID'] ) ); ?>" title="">
								<?php else : ?>

									<?php
									$image = get_field( 'blog_placeholder_image', 'option' );
									if ( $image ) :
										?>
										<img class="" src="<?php echo esc_url( $image['url'] ); ?>" title="">
									<?php endif; ?>

								<?php endif; ?>

							</a>

							<div class="recent-post__info">

								<p><a href="<?php echo esc_url( get_permalink( $recent['ID'] ) ); ?>" class="">
									<strong><?php echo esc_html( $recent['post_title'] ); ?></strong>
								</a></p>
								<p class="recent-post__meta">
									<span class="byline author vcard"><?php the_author_meta( 'display_name', $recent['post_author'] ); ?>
									</span>
									&nbsp;&nbsp; / &nbsp;&nbsp;
									<?php echo esc_html( get_the_date( null, $recent['ID'] ) ); ?>
								</p>
							</div>

						</li>

					<?php endforeach; ?>

					</ul>
					<?php wp_reset_query(); ?>
				</div>
			</div>

			<div class="footer__item">
				<?php dynamic_sidebar( 'sidebar-footer-3' ); ?>
			</div>

		</div>
	</div>

	<div class="footer__bottom">
		<div class="container">
			<p class="footer__copy"><small>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> CropConnect Conference. All Rights Reserved. &ensp; Web Design & Development by <a href="https://www.vincentdesign.ca" class="text-white">Vincent Design</a></small></p>
		</div>
	</div>

</footer>
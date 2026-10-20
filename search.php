<?php
/**
 * Blog search template
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

// Determine classes based on sidebar presence
if ( hasSidebar() ) {
	$classes  = 'container grid grid-cols-1 lg:grid-cols-4 gap-8 xl:gap-16 my-section mx-auto';
	$clsEntry = 'lg:col-span-3';
} else {
	$classes = 'container my-section lg:my-0 mx-auto';
}

get_header();
?>

<section class="<?php echo esc_attr( $classes ); ?>">
	<?php if ( have_posts() ) : ?>
		<div class="blog-posts <?php echo esc_attr( $clsEntry ); ?>">
			<div class="post-list">
				<div class="post-list__posts grid grid-cols-[repeat(auto-fit,minmax(20rem,1fr))] gap-6">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<div class="post-list__post flex flex-col border border-secondary rounded-md shadow-lg hover:prose-img:scale-110 hover:prose-img:origin-center hover:prose-img:duration-500">
							<figure class="post-list__img aspect-video border-b border-secondary rounded-t-md block h-auto w-full overflow-hidden m-0 p-0">
								<?php
								$featImg = get_the_post_thumbnail_url();
								$postImg = $featimg ? $featImg : 'https://picsum.photos/600/400?random=' . get_the_ID();
								$imgAlt  = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ?? get_the_title();
								?>

								<img class="block h-full object-cover transition-transform duration-300 ease-linear w-full will-change-transform" src="<?php echo esc_url( $postImg ); ?>" alt="<?php echo esc_attr( $imgAlt ); ?>">
							</figure>

							<div class="post-list__details px-4 py-8 flex flex-col flex-grow">
									<?php
									$categories = get_the_category();
									if ( ! empty( $categories ) ) {
										?>
										<div class="post-list__cats">
											Posted in:
											<?php
											foreach ( $categories as $index => $category ) :
												$separator = $index < count( $categories ) - 1 ? ', ' : '';
												?>
												<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="post-list__category text-14px font-semibold leading-none uppercase mt-0 mb-2.5 mx-0 inline-block"><?php echo esc_html( $category->name ); ?></a><?php echo esc_attr( $separator ); ?>
											<?php endforeach; ?>
										</div>
									<?php } else { ?>
										<div class="post-list__cats">
											<?php $postType = get_post_type(); ?>
											Post Type: <span class="post-list__category text-14px font-semibold leading-none uppercase mt-0 mb-2.5 mx-0 inline-block"><?php echo esc_html( $postType ); ?></span>
										</div>
									<?php } ?>

								<a href="<?php the_permalink(); ?>" class="">
									<h2 class="post-list__title font-normal text-25px text-balance line-clamp-4 truncate mt-0 mb-5 mx-0"><?php the_title(); ?></h2>
								</a>

								<div class="post-list__byline mt-auto">
									<span class="post-list__author"><?php echo esc_html( ucfirst( get_the_author() ) ); ?> &mdash;</span>
									<span class="post-list__date"><?php echo get_the_date(); ?></span>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
				</div>

				<div class="post-list__pagination">
					<?php
					the_posts_pagination(
						array(
							'mid_size'  => 2,
							'prev_text' => '&laquo; Previous',
							'next_text' => 'Next &raquo;',
						)
					);
					?>
				</div>
			</div>
		</div>
	<?php else : ?>
		<div class="blog-posts <?php echo esc_attr( $clsEntry ); ?>">
			<div class="no-posts">
				<h2 class="no-posts__title">Nothing here yet&hellip;</h2>
				<p class="no-posts__desc">No published posts found.</p>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( hasSidebar() ) : ?>
		<?php get_sidebar(); ?>
	<?php endif; ?>
</section>

<?php get_footer(); ?>

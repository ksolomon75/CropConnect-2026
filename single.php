<?php
/**
 * Single Posts
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

get_header();

// Determine classes based on sidebar presence
if ( hasSidebar() ) {
	$classes  = 'container grid grid-cols-1 lg:grid-cols-4 gap-8 xl:gap-16 my-section mx-auto';
	$clsEntry = 'lg:col-span-3';
} else {
	$classes = 'container my-section lg:my-0 mx-auto';
}
?>

<article class="<?php echo esc_attr( $classes ); ?>">
	<div class="entry-content <?php echo esc_attr( $clsEntry ); ?>">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				?>

				<div class="post-title mb-0">
					<h1 class="mb-0"><?php the_title(); ?></h1>
				</div>

				<div class="post-meta text-14px italic mb-6">
					<div class="">
						<span class="post-date">Posted: <?php the_date(); ?></span> | <span class="post-author">Posted by: <?php echo esc_html( ucfirst( get_the_author() ) ); ?></span>
					</div>

					<div class="">
						<span class="post-categories">
							<?php
							$categories = get_the_category();
							if ( ! empty( $categories ) ) {
								echo '<span>Posted in: ';
								foreach ( $categories as $ct ) {
									echo '<a href="' . esc_url( get_category_link( $ct->cat_ID ) ) . '">' . esc_html( $ct->name ) . '</a>';
									if ( count( $categories ) > 1 && $ct !== end( $categories ) ) {
										echo ', ';
									}
								}
								echo '</span>';
							}
							?>
						</span>
						|
						<span class="post-tags">
							<?php
							$tags = get_the_tags();
							if ( ! empty( $tags ) ) {
								echo '<span>Tagged: ';
								foreach ( $tags as $tg ) {
									echo '<a href="' . esc_url( get_tag_link( $tg->term_id ) ) . '">' . esc_html( $tg->name ) . '</a>';
									if ( count( $tags ) > 1 && $tg !== end( $tags ) ) {
										echo ', ';
									}
								}
								echo '</span>';
							}
							?>
						</span>
					</div>
				</div>

				<?php
				the_content();
			}
		}
		?>
	</div>

	<?php if ( hasSidebar() ) : ?>
		<?php get_sidebar(); ?>
	<?php endif; ?>
</article>

<?php get_footer(); ?>

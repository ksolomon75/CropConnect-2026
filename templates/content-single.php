<?php
/**
 * Single post content partial.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class(); ?>>

		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail(); ?>
		<?php else : ?>
			<?php
			$image = get_field( 'blog_placeholder_image', 'option' );
			if ( $image ) :
				?>
				<img class="" src="<?php echo esc_url( $image['url'] ); ?>" title="">
			<?php endif ?>
		<?php endif; ?>
		</a>

		<header>
			<?php get_template_part( 'templates/entry-meta' ); ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>

		</header>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
		<footer>
			<?php
			wp_link_pages(
				array(
					'before' => '<nav class="page-nav"><p>' . esc_html__( 'Pages:', 'sage' ),
					'after'  => '</p></nav>',
				)
			);
			?>
		</footer>

		<?php comments_template( '/templates/comments.php' ); ?>

	</article>

	<?php
endwhile;
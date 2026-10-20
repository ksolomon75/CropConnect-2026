<?php
/**
 * Archive content partial.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

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
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	</header>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div>

	<div class="">
		<a class="btn" href="<?php the_permalink(); ?>">Read More</a>
	</div>
</article>
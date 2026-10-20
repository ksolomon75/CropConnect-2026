<?php
/**
 * Single speaker partial.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class(); ?>>

		<div class="speaker-single__wrap">
			<div class="speaker-single__image">
				<?php echo get_the_post_thumbnail(); ?>
			</div>
			<div class="speaker-single__info">

				<?php if ( in_category( 'keynote-speaker' ) ) { ?>
					<h5 class="speaker__type">Keynote Speaker</h5>
				<?php } elseif ( in_category( 'banquet-speaker' ) ) { ?>
					<h5 class="speaker__type">Banquet Speaker</h5>
				<?php } ?>

				<h3 class="speaker__name"><?php the_title(); ?></h3>

				<p class="speaker__position"><?php the_field( 'position' ); ?></p>
			</div>
		</div>

		<div class="entry-content">
			<?php the_content(); ?>
		</div>

		<div>

			<?php
			$args = array(
				'post_type'  => 'presentation',
				'meta_query' => array(
					array(
						'key'     => 'presentation_speakers',
						'value'   => '"' . get_the_ID() . '"',
						'compare' => 'LIKE',
					),
				),
			);

			$the_query = new \WP_Query( $args );
			?>

			<?php if ( $the_query->have_posts() ) : ?>
				<h3>Presentation(s):</h3>
				<ul>
				<?php
				while ( $the_query->have_posts() ) :
					$the_query->the_post();
					?>
					<li>
						<a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
					</li>
				<?php endwhile; ?>
				</ul>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>

		</div>
	</article>

	<?php
endwhile;
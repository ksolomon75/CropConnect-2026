<?php
/**
 * Single presentation content partial.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class(); ?>>

		<header>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>

		<div class="">
			<h3>Presenters:</h3>
			<?php
			$id       = get_the_id();
			$speakers = get_field( 'presentation_speakers', $id );

			if ( $speakers ) :
				?>
				<ul>
				<?php foreach ( $speakers as $speaker ) : ?>
					<li>
						<a href="<?php echo esc_url( get_permalink( $speaker->ID ) ); ?>"><?php echo esc_html( $speaker->post_title ); ?></a>
					</li>

				<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

	</article>

	<?php
endwhile;
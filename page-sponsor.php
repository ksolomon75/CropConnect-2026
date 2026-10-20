<?php
/**
 * Template Name: Sponsors Template
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;
while ( have_posts() ) :
	the_post();
	get_template_part( 'templates/page', 'header' );
	?>

	<div class="container">
		<main class="">
			<?php the_content(); ?>
			<div>
				<hr>
				<?php
				$sponsor_sections = array(
					array(
						'title' => 'Exclusive Sponsors',
						'type'  => 'activity',
						'class' => 'sponsor__item--activity',
					),
					array(
						'title' => 'Harvest Sponsors',
						'type'  => 'harvest',
						'class' => 'sponsor__item--harvest',
					),
					array(
						'title' => 'Plant Sponsors',
						'type'  => 'plant',
						'class' => 'sponsor__item--plant',
					),
					array(
						'title' => 'Gold Sponsors',
						'type'  => 'gold',
						'class' => 'sponsor__item--gold',
					),
					array(
						'title' => 'Silver Sponsors',
						'type'  => 'silver',
						'class' => 'sponsor__item--silver',
					),
					array(
						'title' => 'Bronze Sponsors',
						'type'  => 'bronze',
						'class' => 'sponsor__item--bronze',
					),
					array(
						'title' => 'Friends',
						'type'  => 'other',
						'class' => 'sponsor__item--other',
					),
				);

				foreach ( $sponsor_sections as $section ) :
					$sponsor_posts = cropconnect_get_sponsors_by_type( $section['type'] );
					if ( empty( $sponsor_posts ) ) {
						continue;
					}
					?>
					<h2 class="text-center"><?php echo esc_html( $section['title'] ); ?></h2>
					<div class="sponsors__list">
						<?php
						$sponsor_type  = $section['type'];
						$sponsor_class = $section['class'];
						include locate_template( 'templates/sponsor-list.php' );
						?>
					</div>
					<hr>
				<?php endforeach; ?>
			</div>
		</main>

	</div>

	<?php
endwhile;

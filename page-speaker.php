<?php
/**
 * Template Name: Speakers Template
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;
get_template_part( 'templates/page', 'header' );

$args = array(
	'numberposts'      => -1,
	'offset'           => 0,
	'category_name'    => 'keynote-speaker',
	'order'            => 'DESC',
	'post_type'        => 'speakers',
	'post_status'      => 'publish',
	'suppress_filters' => true,
);

$recent_posts = wp_get_recent_posts( $args, ARRAY_A );
?>

	<?php foreach ( $recent_posts as $recent ) { ?>

		<section class="speaker-section">
			<div class="container">

				<div class="speaker__image">
					<?php $image = get_field( 'large_image', $recent['ID'] ); ?>
					<img class="speaker__img--large" src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" />
					<img class="speaker__img--small" src="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id( $recent['ID'] ) ) ); ?>">
				</div>

				<div class="speaker__info">
					<div>
						<h5 class="speaker__type">Keynote Speaker</h5>
						<h3 class="speaker__name"><?php echo esc_html( $recent['post_title'] ); ?></h3>

						<p class="speaker__position"><small><?php the_field( 'position', $recent['ID'] ); ?></small></p>

						<p class="speaker_excerpt"><?php echo get_the_excerpt( $recent['ID'] ); ?></p>

						<a class="btn" href="<?php echo esc_url( get_permalink( $recent['ID'] ) ); ?>">Read Full Bio</a>
					</div>
				</div>
			</div>
		</section>

	<?php } ?>

	<?php wp_reset_query(); ?>

	<?php
	$args = array(
		'numberposts'      => -1,
		'offset'           => 0,
		'category_name'    => 'banquet-speaker',
		'orderby'          => 'menu_order',
		'order'            => 'ASC',
		'post_type'        => 'speakers',
		'post_status'      => 'publish',
		'suppress_filters' => true,
	);

	$recent_posts = wp_get_recent_posts( $args, ARRAY_A );
	?>

	<?php foreach ( $recent_posts as $recent ) { ?>

		<section class="speaker-section ">
			<div class="container">
				<div class="speaker__image">
					<?php $image = get_field( 'large_image', $recent['ID'] ); ?>
					<img class="speaker__img--large" src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" />
					<img class="speaker__img--small" src="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id( $recent['ID'] ) ) ); ?>">
				</div>
				<div class="speaker__info">
					<div>
					<h5 class="speaker__type">Banquet Speaker</h5>
					<h3 class="speaker__name"><?php echo esc_html( $recent['post_title'] ); ?></h3>
					<p class="speaker__position"><small><?php the_field( 'position', $recent['ID'] ); ?></small></p>

					<p class="speaker_excerpt"><?php echo get_the_excerpt( $recent['ID'] ); ?></p>

					<a class="btn" href="<?php echo esc_url( get_permalink( $recent['ID'] ) ); ?>">Read Full Bio</a>
					</div>
				</div>

			</div>
		</section>

	<?php } ?>

	<?php wp_reset_query(); ?>

<section class="home__speakers__section">

	<!-- remove this when there are speakers

	<h2 class="">Speakers</h2>
	<div style="margin-bottom: 40px;" class="">
		<p>Full speaker list coming soon</p>
	</div> -->


	<hr>
	<!-- <h3 class="">Suggest a Speaker</h3>
	<div class="">
		<p>Please take the time to suggest a speaker for the upcoming Crop Connect Conference 2020!</p>
		<a class="btn" href="https://www.surveymonkey.com/r/LCSFFKG">Suggestion</a>
	</div> -->

	<div class="container">
		<div class="home__speakers">
		<?php
		$args = array(
			'numberposts'      => -1,
			'offset'           => 0,
			'category'         => '-7, -8',
			'order'            => 'ASC',
			'post_type'        => 'speakers',
			'post_status'      => 'publish',
			'suppress_filters' => true,
		);

		$recent_posts = wp_get_recent_posts( $args, ARRAY_A );
		?>

		<?php foreach ( $recent_posts as $recent ) { ?>
			<?php include locate_template( 'templates/speaker-list.php' ); ?>
		<?php } ?>

		<?php wp_reset_query(); ?>

		</div>

	</div>
</section>

<?php

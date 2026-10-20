<?php
/**
 * Template Name: Home Template
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

?>
<!-- get hero images -->
<?php
$bg_image     = get_field( 'background_image' );
$logo_image   = get_field( 'logo_image' );
$buttons      = get_field( 'buttons' );
$about_images = get_field( 'about_sponsor_images' );
?>

<!-- if there is a hero image -->
<?php if ( $bg_image ) : ?>

	<!--t his section has been changed to display:flex and may break previous layouts -->
	<section class="hero" style="background-image: url('<?php echo esc_url( $bg_image['url'] ); ?>');">
		<div class="container hero_container">
			<h1 class="hero_h1">
				<?php
				the_field( 'text' );
				if ( ! empty( $logo_image ) ) :
					?>
					<img src="<?php echo esc_url( $logo_image['url'] ); ?>" alt="<?php echo esc_attr( $logo_image['alt'] ); ?>" />
				<?php endif; ?>
			</h1>
			<?php if ( $buttons ) : ?>
				<div class="hero_btns">
					<?php foreach ( $buttons as $button ) : ?>
						<a class="btn hero_btn" href="<?php echo esc_url( $button['button']['url'] ); ?>" <?php if ( $button['button']['target'] ) { echo 'target="_blank"'; } ?> >
							<?php echo esc_html( $button['button']['title'] ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>

<section class="home__message">
		<div class="container container--message">
			<div class="home__message__text">
				<?php echo cropconnect_remove_moga_homepage_content( get_field( 'body' ) ); ?>
			</div>
		</div>
	</section>

<section class="home__grid">
	<div class="container">

	<?php if ( have_rows( 'grid_items' ) ) :
		$grid_items = get_field( 'grid_items' );
		?>

		<div class="home__grid__item home__grid__item--l">
			<a href="<?php echo esc_url( $grid_items[0]['link'] ); ?>" class="home__grid__item home__grid__item--ml">
				<img class="" src="<?php echo esc_url( $grid_items[0]['background_image']['url'] ); ?>" title="">
				<div style="background-image: url('<?php echo esc_url( $grid_items[0]['foreground_image']['url'] ); ?>')" class="home__grid__item__overlay"></div>
			</a>
			<a href="<?php echo esc_url( $grid_items[1]['link'] ); ?>" class="home__grid__item home__grid__item--bl">
				<img class="" src="<?php echo esc_url( $grid_items[1]['background_image']['url'] ); ?>" title="">
				<div style="background-image: url('<?php echo esc_url( $grid_items[1]['foreground_image']['url'] ); ?>')" class="home__grid__item__overlay"></div>
			</a>
		</div>

		<!-- remove this middle part to use the other 5 grid item layout -->
		<div class="home__grid__item home__grid__item--l">
			<a href="<?php echo esc_url( $grid_items[2]['link'] ); ?>" class="home__grid__item home__grid__item--ml">
				<img class="" src="<?php echo esc_url( $grid_items[2]['background_image']['url'] ); ?>" title="">
				<div style="background-image: url('<?php echo esc_url( $grid_items[2]['foreground_image']['url'] ); ?>')" class="home__grid__item__overlay"></div>
			</a>
			<a href="<?php echo esc_url( $grid_items[3]['link'] ); ?>" class="home__grid__item home__grid__item--bl">
				<img class="" src="<?php echo esc_url( $grid_items[3]['background_image']['url'] ); ?>" title="">
				<div style="background-image: url('<?php echo esc_url( $grid_items[3]['foreground_image']['url'] ); ?>')" class="home__grid__item__overlay"></div>
			</a>
		</div>

		<!-- this layout part is for 5 grid items, change the associated numbers for each grid item, to be back in the correct order Eg: $grid_items[2]['link']
		<a href="<?php echo esc_url( $grid_items[2]['link'] ); ?>" class="home__grid__item home__grid__item--mm">
			<img class="" src="<?php echo esc_url( $grid_items[2]['background_image']['url'] ); ?>" title="">
			<div style="background-image: url('<?php echo esc_url( $grid_items[2]['foreground_image']['url'] ); ?>')" class="home__grid__item__overlay"></div>
		</a>
	    -->

		<div class="home__grid__item home__grid__item--r">
			<a href="<?php echo esc_url( $grid_items[4]['link'] ); ?>" class="home__grid__item home__grid__item--mr">
				<img class="" src="<?php echo esc_url( $grid_items[4]['background_image']['url'] ); ?>" title="">
				<div style="background-image: url('<?php echo esc_url( $grid_items[4]['foreground_image']['url'] ); ?>')" class="home__grid__item__overlay"></div>
			</a>
			<a href="<?php echo esc_url( $grid_items[5]['link'] ); ?>" class="home__grid__item home__grid__item--br">
				<img class="" src="<?php echo esc_url( $grid_items[5]['background_image']['url'] ); ?>" title="">
				<div style="background-image: url('<?php echo esc_url( $grid_items[5]['foreground_image']['url'] ); ?>')" class="home__grid__item__overlay"></div>
			</a>
		</div>

	<?php endif; ?>

	</div>
</section>

<!-- just take out the last item if you need to go down to 5 -->
<section class="home__grid--mobile">
	<div class="container">

	<?php
	if ( have_rows( 'grid_items_mobile' ) ) :
		$grid_items = get_field( 'grid_items_mobile' );
		?>

		<a href="<?php echo esc_url( $grid_items[0]['link'] ); ?>" class="home__grid--mobile__item">
			<img class="" src="<?php echo esc_url( $grid_items[0]['image']['url'] ); ?>" title="">
		</a>
		<a href="<?php echo esc_url( $grid_items[1]['link'] ); ?>" class="home__grid--mobile__item">
			<img class="" src="<?php echo esc_url( $grid_items[1]['image']['url'] ); ?>" title="">
		</a>
		<a href="<?php echo esc_url( $grid_items[2]['link'] ); ?>" class="home__grid--mobile__item">
			<img class="" src="<?php echo esc_url( $grid_items[2]['image']['url'] ); ?>" title="">
		</a>
		<a href="<?php echo esc_url( $grid_items[3]['link'] ); ?>" class="home__grid--mobile__item">
			<img class="" src="<?php echo esc_url( $grid_items[3]['image']['url'] ); ?>" title="">
		</a>

		<a href="<?php echo esc_url( $grid_items[4]['link'] ); ?>" class="home__grid--mobile__item">
			<img class="" src="<?php echo esc_url( $grid_items[4]['image']['url'] ); ?>" title="">
		</a>

		<a href="<?php echo esc_url( $grid_items[5]['link'] ); ?>" class="home__grid--mobile__item">
			<img class="" src="<?php echo esc_url( $grid_items[5]['image']['url'] ); ?>" title="">
		</a>

	<?php endif; ?>

	</div>
</section>

<section class="home__about">
	<div class="container">
		<div class="home__about__item home__about__item--logo">
			<img class="home__about__logo" src="<?php echo esc_url( get_template_directory_uri() ); ?>/static/img/logo-about.png">
		</div>
		<div class="home__about__item">
			<?php echo cropconnect_remove_moga_homepage_content( get_field( 'home_page_about_text' ) ); ?>
		</div>
		<ul class="home__about__sponsors">
			<?php foreach ( $about_images as $image ) : ?>
				<li class="home__about__sponsor">
					<a href="<?php echo esc_url( $image['link'] ); ?>" target="_blank" rel="noopener"><img src="<?php echo esc_url( $image['image']['url'] ); ?>" alt="<?php echo esc_attr( $image['image']['alt'] ); ?>"></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<section class="section--images" style="background-image: url('<?php echo esc_url( get_template_directory_uri() ); ?>/static/img/imagegroup.jpg');">
	<!-- <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/static/img/imagegroup.jpg"> -->
</section>

<section class="section--lines" style="background-image: url('<?php echo esc_url( get_template_directory_uri() ); ?>/static/img/lines.png');">
</section>

<section class="home__speakers__section">
	<div class="container">
	<h2 class="text-center">Speakers</h2>

	<?php
	$args = array(
		'numberposts'      => 4,
		'offset'           => 0,
		'category_name'    => 'banquet-speaker, Keynote Speaker',
		'order'            => 'DESC',
		'post_type'        => 'speakers',
		'post_status'      => 'publish',
		'suppress_filters' => true,
	);

	$recent_posts = wp_get_recent_posts( $args, ARRAY_A );
	?>

	<?php if ( $recent_posts ) { ?>
		<div class="home__speakers">
			<?php foreach ( $recent_posts as $recent ) { ?>
				<?php include locate_template( 'templates/speaker-list.php' ); ?>
			<?php } ?>

			<div class="home__speakers__link"><a href="<?php get_site_url(); ?>/event/speakers">See Full Speakers List</a></div>
		</div>
	</div>
	<?php } else { ?>
	<div class="text-center">
		<p>Full speaker list coming soon</p>
	</div>
	<?php } ?>

	<hr>
	<h3 class="">Suggest a Speaker</h3>
	<div class="">
		<p>Please take the time to suggest a speaker for the upcoming Crop Connect Conference!</p>
		<a class="btn" href="https://www.surveymonkey.com/r/LCSFFKG">Suggestion</a>
	</div>

	<?php wp_reset_query(); ?>

	</section>

<?php

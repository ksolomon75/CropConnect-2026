<?php
/**
 * Template Name: Presentations Template
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

		<div>
			<?php the_content(); ?>
		</div>

	</div>

	<?php
endwhile;

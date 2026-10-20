<?php
/**
 * Page template.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

while ( have_posts() ) :
	the_post();
	get_template_part( 'templates/page', 'header' );
	?>
  <!-- get sponsor banner checking template -->
	<?php get_template_part( 'templates/sponsor-banner' );
	get_template_part( 'templates/content', 'page' );
endwhile;
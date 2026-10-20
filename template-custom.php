<?php
/**
 * Template Name: Custom Template
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;
while ( have_posts() ) :
	the_post();
	get_template_part( 'templates/page', 'header' );
	get_template_part( 'templates/content', 'page' );
endwhile;

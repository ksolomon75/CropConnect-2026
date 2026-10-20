<?php
/**
 * Front Page Template
 *
 * @package BasicWP
 */

namespace BasicWP;

get_header();
?>

<div class="container my-section lg:my-0 mx-auto">
	<div>
		<?php
		// Content block
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				the_content();
			}
		}
		?>
	</div>
</div>

<?php get_footer(); ?>

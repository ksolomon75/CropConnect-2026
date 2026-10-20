<?php
/**
 * Page content partial.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

?>
<div class="container">
	<main class="main">
	<?php the_content(); ?>
	</main>

	<?php if ( display_sidebar() ) : ?>
		<aside class="sidebar">
			<?php get_template_part( 'templates/sidebar' ); ?>
		</aside>
	<?php endif; ?>

</div>
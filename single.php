<?php
/**
 * Single post template.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;
get_template_part( 'templates/page', 'header' );

?>

<div class="container">

	<main class="main">
		<?php get_template_part( 'templates/content-single', get_post_type() ); ?>
	</main>

	<?php if ( display_sidebar() ) : ?>
		<aside class="sidebar">
			<?php get_template_part( 'templates/sidebar' ); ?>
		</aside>
	<?php endif; ?>

</div>

<?php

<?php
/**
 * 404 template.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;
get_template_part( 'templates/page', 'header' );

?>

<div class="container">

	<div class="alert alert-warning">
		<?php _e( 'Sorry, but the page you were trying to view does not exist.', 'sage' ); ?>
	</div>

	<?php get_search_form(); ?>

</div>

<?php

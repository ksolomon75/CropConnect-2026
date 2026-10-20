<?php
/**
 * Search results template.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;
get_template_part( 'templates/page', 'header' );

?>

<div class="container">
<?php if ( ! have_posts() ) : ?>
	<div class="alert alert-warning">
		<?php _e( 'Sorry, no results were found.', 'sage' ); ?>
	</div>
	<?php get_search_form(); ?>
<?php endif; ?>

<?php
while ( have_posts() ) :
	the_post();
	get_template_part( 'templates/content', 'search' );
endwhile;
?>

<?php the_posts_navigation(); ?>
</div>

<?php

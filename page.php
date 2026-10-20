<?php
/**
 * Single Pages
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

get_header();

$clsEntry = '';

// Determine classes based on sidebar presence
if ( hasSidebar() ) {
	$classes  = 'container grid grid-cols-1 lg:grid-cols-4 gap-8 xl:gap-16 my-section mx-auto';
	$clsEntry = 'lg:col-span-3';
} else {
	$classes = 'container my-section lg:my-0 mx-auto';
}
?>

<article class="<?php echo esc_attr( $classes ); ?>">
	<div class="entry-content <?php echo esc_attr( $clsEntry ); ?>">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				the_content();
			}
		}
		?>
	</div>

	<?php if ( hasSidebar() ) : ?>
		<?php get_sidebar( 'page' ); ?>
	<?php endif; ?>
</article>

<?php get_footer(); ?>

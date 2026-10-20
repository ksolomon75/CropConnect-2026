<?php
/**
 * Page Not Found
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

get_header();
?>

<section class="error-404 container">
	<h2>Page Not Found</h2>
	<h3>Sorry, the page you're looking for doesn't exist.</h3>

	<p>It might have been removed, had its name changed, or is temporarily unavailable.</p>

	<p>
	<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label>
		<span class="screen-reader-text"><?php echo esc_html__( 'Search for:', 'label' ); ?></span>
		Try searching for it: <input type="search" class="search-field border border-secondary px-2 rounded-md" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr__( 'Search for:', 'label' ); ?>" />
		</label>
		<x-button
		btnClasses="search-submit button text-center"
		element="button"
	type="submit"
		title="<?php echo esc_attr__( 'Search', 'submit button' ); ?>"
		color="primary"
		variant="default"
		size="small"
		width="auto"
		></x-button>
	</form>
	</p>

	<p>Or check out the links below:</p>
	<ul class="list-inside">
	<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
	<li><a href="<?php echo esc_url( home_url( '/news' ) ); ?>">Posts</a></li>
	<li>If all else fails, <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>">Contact Us</a></li>
	</ul>
</section>

<?php get_footer(); ?>

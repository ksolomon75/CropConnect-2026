<?php
/**
 * Page header partial.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

$image = get_field( 'page_header_image', 'option' );

?>
<style>
.page-header .breadcrumbs a {
    color: white !important;
    text-decoration: none; /* optional */
}

.page-header .breadcrumbs a:hover {
    color: white !important;
}
</style>

<?php if ( $image ) : ?>
	<div class="page-header" style="background-image: url('<?php echo esc_url( $image['url'] ); ?>');">
		<div class="container">
			<div class="page-header__wrap" style="color: white;">
				<h1 class="page-header__title page-header__title--<?php echo esc_attr( get_post_type() ); ?>"><?php echo esc_html( title() ); ?></h1>
				<div class="breadcrumbs" style="color: white !important;" typeof="BreadcrumbList" vocab="http://schema.org/">
					<?php
					if ( function_exists( 'bcn_display' ) ) {
						bcn_display();
					}
					?>
				</div>
			</div>
	</div>
<!--  	<div class="page-header__overlay">

 	</div> -->
</div>

<?php endif; ?>
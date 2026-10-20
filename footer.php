<?php
/**
 * Theme footer template
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

$footerLogo = getFieldValue( 'footer.footer_logo.url' ) ? getFieldValue( 'footer.footer_logo.url' ) : '';
$footerDesc = getFieldValue( 'footer.footer_description' ) ? getFieldValue( 'footer.footer_description' ) : '';

$copyright_text = getFieldValue( 'footer.copyright_text' ) ? getFieldValue( 'footer.copyright_text' ) : 'Copyright &copy; %Y ' . get_bloginfo( 'name' );

$copyright = str_replace( '%Y', gmdate( 'Y' ), $copyright_text );

$locations = get_nav_menu_locations();
$footerNav = ! empty( $locations['footer_navigation'] )
    ? ( wp_get_nav_menu_items( (int) $locations['footer_navigation'] ) ? wp_get_nav_menu_items( (int) $locations['footer_navigation'] ) : array() )
    : array();
?>

	</main>

	<footer role="contentinfo" class="site-footer bg-gray-800 text-white text-base">
		<div class="pt-16 pb-12 text-base">
			<div class="container mx-auto">
				<div class="grid grid-cols-4 gap-6 md:grid-cols-8 lg:grid-cols-12">
					<div class="col-span-4 md:col-span-8 lg:col-span-4 max-w-[40ch] prose-p:text-14px prose-p:mb-4 text-balance" aria-labelledby="footer-header">
						<h2 id="footer-header" class="max-w-64 h-auto">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-footer__logo-link h-full w-full">
								<?php if ( $footerLogo ) { ?>
									<img src="<?php echo esc_url( $footerLogo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="site-footer__logo-image" />
									<?php
								} else {
									echo esc_html( get_bloginfo( 'name' ) );
								}
								?>
							</a>
						</h2>

						<div id="footer-description" aria-label="Footer description">
							<?php echo wp_kses_post( $footerDesc ); ?>
						</div>

						<div class="social-links mt-8">
							<?php
							get_template_part(
								'views/partials/social-media',
								null,
								array(
									'circle'  => false,
									'classes' => 'social-icons p-0 mr-2 text-30px text-gray-300! hover:text-info!',
								)
							);
							?>
						</div>
					</div>

					<div id="footRight" class="col-span-4 md:col-span-8 grid md:grid-cols-4 gap-10 lg:justify-end">
						<div aria-labelledby="footer-area-1" class="prose-p:text-balance">
							<h3 class="mb-4 pb-2 border-b border-b-secondary font-bold text-white" id="footer-area-1">Footer Area 1</h3>
							<?php dynamic_sidebar( 'footer-1' ); ?>
						</div>

						<div aria-labelledby="footer-area-2" class="prose-p:text-balance">
							<h3 class="mb-4 pb-2 border-b border-b-secondary font-bold text-white" id="footer-area-2">Footer Area 2</h3>
							<?php dynamic_sidebar( 'footer-2' ); ?>
						</div>

						<div aria-labelledby="footer-area-3" class="prose-p:text-balance">
							<h3 class="mb-4 pb-2 border-b border-b-secondary font-bold text-white" id="footer-area-3">Footer Area 3</h3>
							<?php dynamic_sidebar( 'footer-3' ); ?>
						</div>

						<div id="footer-nav" aria-labelledby="footer-navigation">
							<h3 class="mb-4 pb-2 border-b border-b-secondary font-bold text-white" id="footer-navigation"><?php echo esc_html__( 'Navigation' ); ?></h3>
							<?php if ( $footerNav ) : ?>
								<nav class="site-footer__nav" aria-label="Footer navigation">
									<ul class="site-footer__nav-list">
										<?php foreach ( $footerNav as $item ) : ?>
											<li class="site-footer__nav-item list-none text-left <?php echo esc_attr( implode( ' ', $item->classes ) ); ?>">
												<a href="<?php echo esc_url( $item->url ); ?>" class="site-footer__nav-link">
													<?php echo esc_html( $item->title ); ?>
												</a>
											</li>
										<?php endforeach; ?>
									</ul>
								</nav>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="copyright bg-primary text-white text-center py-3 text-14px">
			<?php
			if ( $copyright ) {
				echo wp_kses_post( $copyright );
			} else {
				echo esc_html( '&copy; ' . gmdate( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '.' );
			}
			?>
		</div>
	</footer>

	<?php wp_footer(); ?>
</body>
</html>

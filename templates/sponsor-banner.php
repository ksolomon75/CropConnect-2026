<?php
/**
 * Sponsor banner partial.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

if ( get_field( 'sponsor_banner_check' ) ) :

	$logo_year = get_field( 'logo_year', 'option' );
	?>
	<div class="sponsor__banner">
		<img class="hero__streamer__underlay" src="<?php echo esc_url( get_template_directory_uri() ); ?>/static/img/CC-logo-white-big.png">

		<div class="sponsor__banner__container">
			<div class="sponsor__banner__wrap">
				<div class="sponsor__banner__item">
					<?php if ( $logo_year ) : ?>
						<img class="sponsor__banner__text-image" src="<?php echo esc_url( $logo_year['url'] ); ?>">
					<?php endif; ?>
				</div>
				<div class="sponsor__banner__item">
					<p class="hero__text">Victoria Inn Hotel and Convention Centre 1808 Wellington Ave. Winnipeg, Manitoba</p>
				</div>
				<!-- <div class="sponsor__banner__item sponsor__banner__item--bottom">
					<p class="sponsor__banner__text sponsor__banner__sub-item">Presented by:</p>
					<div class="sponsor__banner__sponsor__logo sponsor__banner__sub-item">
						<a href="https://cxn360.com/?utm_source=cropconnect&utm_medium=referral&utm_campaign=cropconnect2018"><img class="sponsor__banner__sponsor__logo__image" src="<?php echo esc_url( get_template_directory_uri() ); ?>/static/img/ag.jpg"></a>
					</div>
					<p class="sponsor__banner__text sponsor__banner__sub-item" href="">cxn360.com</p>
				</div> -->
			</div>
		</div>

		<div class="sponsor__banner__foreground__image">
		</div>
	</div>

<?php endif; ?>
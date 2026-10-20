<?php
/**
 * Site header partial.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

?>
<header class="header">
	<div class="container">
		<a class="header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img class="header__logo__image" src="https://cropconnectconference.ca/wp-content/uploads/2019/09/CCC-updated.png">
		</a>


		<nav class="nav-primary" id="nav">

		<ul class="contact-menu">
			<li class="contact-menu__item">

				<a href="
https://www.facebook.com/cropconnectconference/" class="contact-menu__link contact-menu__item--icon">
					<i class="icon-facebook-squared"></i>
				</a>

			</li>
			<li class="contact-menu__item">

				<a href="https://twitter.com/CropConnect" class="contact-menu__link contact-menu__item--icon">
					<i class="icon-twitter"></i>
				</a>

			</li>

			<li class="contact-menu__item">

				<div class="contact-menu__item--icon">
					<i class="icon-phone"></i>
				</div>
				<div class="contact-menu__item--text">
					<p><small>Phone:</small></p>
					<a class="contact-menu__link" href="tel:204-995-8997.">204.995.8997</a>
				</div>

			</li>

			<li class="contact-menu__item">

				<div class="contact-menu__item--icon">
					<i class="icon-paper-plane"></i>
				</div>
				<div class="contact-menu__item--text">
					<p><small>Email:</small></p>
					<a class="contact-menu__link" href="mailto:info@cropconnectconference.ca">info@cropconnectconference.ca</a>
				</div>

			</li>
		</ul>

			<?php
			if ( has_nav_menu( 'primary_navigation' ) ) :
				bem_menu( 'primary_navigation', 'main-menu', 'main-menu--nav-main' );
			endif;
			?>
		</nav>
		<div class="toggle-button">
			<a href="#"><i class="icon-menu"></i></a>
		</div>
	</div>


</header>
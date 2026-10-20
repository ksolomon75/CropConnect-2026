<?php
/**
 * Mobile navigation partial.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

?>
<nav id="js-menu" class="nav-mobile">

	<div class="nav-mobile__close">
		<a href="#"><i class="icon-cancel"></i></a>
	</div>

	<?php
	if ( has_nav_menu( 'primary_navigation' ) ) :
		bem_menu( 'primary_navigation', 'main-menu', 'main-menu--nav-mobile' );
	endif;
	?>

	<div class="nav-mobile__contact">
		<p>
			<i class="icon-phone"></i> <strong>Phone Number:</strong><br>
				Tel: 204.995.8997
		</p>

		<p><i class="icon-paper-plane"></i> <strong>Email Address:</strong><br>
		<a href="mailto:info@cropconnectconference.ca">info@cropconnectconference.ca</a></p>

		<p><i class="icon-twitter"></i> <strong>Twitter:</strong><br>
		<a href="http://twitter.com/@CropConnect">@CropConnect</a></p>


		<p><i class="icon-facebook-squared"></i> <strong>Facebook:</strong><br>
		<a href="https://www.facebook.com/cropconnectconference">cropconnectconference</a></p>
	</div>
</nav>
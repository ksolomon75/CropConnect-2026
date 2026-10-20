<?php
/**
 * Template Name: Contact Template
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;
while ( have_posts() ) :
	the_post();
	get_template_part( 'templates/page', 'header' );
	?>

	<!-- 	<div class="container">
		<main class="main">
		<?php // the_content(); ?>
		</main>
	</div> -->

	<div class="contact-section">
		<div class="container">

			<div class="contact__info">
				<h3>Contact Us Today</h3>

				<p><strong>CropConnect Conference 2022</strong></p>

				<p>
					<i class="icon-location"></i> <strong>Address:</strong><br>
					CropConnect<br>
					Box 188<br>
					38 – 4th Avenue NE<br>
					Carman, Manitoba<br>
					R0G 0J0
				</p>
				<p>
					<i class="icon-phone"></i> <strong>Phone Number:</strong><br>
					<a href="tel:204.995.8997">Tel: 204.995.8997</a>
				</p>

				<p><i class="icon-paper-plane"></i> <strong>Email Address:</strong><br>
				<a href="mailto:info@cropconnectconference.ca">info@cropconnectconference.ca</a></p>

				<p><i class="icon-twitter"></i> <strong>Twitter:</strong><br>
				<a href="http://twitter.com/@CropConnect">@CropConnect</a></p>

				<p><i class="icon-facebook"></i> <strong>Facebook:</strong><br>
				<a href="https://facebook.com/173605256411914">CropConnectConference</a></p>
			</div>
			<div class="contact__form">
				<h3>Get In Touch</h3>
				<?php echo do_shortcode( '[gravityform id="2" title="false" description="false"]' ); ?>
			</div>
		</div>
	</div>
	<div class="google-maps">
		<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2569.850659664047!2d-97.210221!3d49.901607!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x3ccb75336f3d504f!2sVictoria+Inn+Hotel+%26+Convention+Centre!5e0!3m2!1sen!2sus!4v1473733694615" width="800" height="600" frameborder="0" style="border:0" allowfullscreen></iframe>
	</div>

	<?php
endwhile;

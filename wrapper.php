<?php
/**
 * Theme wrapper template.
 *
 * Reproduces the previous theme's `base.php`: it opens the `.wrap` /
 * `.content` containers, hands control to whichever template WordPress
 * selected, then closes them and emits the footer.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<?php get_template_part( 'templates/head' ); ?>
	<body <?php body_class(); ?>>
		<?php wp_body_open(); ?>

		<?php
		get_template_part( 'templates/nav-mobile' );
		?>

		<div id="js-main">
			<!--[if IE]>
				<div class="alert alert-warning">
					<?php
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					_e( 'You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'sage' );
					?>
				</div>
			<![endif]-->
			<?php
			do_action( 'get_header' );
			get_template_part( 'templates/header' );
			?>
			<div class="wrap" role="document">
				<div class="content">

					<?php include templatePath(); ?>

				</div>
			</div>
			<?php
			do_action( 'get_footer' );
			get_template_part( 'templates/footer' );
			wp_footer();
			?>
		</div>
	</body>
</html>
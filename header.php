<?php
/**
 * Theme header template
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

global $views;

$headerLogo = getFieldValue( 'header.header_logo.url' ) ? getFieldValue( 'header.header_logo.url' ) : get_theme_file_uri( '/static/img/logo.svg' );

// Check conditions for displaying the hero section
$showHero = in_array(
	true,
	array(
		get_field( 'hero_style' ) === 'default',
		is_home(),
		is_archive(),
		is_single(),
		is_search(),
		is_404(),
	),
	true
);

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>
</head>

<body <?php echo body_class(); ?>>
	<a class="skip-link" href="#maincontent">
		<?php echo esc_html__( 'Skip to main content' ); ?>
	</a>

	<header role="banner" class="site-header bg-secondary flex flex-col items-center justify-start">
		<?php get_template_part( 'views/components/nav-aux' ); ?>

		<div class="header__nav-main container py-4 items-center grid gap-x-8 gap-y-0 grid-cols-[83px_auto] grid-rows-[1fr] justify-between">
			<a href="<?php bloginfo( 'url' ); ?>" class="site-header__logo block size-20">
				<img class="size-full" src="<?php echo esc_url( $headerLogo ); ?>" alt="<?php bloginfo( 'name' ); ?> logo"/>
			</a>

			<?php get_template_part( 'views/components/nav-main' ); ?>
		</div>
	</header>

	<main id="maincontent" class="overflow-hidden min-h-[78dvh]">
		<?php if ( $showHero ) : ?>
			<?php get_template_part( 'views/partials/page-hero' ); ?>
		<?php endif; ?>

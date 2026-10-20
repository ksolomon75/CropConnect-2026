<?php
/**
 * Block Name: Contact Info
 *
 * Display contact information from global fields with icons and optional form.
 *
 * @package BasicWP
 */

namespace BasicWP;

$classes = 'contact-info';
$wrapper = blockWrapperAttributes( $classes, $is_preview );
?>

<section <?php echo esc_attr( $wrapper ); ?>>
	<div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-1/2 p-6">
            <h2 class="text-2xl font-bold mb-4">Contact Information</h2>

            <div class="not-prose text-black my-4">
                <h3 class="mb-0"><?php echo esc_html__( 'Mailing Address', 'basicwp' ); ?></h3>
                <p class="my-0.5"><?php echo wp_kses_post( get_field( 'contact_info', 'option' )['address'] ); ?></p>

                <h3 class="mb-0"><?php echo esc_html__( 'Email', 'basicwp' ); ?></h3>
                <p class="my-0.5"><a class="hover:opacity-80 transition-colors duration-100" href="mailto:<?php echo esc_html( get_field( 'contact_info', 'option' )['email'] ); ?>"><?php echo esc_html( get_field( 'contact_info', 'option' )['email'] ); ?></a></p>

                <h3 class="mb-0"><?php echo esc_html__( 'Phone', 'basicwp' ); ?></h3>
                <p class="my-0.5"><a class="hover:opacity-80 transition-colors duration-100" href="tel:<?php echo esc_html( get_field( 'contact_info', 'option' )['phone'] ); ?>"><?php echo esc_html( get_field( 'contact_info', 'option' )['phone'] ); ?></a></p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 p-6">
            <InnerBlocks />
        </div>
    </div>
</section>

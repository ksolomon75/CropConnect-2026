<?php
/**
 * BasicWP Theme Hooks
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

/**
 * Add preconnect for Google fonts to head
 *
 * @return void
 */
add_action(
    'wp_head',
    function () {
        ?>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <?php
    },
    0
);

/**
 * Register the navigation menus.
 *
 * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
 */
register_nav_menus(
    array(
        'main_navigation'   => 'Main Navigation',
        'aux_navigation'    => 'Auxiliary Navigation',
        'footer_navigation' => 'Footer Navigation',
    )
);

/**
 * Whether the page hero should hold the main h1 of the page.
 */
add_filter(
    'include_page_title_in_hero',
    function ( $include_title ) {
        // Add post types that should not use the title in the hero.
        if ( is_singular( ( 'post' ) ) ) {
            return false;
        }

        return $include_title;
    }
);

/**
 * WP Cleanup
 */
function init() {
    // NOTE: Emoji scripts/styles and the core block stylesheets are
    // intentionally NOT removed. The previous theme shipped them, and page
    // content (Block widgets, columns, images) depends on them rendering.
    remove_action( 'wp_head', 'wp_generator' ); // WordPress version.
    remove_action( 'wp_head', 'rsd_link' ); // RSD link.
    remove_action( 'wp_head', 'wlwmanifest_link' ); // Windows Live Writer.
    remove_action( 'wp_head', 'wp_shortlink_wp_head' ); // Shortlink.
    remove_action( 'wp_head', 'rest_output_link_wp_head' ); // REST API link.
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' ); // oEmbed discovery links.
    remove_action( 'wp_head', 'rel_canonical' ); // Canonical URL.
    remove_action( 'wp_head', 'wp_resource_hints', 2 ); // DNS Prefetch.
    // NOTE: image auto-sizes are left enabled; disabling them rewrites the
    // `sizes` attribute on content images and changes the rendered markup.
    add_filter( 'xmlrpc_enabled', '__return_false' );

    // Add theme support features
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_theme_support(
        'html5',
        array(
            'caption',
            'comment-form',
            'comment-list',
            'gallery',
            'search-form',
        )
    );
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    // NOTE: 'responsive-embeds' is intentionally NOT enabled. The previous theme
    // did not enable it, and enabling it adds a `wp-embed-responsive` body class
    // plus an oEmbed JS filter, both of which change the rendered markup.
    add_theme_support( 'customize-selective-refresh-widgets' );
}

add_action( 'init', __NAMESPACE__ . '\\init', 1 );

/**
 * Allow SVG uploads
 */
add_filter(
    'wp_check_filetype_and_ext',
    function ( $data, $file, $filename, $mimes ) {
        global $wp_version;

        if ( '4.7.1' !== $wp_version ) {
            return $data;
        }

        $filetype = wp_check_filetype( $filename, $mimes );

        return array(
            'ext'             => $filetype['ext'],
            'type'            => $filetype['type'],
            'proper_filename' => $data['proper_filename'],
        );
    },
    10,
    4
);

add_filter(
    'upload_mimes',
    function ( $mimes ) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }
);

/**
 * Fix display issues with SVGs in admin
 */
add_action(
    'admin_head',
    function () {
        echo '
        <style type="text/css">
            .attachment-266x266, .thumbnail img {
                width: 100% !important;
                height: auto !important;
            }
        </style>
        ';
    }
);

/**
 * Filters the email address used as the sender in outgoing emails.
 *
 * This function allows you to modify the default "from" email address
 * used by WordPress when sending emails.
 *
 * @param string $old The original email address.
 * @return string The new email address to use as the sender.
 */
// phpcs:ignore
function new_mail_from( $old ) {
    return get_option( 'admin_email' );
}

/**
 * Filters the name used as the sender in outgoing emails.
 *
 * This function allows you to modify the default "from" name
 * used by WordPress when sending emails.
 *
 * @param string $old The original name.
 * @return string The new name to use as the sender.
 */
// phpcs:ignore
function new_mail_from_name( $old ) {
    return get_option( 'blogname' );
}

add_filter( 'wp_mail_from', __NAMESPACE__ . '\\new_mail_from' );
add_filter( 'wp_mail_from_name', __NAMESPACE__ . '\\new_mail_from_name' );

/**
 * Add child page template
 */
add_filter(
    'page_template',
    function ( $template ) {
        global $post;

        if ( $post->post_parent ) {
            // get top level parent page
            $parent = get_post(
                reset( array_reverse( get_post_ancestors( $post->ID ) ) )
            );

            $child_template = locate_template(
                array(
                    $parent->post_name . '-child.php',
                )
            );

            if ( $child_template ) {
                return $child_template;
            }
        }

        return $template;
    }
);

/**
 * Disable comments
 */
function disable_comments() {
    $post_types = get_post_types();

    foreach ( $post_types as $post_type ) {
        if ( post_type_supports( $post_type, 'comments' ) ) {
            remove_post_type_support( $post_type, 'comments' );
            remove_post_type_support( $post_type, 'trackbacks' );
        }
    }
}
add_action( 'admin_init', __NAMESPACE__ . '\\disable_comments' );

// Close comments
add_filter( 'comments_open', '__return_false', 20, 2 );
add_filter( 'pings_open', '__return_false', 20, 2 );

// Hide existing comments
add_filter( 'comments_array', '__return_empty_array', 10, 2 );

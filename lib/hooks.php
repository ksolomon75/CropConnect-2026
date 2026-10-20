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
 * Widget Areas
 *
 * Set up sidebar/widget areas for the theme.
 *
 * @return void
 */
add_action(
    'widgets_init',
    function () {
        $config = array(
            'before_widget' => '<div class="widget %1$s %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2>',
            'after_title'   => '</h2>',
        );

        $cfg_foot = array(
            'before_widget' => '<div class="widget %1$s %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4>',
            'after_title'   => '</h4>',
        );

        register_sidebar(
            array(
                'name' => 'Primary Sidebar',
                'id'   => 'sidebar-primary',
            ) + $config
        );

        register_sidebar(
            array(
                'name' => 'Page Sidebar',
                'id'   => 'sidebar-page',
            ) + $config
        );

        register_sidebar(
            array(
                'name' => 'Footer Area 1',
                'id'   => 'footer-1',
            ) + $cfg_foot
        );

        register_sidebar(
            array(
                'name' => 'Footer Area 2',
                'id'   => 'footer-2',
            ) + $cfg_foot
        );

        register_sidebar(
            array(
                'name' => 'Footer Area 3',
                'id'   => 'footer-3',
            ) + $cfg_foot
        );
    }
);

/**
 * Basic SEO
 *
 * {Site URL}: {Title}
 */
add_filter(
    'wp_title',
    function ( $title ) {
        $site_name = get_bloginfo( 'name' );

        return "{$site_name}: {$title}";
    }
);

/**
 * Excerpt
 */
add_filter(
    'excerpt_more',
    function () {
        return '&hellip;';
    }
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
 * Remove unused or replaced core blocks
 *
 * @return array List of allowed block types
 */
add_filter(
    'allowed_block_types_all',
    function () {
        // get all the registered blocks
        $blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();

        // then disable some of them
        unset( $blocks['core/media-text'] );
        unset( $blocks['core/calendar'] );
        unset( $blocks['core/buttons'] );
        unset( $blocks['core/search'] );

        // return the new list of allowed blocks
        return array_keys( $blocks );
    }
);

/**
 * WP Cleanup
 */
function init() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    wp_dequeue_style( 'wp-block-library' ); // Core block styles.
    wp_dequeue_style( 'wp-block-library-theme' ); // Block theme styles.
    wp_dequeue_style( 'global-styles' ); // Global styles.
    wp_dequeue_style( 'core-block-supports' ); // Core block supports.
    wp_dequeue_style( 'core-block-styles' ); // Core block styles.
    remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles', 1 );
    remove_action( 'wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles', 1 );
    remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
    remove_action( 'wp_head', 'wp_generator' ); // WordPress version.
    remove_action( 'wp_head', 'rsd_link' ); // RSD link.
    remove_action( 'wp_head', 'wlwmanifest_link' ); // Windows Live Writer.
    remove_action( 'wp_head', 'wp_shortlink_wp_head' ); // Shortlink.
    remove_action( 'wp_head', 'rest_output_link_wp_head' ); // REST API link.
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' ); // oEmbed discovery links.
    remove_action( 'wp_head', 'rel_canonical' ); // Canonical URL.
    remove_action( 'wp_head', 'wp_resource_hints', 2 ); // DNS Prefetch.
    add_filter( 'wp_img_tag_add_width_and_height_attr', '__return_false' ); // Disable intrinsic image size.
    add_filter( 'wp_img_tag_add_auto_sizes', '__return_false' ); // Disable auto sizes.
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
            'global-search-form',
            'script',
            'style',
        )
    );
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'responsive-embeds' );
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

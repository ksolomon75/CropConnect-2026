<?php
/**
 * Resources custom post type & taxonomies
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

/**
 * Class Resources
 *
 * This class is responsible for setting up the Resources post type and taxonomies.
 *
 * @package Basic-WP
 */
class Resources {
	/**
	 * Constructor for the class.
	 *
	 * Initializes the class and sets up any necessary properties or methods.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'registerPostType' ) );
		add_action( 'init', array( $this, 'registerTaxonomy' ) );
		add_filter( 'post_type_link', array( $this, 'postTypeLink' ), 10, 2 );
	}

	/**
	 * Registers a custom post type.
	 *
	 * This method is responsible for defining and registering a custom post type
	 * with WordPress. It should include all necessary arguments and labels
	 * required for the post type to function correctly.
	 *
	 * @return void
	 */
	public function registerPostType() {
		register_post_type(
			'resources',
			array(
				'labels'        => array(
					'name'               => 'Resources',
					'singular_name'      => 'Resource',
					'menu_name'          => 'Resources',
					'name_admin_bar'     => 'Resource',
					'add_new'            => 'Add New Resource',
					'add_new_item'       => 'Add New Resource',
					'edit_item'          => 'Edit Resource',
					'new_item'           => 'New Resource',
					'view_item'          => 'View Resource',
					'search_items'       => 'Search Resources',
					'not_found'          => 'No resources found',
					'not_found_in_trash' => 'No resources found in Trash',
				),
				'public'        => true,
				'has_archive'   => true,
				'rewrite'       => array( 'slug' => 'resources' ),
				'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields' ),
				'menu_position' => 20,
				'menu_icon'     => 'dashicons-hammer',
				'show_in_rest'  => true,
			)
		);
	}

	/**
	 * Registers a custom taxonomy.
	 *
	 * This method is responsible for defining and registering a custom taxonomy
	 * within the WordPress environment. It should include the necessary arguments
	 * and settings for the taxonomy to function as intended.
	 *
	 * @return void
	 */
	public function registerTaxonomy() {
		register_taxonomy(
			'resource_type',
			array( 'resources' ),
			array(
				'labels'            => array(
					'name'              => 'Resource Types',
					'singular_name'     => 'Resource Type',
					'search_items'      => 'Search Resource Types',
					'all_items'         => 'All Resource Types',
					'parent_item'       => 'Parent Resource Type',
					'parent_item_colon' => 'Parent Resource Type:',
					'edit_item'         => 'Edit Resource Type',
					'update_item'       => 'Update Resource Type',
					'add_new_item'      => 'Add New Resource Type',
					'new_item_name'     => 'New Resource Type Name',
					'menu_name'         => 'Resource Types',
				),
				'public'            => true,
				'hierarchical'      => true,
				'show_admin_column' => true,
				'rewrite'           => array(
					'slug'         => 'resources',
					'with_front'   => false,
					'hierarchical' => true,
				),
				'show_in_rest'      => true,
			)
		);
	}

	/**
	 * Filters the permalink for a post of a specific post type.
	 *
	 * @param string  $post_link The post's permalink.
	 * @param WP_Post $post      The post object.
	 * @return string             The filtered post permalink.
	 */
	public function postTypeLink( $post_link, $post ) {
		if ( 'resources' === $post->post_type ) {
			$terms = get_the_terms( $post->ID, 'resource_type' );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$term_slug = $terms[0]->slug;

				return home_url( "resources/{$term_slug}/{$post->post_name}" );
			}
		}

		return $post_link;
	}
}

new Resources();

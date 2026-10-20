<?php
/*
 *  On Theme Activation adds Home and News pages, sets up reading options for front page and posts page, and erases sample page and post
 */

 // phpcs:ignore
if ( isset( $_GET['activated'] ) && is_admin() ) {
	// Set Blog Description to nothing
	update_option( 'blogdescription', '' );

	// List of pages to create with nested structure
	$arrPages = array(
		'Home'                       => array(),
		'News'                       => array(),
		'Page Not Found (Error 404)' => array(),
		'Contact Us'                 => array(),
		/**
		 * Sample nested structure
		 *
		 * 'Parent Page' => array(
		 *     'Subpage 1' => array(
		 *         'Sub-subpage 1',
		 *         'Sub-subpage 2'
		 *     ),
		 *     'Subpage 2.2' => array()
		 * ),
		 */
	);

	foreach ( $arrPages as $pageTitle => $childPages ) {
		// phpcs:ignore
		$pageExists = get_page_by_title($pageTitle);

		if ( ! $pageExists ) {
			// Create the parent page
			$pageId = wp_insert_post(
				array(
					'post_title'   => $pageTitle,
					'post_content' => '',
					'post_type'    => 'page',
					'post_status'  => 'publish',
				)
			);

			// If there are child pages, create them under the parent page
			foreach ( $childPages as $childPageTitle => $subChildPages ) {
				if ( is_array( $subChildPages ) ) {
					$subpageId = wp_insert_post(
						array(
							'post_title'   => $childPageTitle,
							'post_content' => '',
							'post_type'    => 'page',
							'post_status'  => 'publish',
							'post_parent'  => $pageId,
						)
					);

					foreach ( $subChildPages as $subChildPageTitle ) {
						wp_insert_post(
							array(
								'post_title'   => $subChildPageTitle,
								'post_content' => '',
								'post_type'    => 'page',
								'post_status'  => 'publish',
								'post_parent'  => $subpageId,
							)
						);
					}
				} else {
					wp_insert_post(
						array(
							'post_title'   => $childPageTitle,
							'post_content' => '',
							'post_type'    => 'page',
							'post_status'  => 'publish',
							'post_parent'  => $pageId,
						)
					);
				}
			}
		}
	}

	// Use a static front page
	// phpcs:ignore
	$home = get_page_by_title('Home');
	update_option( 'page_on_front', $home->ID );
	update_option( 'show_on_front', 'page' );

	// Set the blog/news page
	// phpcs:ignore
	$news = get_page_by_title('News');
	update_option( 'page_for_posts', $news->ID );

	// Trash the samples
	wp_delete_post( 1, true );
	wp_delete_post( 2, true );

	// Flush rewrite rules to ensure new pages are recognized
	flush_rewrite_rules();

	/**
	 * Install and activate must-use plugins
	 */

	$muPlugins = array(
		array(
			'url'    => 'https://docs.vincentdevelopment.ca/files/advanced-custom-fields-pro.zip',
			'active' => true,
		),
		array(
			'url'    => 'https://docs.vincentdevelopment.ca/files/gravity-forms.zip',
			'active' => true,
		),
		array(
			'url'    => 'https://downloads.wordpress.org/plugin/simple-history.5.11.0.zip',
			'active' => true,
		),
		array(
			'url'    => 'https://downloads.wordpress.org/plugin/autodescription.5.1.2.zip',
			'active' => true,
		),
		array(
			'url'    => 'https://downloads.wordpress.org/plugin/better-search-replace.1.4.10.zip',
			'active' => false,
		),
		array(
			'url'    => 'https://downloads.wordpress.org/plugin/google-site-kit.1.153.0.zip',
			'active' => false,
		),
	);

	// Custom log file
	$logFile = WP_CONTENT_DIR . '/mu-plugin-install.log';

	/**
	 * Simple logging function.
	 *
	 * @param string $message The message to log.
	 */
	function log_message( $message ) {
		global $logFile;
		$timestamp = gmdate( 'Y-m-d H:i:s' );
		// phpcs:ignore
		file_put_contents( $logFile, "[$timestamp] $message\n", FILE_APPEND );
	}

	// Include necessary WordPress files
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	require_once ABSPATH . 'wp-admin/includes/plugin.php';

	// Force direct filesystem access
	add_filter( 'filesystem_method', fn() => 'direct' );

	global $wp_filesystem;
	if ( ! WP_Filesystem() ) {
		log_message( 'Filesystem initialization failed.' );
		return;
	}

	// Define a silent skin to avoid show_message() errors
	// phpcs:disable
	class Silent_Upgrader_Skin extends WP_Upgrader_Skin {
		public function feedback( $string, ...$args ) {}
		public function header() {}
		public function footer() {}
		public function error( $errors ) {
			log_message( 'Upgrader error: ' . print_r( $errors, true ) );
		}
		public function before() {}
		public function after() {}
	}
	// phpcs:enable

	$skin     = new Silent_Upgrader_Skin();
	$upgrader = new Plugin_Upgrader( $skin );

	// Process each plugin
	foreach ( $muPlugins as $plug ) {
		$plugUrl        = $plug['url'];
		$shouldActivate = ! empty( $plug['active'] );

		// Download plugin
		$response = wp_remote_get( $plugUrl );
		if ( is_wp_error( $response ) ) {
			log_message( "Failed to download plugin from {$plugUrl}: " . $response->get_error_message() );
			continue;
		}

		// Extract filename from URL
		$plugFileName = basename( wp_parse_url( $plugUrl, PHP_URL_PATH ) );

		// Save the plugin zip
		$plugZip = wp_upload_bits( $plugFileName, null, wp_remote_retrieve_body( $response ) );
		if ( $plugZip['error'] ) {
			log_message( "Failed to save plugin zip {$plugFileName}: " . $plugZip['error'] );
			continue;
		}

		// Install the plugin
		$installResult = $upgrader->install( $plugZip['file'] );
		if ( is_wp_error( $installResult ) ) {
			log_message( "Failed to install plugin {$plugFileName}: " . $installResult->get_error_message() );
			// Cleanup temp zip
			if ( file_exists( $plugZip['file'] ) ) {
				wp_delete_file( $plugZip['file'] );
			}
			continue;
		}

		// Get plugin info ( folder/main file )
		$plugInfo = $upgrader->plugin_info();
		log_message( "plugin_info for {$plugFileName}: {$plugInfo}" );

		if ( ! $plugInfo || ! file_exists( WP_PLUGIN_DIR . '/' . $plugInfo ) ) {
			log_message( "Could not determine installed plugin file for {$plugFileName}." );
			// Cleanup temp zip
			if ( file_exists( $plugZip['file'] ) ) {
				wp_delete_file( $plugZip['file'] );
			}
			continue;
		}

		log_message( "Successfully installed plugin {$plugInfo}." );

		// Attempt activation if marked active
		if ( $shouldActivate ) {
			$activateResult = activate_plugin( $plugInfo );
			if ( is_wp_error( $activateResult ) ) {
				log_message( "Failed to activate plugin {$plugInfo}: " . $activateResult->get_error_message() );
			} else {
				log_message( "Successfully activated plugin {$plugInfo}." );
			}
		} else {
			log_message( "Plugin {$plugInfo} installed but not activated ( per configuration )." );
		}

		// Cleanup temp zip
		if ( file_exists( $plugZip['file'] ) ) {
			wp_delete_file( $plugZip['file'] );
			log_message( "Deleted temporary zip file {$plugZip['file']}." );
		}
	}

	log_message( '=== Plugin installation and activation process completed ===' );
}

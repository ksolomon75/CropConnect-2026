<?php
/**
 * Page Sidebar
 *
 * @package BasicWP
 */

namespace BasicWP;

?>

<aside class="sidebar sidebar-page">
	<h2><?php esc_html_e( 'Page Sidebar', 'basicwp' ); ?></h2>
	<?php dynamic_sidebar( 'sidebar-page' ); ?>
</aside>

<?php
/**
 * isprithul theme.
 *
 * The homepage (front-page.php) is rendered on the server from the isprithul
 * core plugin's data; the scripts in assets/js add the schematic probe, the
 * filters, the photo viewer and the "Start a project" form on top.
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

define( 'ISP_THEME_VERSION', '1.1.1' );

require get_template_directory() . '/inc/setup.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/assets.php';
require get_template_directory() . '/inc/compat.php';

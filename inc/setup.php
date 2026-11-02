<?php
/**
 * Theme supports and small WordPress settings.
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		/* Ultimate Addons for Elementor (Header Footer Elementor): with this flag it no longer
		   swaps its own header and footer into the theme, so every page uses this theme's. */
		add_theme_support( 'header-footer-elementor' );
		$GLOBALS['content_width'] = 760;
	}
);

add_filter( 'excerpt_more', fn() => '…' );
add_filter( 'excerpt_length', fn() => 32 );

/* No emoji script: one request less on every page. */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

add_filter(
	'body_class',
	function ( $classes ) {
		$classes[] = 'isp';
		if ( isp_home_view() ) {
			$classes[] = 'isp-home';
		}
		return $classes;
	}
);

add_action(
	'admin_notices',
	function () {
		if ( ! function_exists( 'isp_content' ) && current_user_can( 'activate_plugins' ) ) {
			echo '<div class="notice notice-warning"><p><strong>isprithul theme:</strong> please activate the <em>isprithul core</em> plugin. It holds the projects and the homepage texts; until then the homepage shows the content of the page set as the front page.</p></div>';
		}
	}
);

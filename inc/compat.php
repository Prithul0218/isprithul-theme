<?php
/**
 * Getting along with the plugins already on isprithul.me.
 *
 * - Elementor: the homepage always uses this theme's front-page.php, even though
 *   the old Home page was built with Elementor. Other Elementor pages keep working.
 * - Ultimate Addons for Elementor: its header/footer stay out (see setup.php), and on
 *   the homepage its scroll-to-top button and reading-progress bar are removed.
 * - The homepage loads none of the page-builder styles and scripts it doesn't use.
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

add_filter(
	'template_include',
	function ( $template ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only check for Elementor's editor preview.
		if ( is_front_page() && isp_theme_ready() && ! isset( $_GET['elementor-preview'] ) ) {
			$front = locate_template( 'front-page.php' );
			if ( $front ) {
				return $front;
			}
		}
		return $template;
	},
	99999
);

/** Remove every callback on $hook that belongs to a class or function matching $pattern. */
function isp_remove_callbacks( string $hook, string $pattern ): void {
	global $wp_filter;
	if ( empty( $wp_filter[ $hook ] ) ) {
		return;
	}
	foreach ( $wp_filter[ $hook ]->callbacks as $priority => $callbacks ) {
		foreach ( $callbacks as $callback ) {
			$fn    = $callback['function'];
			$owner = '';
			if ( is_array( $fn ) ) {
				$owner = is_object( $fn[0] ) ? get_class( $fn[0] ) : (string) $fn[0];
			} elseif ( $fn instanceof Closure ) {
				$scope = ( new ReflectionFunction( $fn ) )->getClosureScopeClass();
				$owner = $scope ? $scope->getName() : '';
			} elseif ( is_string( $fn ) ) {
				$owner = $fn;
			}
			if ( '' !== $owner && preg_match( $pattern, $owner ) ) {
				remove_action( $hook, $fn, $priority );
			}
		}
	}
}

/** Styles and scripts the homepage never uses (page builders, add-ons, block styles). */
function isp_homepage_skips( string $handle ): bool {
	$prefixes = array( 'elementor', 'e-', 'eael', 'pa-', 'premium', 'hfe', 'uael', 'swiper', 'font-awesome', 'contact-form-7', 'wpcf7', 'wp-block-library', 'global-styles', 'classic-theme-styles', 'jquery', 'google-fonts', 'aioseo/css/src/vue/standalone/blocks' );
	foreach ( $prefixes as $prefix ) {
		if ( str_starts_with( $handle, $prefix ) ) {
			return true;
		}
	}
	return false;
}

function isp_homepage_dequeue(): void {
	foreach ( array( wp_styles(), wp_scripts() ) as $deps ) {
		foreach ( (array) $deps->queue as $handle ) {
			if ( isp_homepage_skips( $handle ) ) {
				$deps->dequeue( $handle );
			}
		}
	}
}

/*
 * The homepage is this theme's own template, so the old Elementor Home page, its
 * Google Fonts, Elementor add-ons and the Ultimate Addons extras (scroll-to-top, reading
 * progress) stay out.
 * Runs after Elementor sets up its front end (template_redirect, priority 10).
 */
add_action(
	'template_redirect',
	function () {
		if ( ! is_front_page() || ! isp_theme_ready() ) {
			return;
		}
		foreach ( array( 'wp_head', 'wp_body_open', 'wp_enqueue_scripts', 'wp_footer', 'wp_print_footer_scripts', 'body_class' ) as $hook ) {
			isp_remove_callbacks( $hook, '/^(HFE|UAEL?)[\\\\_]|Header_Footer_Elementor|^(Elementor|Essential_Addons_Elementor|PremiumAddons)\\\\/i' );
		}
		add_action( 'wp_enqueue_scripts', 'isp_homepage_dequeue', 9999 );
		add_action( 'wp_print_styles', 'isp_homepage_dequeue', 1 );
		add_action( 'wp_print_scripts', 'isp_homepage_dequeue', 1 );
		add_action( 'wp_print_footer_scripts', 'isp_homepage_dequeue', 1 );
		// Last safety net for stylesheets printed some other way. (Scripts are only dequeued, so
		// anything another script still depends on keeps loading.)
		add_filter( 'style_loader_tag', fn( $tag, $handle ) => isp_homepage_skips( $handle ) ? '' : $tag, 999, 2 );
	},
	99
);

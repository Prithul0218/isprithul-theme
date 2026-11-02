<?php
/**
 * Styles, fonts and scripts.
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_enqueue_scripts',
	function () {
		$uri  = get_template_directory_uri() . '/assets/';
		$ver  = ISP_THEME_VERSION;
		$home = isp_home_view();

		wp_enqueue_style( 'isp-fonts', $uri . 'css/fonts.css', array(), $ver );
		wp_enqueue_style( 'isp-tokens', $uri . 'css/tokens.css', array( 'isp-fonts' ), $ver );
		wp_enqueue_style( 'isp-base', $uri . 'css/base.css', array( 'isp-tokens' ), $ver );
		wp_enqueue_style( 'isp-components', $uri . 'css/components.css', array( 'isp-base' ), $ver );
		wp_enqueue_style( 'isp-wp', $uri . 'css/wp.css', array( 'isp-components' ), $ver );
		if ( $home ) {
			wp_enqueue_style( 'isp-schematic', $uri . 'css/schematic.css', array( 'isp-components' ), $ver );
			wp_enqueue_style( 'isp-home', $uri . 'css/home.css', array( 'isp-schematic' ), $ver );
		}

		if ( ! isp_theme_ready() ) {
			return;
		}
		$defer = array(
			'strategy'  => 'defer',
			'in_footer' => true,
		);
		wp_enqueue_script( 'isp-core', $uri . 'js/core.js', array(), $ver, $defer );
		wp_enqueue_script( 'isp-ui', $uri . 'js/ui.js', array( 'isp-core' ), $ver, $defer );
		if ( $home ) {
			wp_enqueue_script( 'isp-projects', $uri . 'js/projects.js', array( 'isp-ui' ), $ver, $defer );
			wp_enqueue_script( 'isp-schematic', $uri . 'js/schematic.js', array( 'isp-projects' ), $ver, $defer );
		}
	}
);

/*
 * The page data for the scripts, as a JSON block (not a script that runs), so page
 * optimizers that delay or combine inline scripts (LiteSpeed, Cloudflare) can't run
 * it in the wrong order. core.js reads it.
 */
add_action(
	'wp_footer',
	function () {
		if ( ! isp_theme_ready() ) {
			return;
		}
		$home = isp_home_view();
		$data = array(
			'content'       => isp_content_for_script( $home ),
			'homeURL'       => home_url( '/' ),
			'isHome'        => $home,
			'legalURL'      => isp_legal_url(),
			'briefEndpoint' => rest_url( 'isprithul/v1/brief' ),
		);
		wp_print_inline_script_tag(
			(string) wp_json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ),
			array(
				'type' => 'application/json',
				'id'   => 'isp-data',
			)
		);
	},
	5
);

/* Fonts the first screen needs, before the stylesheets ask for them. */
add_action(
	'wp_head',
	function () {
		$fonts = get_template_directory_uri() . '/assets/fonts/';
		foreach ( array( 'bricolage-grotesque-opsz.woff2', 'atkinson-hyperlegible-400-normal.woff2' ) as $file ) {
			printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( $fonts . $file ) );
		}
	},
	2
);

/* A saved SCH/PCB choice applies before the first paint (no flash of the other theme). */
add_action(
	'wp_head',
	function () {
		echo "<script>try{var t=localStorage.getItem('isp-theme');if(t==='light'||t==='dark')document.documentElement.dataset.theme=t}catch(e){}</script>\n";
		echo '<meta name="theme-color" content="#F5F4EF" media="(prefers-color-scheme: light)">' . "\n";
		echo '<meta name="theme-color" content="#001023" media="(prefers-color-scheme: dark)">' . "\n";
	},
	1
);

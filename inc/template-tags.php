<?php
/**
 * Small helpers the templates share (server-side twins of the helpers in assets/js).
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

/** True when the isprithul core plugin (projects + texts) is active. */
function isp_theme_ready(): bool {
	return function_exists( 'isp_content' );
}

/** True inside Elementor's editor preview, which needs the page's own content and assets. */
function isp_builder_preview(): bool {
	return isset( $_GET['elementor-preview'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only check.
}

/** True when this request shows the new homepage (plugin active, front page, not an editor preview). */
function isp_home_view(): bool {
	return is_front_page() && isp_theme_ready() && ! isp_builder_preview();
}

/**
 * True when nothing on this page was built with Elementor: the homepage, and any page,
 * post, archive or 404 whose content is ordinary blocks (the Legal page, for example).
 * Those pages then skip the page-builder files entirely.
 */
function isp_lean_view(): bool {
	if ( isp_home_view() ) {
		return true;
	}
	if ( is_admin() || isp_builder_preview() || is_front_page() ) {
		return false;
	}
	if ( is_singular() ) {
		return 'builder' !== get_post_meta( get_queried_object_id(), '_elementor_edit_mode', true );
	}
	return is_404() || is_search() || is_archive() || is_home();
}

/** A homepage text by its place on the page, e.g. isp_c( 'hero.title' ). */
function isp_c( string $path, $fallback = '' ) {
	return isp_theme_ready() ? isp_get( $path, $fallback ) : $fallback;
}

function isp_available(): bool {
	return (bool) isp_c( 'site.available', true );
}

/** "#projects"-style links point at the homepage when this page isn't the homepage. */
function isp_link( string $href ): string {
	if ( '' !== $href && '#' === $href[0] && ! is_front_page() ) {
		return home_url( '/' ) . $href;
	}
	return $href;
}

/** "binder-jet" never breaks at its hyphen. Returns escaped HTML. */
function isp_keep_words( string $text ): string {
	return (string) preg_replace( '/(\S+-\S+)/u', '<span class="nw">$1</span>', esc_html( $text ) );
}

function isp_legal_url(): string {
	$id = (int) isp_c( 'footer.legalPage', 0 );
	if ( $id && 'publish' === get_post_status( $id ) ) {
		return (string) get_permalink( $id );
	}
	return home_url( '/legal/' );
}

/** The ISP-header logo (6 pins + key). */
function isp_logo_svg( int $width = 34 ): string {
	$height = (int) round( $width * 0.78 );
	return '<svg class="logo-mark" width="' . $width . '" height="' . $height . '" viewBox="0 0 44 34" aria-hidden="true">' .
		'<rect class="lm-body" x="1" y="1" width="42" height="32" rx="4"/>' .
		'<rect class="lm-pin" x="8" y="7" width="7" height="7" rx="1"/><rect class="lm-pin" x="18.5" y="7" width="7" height="7" rx="1"/><rect class="lm-pin" x="29" y="7" width="7" height="7" rx="1"/>' .
		'<rect class="lm-pin" x="8" y="20" width="7" height="7" rx="1"/><rect class="lm-pin" x="18.5" y="20" width="7" height="7" rx="1"/><rect class="lm-pin" x="29" y="20" width="7" height="7" rx="1"/>' .
		'<path class="lm-key" d="M4 30V24l5 6z"/></svg>';
}

function isp_brand( int $size ): void {
	$home = is_front_page();
	printf(
		'<a class="brand" href="%s" aria-label="%s">%s<span><span class="brand__isp">isp</span>rithul</span></a>',
		esc_url( $home ? '#top' : home_url( '/' ) ),
		esc_attr( $home ? 'isprithul, back to top' : 'isprithul, home' ),
		isp_logo_svg( $size ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG.
	);
}

/**
 * A photo, or an empty frame labelled with the shot that belongs there.
 * $photo comes from isp_photo() (plugin). $size: card | thumb | medium.
 */
function isp_photo_html( ?array $photo, string $alt = '', string $size = 'card', array $opts = array() ): string {
	if ( ! $photo || empty( $photo['id'] ) ) {
		$camera = '<svg class="photo-slot__icon" width="26" height="26" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h3l2-2h6l2 2h3v12H4z M12 10.5a3.5 3.5 0 1 0 0 7a3.5 3.5 0 1 0 0-7z" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>';
		$label  = empty( $opts['compact'] ) ? '<span class="photo-slot__label">' . esc_html( $opts['label'] ?? 'Photo coming' ) . '</span>' : '';
		return '<span class="photo-slot">' . $camera . $label . '</span>';
	}
	$wp_size = array(
		'card'   => 'medium_large',
		'thumb'  => 'thumbnail',
		'medium' => 'medium',
	)[ $size ] ?? 'medium_large';
	$attrs   = array(
		'class'     => 'ph',
		'alt'       => '' !== $photo['alt'] && empty( $opts['compact'] ) ? $photo['alt'] : $alt,
		'loading'   => empty( $opts['eager'] ) ? 'lazy' : 'eager',
		'decoding'  => 'async',
		'data-tall' => $photo['h'] > $photo['w'] * 1.05 ? 'true' : 'false',
	);
	if ( ! empty( $opts['sizes'] ) ) {
		$attrs['sizes'] = $opts['sizes'];
	}
	return (string) wp_get_attachment_image( (int) $photo['id'], $wp_size, false, $attrs );
}

/**
 * Opening tag of a frame that crops (the card cover): a portrait photo shows whole
 * on a blurred copy of itself (twin of markOrientation() in core.js).
 */
function isp_frame_open( string $class, ?array $photo ): string {
	$tall = $photo && ! empty( $photo['h'] ) && $photo['h'] > $photo['w'] * 1.05;
	return '<div class="' . esc_attr( $class . ( $tall ? ' is-tall' : '' ) ) . '"' .
		( $tall ? ' style="--ph-bg:url(&quot;' . esc_url( $photo['card'] ) . '&quot;)"' : '' ) . '>';
}

/** The answer link of one "Is Prithul ___?" question (twin of setAnswer() in ui.js). */
function isp_kicker_answer( array $q ): array {
	if ( ! empty( $q['open'] ) ) {
		return array( '#p-' . $q['open'], '', $q['a'] );
	}
	if ( ! empty( $q['filter'] ) ) {
		return array( '#projects', ' data-filter-to="' . esc_attr( $q['filter'] ) . '"', $q['a'] );
	}
	$text = isp_available() || empty( $q['aBooked'] ) ? $q['a'] : $q['aBooked'];
	return array( '#contact', ' data-open-brief', $text );
}

<?php
/**
 * Test-point chips under the schematic, one per service (U1 selected first).
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

$isp_wiring = array(
	'u1' => array( 'TP1', 'isp' ),
	'u2' => array( 'TP2', 'i2c' ),
	'u3' => array( 'TP3', 'motion' ),
);
foreach ( (array) isp_c( 'services.list', array() ) as $isp_s ) {
	if ( ! isset( $isp_wiring[ $isp_s['id'] ] ) ) {
		continue;
	}
	printf(
		'<button type="button" class="tp-chip" data-tp-chip="%1$s" aria-pressed="%2$s"><span class="dot" aria-hidden="true"></span><b>%3$s</b><span class="tp-chip__name">%4$s</span></button>',
		esc_attr( $isp_wiring[ $isp_s['id'] ][1] ),
		'u1' === $isp_s['id'] ? 'true' : 'false',
		esc_html( $isp_wiring[ $isp_s['id'] ][0] ),
		esc_html( $isp_s['name'] )
	);
}

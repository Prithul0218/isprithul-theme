<?php
/**
 * The probe card as it first appears (U1, firmware). schematic.js redraws it on each click.
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

$isp_s       = isp_c( 'services.list' )[0] ?? null;
$isp_probe   = isp_c( 'probe' );
$isp_matches = array_values( array_filter( (array) isp_c( 'projects', array() ), fn( $p ) => in_array( 'u1', $p['services'], true ) ) );
if ( ! $isp_s ) {
	return;
}
?>
<p class="probe__name"><?php echo esc_html( $isp_s['name'] ); ?></p>
<p class="probe__line"><?php echo esc_html( $isp_s['text'] ); ?></p>
<div class="probe__row"><div class="probe__seen">
<?php foreach ( array_slice( $isp_matches, 0, 3 ) as $isp_p ) : ?>
	<button type="button" data-open-project="<?php echo esc_attr( $isp_p['id'] ); ?>" aria-label="<?php echo esc_attr( $isp_p['title'] ); ?>" title="<?php echo esc_attr( $isp_p['title'] ); ?>"><?php echo isp_photo_html( $isp_p['photos'][0] ?? null, '', 'thumb', array( 'compact' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
<?php endforeach; ?>
</div><button type="button" class="btn btn--sm btn--ghost" data-filter-to="u1"><?php echo esc_html( $isp_probe['seeAll'] . ' ' . count( $isp_matches ) ); ?></button></div>

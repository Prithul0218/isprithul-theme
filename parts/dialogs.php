<?php
/**
 * Dialogs: the project photo viewer (homepage) and the "Start a project" form (every page).
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

if ( ! isp_theme_ready() ) {
	return;
}
$isp_ps    = isp_c( 'projectsSection' );
$isp_b     = isp_c( 'brief' );
$isp_field = function ( $id, $label, $input ) {
	printf(
		'<div class="field"><label for="%1$s">%2$s</label>%3$s<p class="field__err" id="%1$s-err" hidden></p></div>',
		esc_attr( $id ),
		esc_html( $label ),
		$input // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts below.
	);
};
if ( isp_home_view() ) :
	?>
<dialog class="dlg" id="qv" aria-labelledby="qv-title">
	<button type="button" class="dlg__close" data-close aria-label="Close">✕</button>
	<div class="qv">
		<div class="qv__media"><div class="qv__stage" data-qv-stage></div><div class="qv__thumbs" data-qv-thumbs></div></div>
		<div class="qv__info">
			<p class="qv__meta" data-qv-meta></p>
			<h2 id="qv-title" data-qv-title></h2>
			<p class="qv__summary" data-qv-summary></p>
			<figure class="qv__quote" data-qv-quote hidden><blockquote></blockquote><figcaption></figcaption></figure>
			<dl class="qv__facts" data-qv-facts></dl>
			<p class="qv__links" data-qv-links hidden></p>
			<div class="qv__actions"><button type="button" class="btn btn--primary" data-open-brief data-qv-similar><?php echo esc_html( $isp_ps['similarButton'] ); ?></button></div>
		</div>
	</div>
	<div class="qv__bar"><button type="button" data-qv-prev aria-label="Previous project"><?php echo esc_html( $isp_ps['prev'] ); ?></button>
		<span class="qv__count" data-qv-count></span>
		<button type="button" data-qv-next aria-label="Next project"><?php echo esc_html( $isp_ps['next'] ); ?></button></div>
</dialog>
<?php endif; ?>
<dialog class="dlg dlg--brief" id="brief" aria-labelledby="brief-title">
	<button type="button" class="dlg__close" data-close aria-label="Close">✕</button>
	<form class="brief" data-brief-form novalidate>
		<div class="brief__head"><h2 id="brief-title"><?php echo esc_html( $isp_b['title'] ); ?></h2>
			<p class="brief__intro"><?php echo esc_html( $isp_b['intro'] ); ?></p></div>
		<div class="brief__row">
			<?php
			$isp_field( 'b-name', $isp_b['name'], '<input id="b-name" name="name" autocomplete="name" maxlength="120" required>' );
			$isp_field( 'b-email', $isp_b['email'], '<input id="b-email" name="email" type="email" autocomplete="email" maxlength="200" required>' );
			?>
		</div>
		<?php $isp_field( 'b-what', $isp_b['what'], '<textarea id="b-what" name="what" maxlength="6000" required></textarea>' ); ?>
		<div class="brief__row">
			<?php
			$isp_options = '';
			foreach ( (array) isp_c( 'services.list', array() ) as $isp_s ) {
				$isp_options .= '<option>' . esc_html( $isp_s['name'] ) . '</option>';
			}
			$isp_options .= '<option>' . esc_html( $isp_b['typeOther'] ) . '</option>';
			$isp_field( 'b-type', $isp_b['type'], '<select id="b-type" name="type">' . $isp_options . '</select>' );
			$isp_times = '';
			foreach ( (array) $isp_b['timelineOptions'] as $isp_o ) {
				$isp_times .= '<option>' . esc_html( $isp_o ) . '</option>';
			}
			$isp_field( 'b-when', $isp_b['timeline'], '<select id="b-when" name="when">' . $isp_times . '</select>' );
			?>
		</div>
		<?php $isp_field( 'b-budget', $isp_b['budget'], '<input id="b-budget" name="budget" maxlength="200">' ); ?>
		<p class="brief__hp" aria-hidden="true"><label>Website <input name="website" tabindex="-1" autocomplete="off"></label></p>
		<button type="submit" class="btn btn--primary brief__send"><?php echo esc_html( $isp_b['send'] ); ?></button>
	</form>
	<div class="brief__done" data-brief-done hidden>
		<h2 tabindex="-1" data-brief-done-title><?php echo esc_html( $isp_b['doneTitle'] ); ?></h2>
		<p><?php echo esc_html( $isp_b['doneText'] ); ?></p>
		<button type="button" class="btn btn--ghost" data-close><?php echo esc_html( $isp_b['close'] ); ?></button>
	</div>
</dialog>
<div class="toast" data-toast role="status" aria-live="polite" hidden></div>

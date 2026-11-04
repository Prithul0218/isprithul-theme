<?php
/**
 * Homepage: contact, drawn like a KiCad title block.
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

$isp_ct     = isp_c( 'contact' );
$isp_status = isp_available() ? isp_c( 'site.statusOn' ) : isp_c( 'site.statusOff' );
?>
<section class="sec" id="contact" aria-labelledby="contact-title">
	<div class="wrap">
		<div class="tblock">
			<div class="tblock__main">
				<h2 id="contact-title"><?php echo esc_html( $isp_ct['title'] ); ?></h2>
				<p class="status"><span class="led" aria-hidden="true"></span><span data-status-text><?php echo esc_html( $isp_status ); ?></span></p>
				<div class="hero__cta"><button type="button" class="btn btn--primary" data-open-brief><?php echo esc_html( $isp_ct['button'] ); ?></button></div>
			</div>
			<div class="tblock__cells">
				<div class="tblock__cell"><span class="tblock__k"><?php echo esc_html( $isp_ct['emailLabel'] ); ?></span><span class="tblock__v"><?php echo function_exists( 'isp_email_html' ) ? isp_email_html() : '<span data-email>' . esc_html( str_replace( '@', ' [at] ', (string) isp_c( 'site.email' ) ) ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?><button type="button" class="copy-btn" data-copy-email><?php echo esc_html( $isp_ct['copy'] ); ?></button></span></div>
				<div class="tblock__cell"><span class="tblock__k"><?php echo esc_html( $isp_ct['basedLabel'] ); ?></span><span class="tblock__v"><?php echo esc_html( $isp_ct['based'] ); ?></span></div>
				<div class="tblock__cell"><span class="tblock__k"><?php echo esc_html( $isp_ct['replyLabel'] ); ?></span><span class="tblock__v"><?php echo esc_html( $isp_ct['reply'] ); ?></span></div>
			</div>
		</div>
	</div>
</section>

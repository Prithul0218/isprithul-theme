<?php
/**
 * Site header: logo, menu, SCH/PCB switch, "Start a project", phone menu.
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

$isp_nav    = (array) isp_c( 'site.nav', array() );
$isp_button = (string) isp_c( 'site.headerButton', 'Start a project' );
$isp_links  = '';
foreach ( $isp_nav as $isp_item ) {
	$isp_links .= '<a href="' . esc_url( isp_link( $isp_item['href'] ) ) . '">' . esc_html( $isp_item['label'] ) . '</a>';
}
?>
<header class="site-header">
	<div class="wrap hdr">
		<?php isp_brand( 34 ); ?>
		<nav class="nav" aria-label="Main"><?php echo $isp_links; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></nav>
		<div class="hdr__tools">
			<div class="theme-switch" role="group" aria-label="Color theme">
				<button type="button" data-theme-set="light" title="Light: KiCad schematic colors" aria-label="Light theme, KiCad schematic colors">SCH</button>
				<button type="button" data-theme-set="dark" title="Dark: KiCad PCB colors" aria-label="Dark theme, KiCad PCB colors">PCB</button>
			</div>
			<a class="btn btn--primary btn--sm hdr__cta" href="<?php echo esc_url( isp_link( '#contact' ) ); ?>" data-open-brief><?php echo esc_html( $isp_button ); ?></a>
			<button type="button" class="menu-btn" aria-expanded="false" aria-controls="mnav" data-menu><span class="menu-btn__bars" aria-hidden="true"><i></i><i></i><i></i></span><span class="menu-btn__label">Menu</span></button>
		</div>
	</div>
	<nav class="mnav wrap" id="mnav" aria-label="Mobile" hidden>
		<?php echo $isp_links; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>
		<a class="btn btn--primary" href="<?php echo esc_url( isp_link( '#contact' ) ); ?>" data-open-brief><?php echo esc_html( $isp_button ); ?></a>
	</nav>
</header>

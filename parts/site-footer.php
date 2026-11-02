<?php
/**
 * Site footer: logo, menu, elsewhere links, copyright and the Legal link.
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;
?>
<footer class="site-footer">
	<div class="wrap ftr">
		<?php isp_brand( 30 ); ?>
		<nav class="ftr__links" aria-label="Footer">
			<?php foreach ( (array) isp_c( 'site.nav', array() ) as $isp_item ) : ?>
				<a href="<?php echo esc_url( isp_link( $isp_item['href'] ) ); ?>"><?php echo esc_html( $isp_item['label'] ); ?></a>
			<?php endforeach; ?>
		</nav>
		<nav class="ftr__links" aria-label="Elsewhere">
			<?php foreach ( (array) isp_c( 'site.socials', array() ) as $isp_item ) : ?>
				<a href="<?php echo esc_url( $isp_item['href'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $isp_item['label'] ); ?></a>
			<?php endforeach; ?>
		</nav>
		<div class="ftr__silk">
			<p><span><?php echo esc_html( isp_c( 'footer.copyright', '© ' . wp_date( 'Y' ) . ' ' . get_bloginfo( 'name' ) ) ); ?></span> · <a href="<?php echo esc_url( isp_legal_url() ); ?>" class="link"><?php echo esc_html( isp_c( 'footer.legal', 'Legal' ) ); ?></a></p>
			<p><?php echo esc_html( isp_c( 'footer.rev', '' ) ); ?></p>
		</div>
	</div>
</footer>

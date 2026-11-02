<?php
/**
 * Homepage: what I do (the three services) and how a project runs.
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

$isp_svc  = isp_c( 'services' );
$isp_refs = array(
	'u1' => 'U1',
	'u2' => 'U2',
	'u3' => 'U3',
);
?>
<section class="sec" id="services" aria-labelledby="services-title">
	<div class="wrap">
		<div class="sec__head"><h2 class="sec__title sec__title--minor" id="services-title"><?php echo esc_html( $isp_svc['title'] ); ?></h2></div>
		<div class="svc-grid" data-services>
			<?php foreach ( $isp_svc['list'] as $isp_s ) : ?>
			<article class="svc"><p class="svc__ref"><?php echo esc_html( $isp_refs[ $isp_s['id'] ] ?? '' ); ?></p>
				<h3><?php echo esc_html( $isp_s['name'] ); ?></h3>
				<p class="svc__text"><?php echo esc_html( $isp_s['text'] ); ?></p>
				<button type="button" class="link-btn" data-filter-to="<?php echo esc_attr( $isp_s['id'] ); ?>"><?php echo esc_html( $isp_s['link'] ); ?></button></article>
			<?php endforeach; ?>
		</div>
		<div class="process">
			<h3 class="process__title"><?php echo esc_html( $isp_svc['processTitle'] ); ?></h3>
			<ol class="wire-list" data-process>
				<?php foreach ( $isp_svc['process'] as $isp_x ) : ?>
				<li><span class="wire-list__k"><?php echo esc_html( $isp_x['step'] ); ?></span><span class="wire-list__t"><?php echo esc_html( $isp_x['title'] ); ?></span><span class="wire-list__d"><?php echo esc_html( $isp_x['text'] ); ?></span></li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>

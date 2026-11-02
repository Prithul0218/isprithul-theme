<?php
/**
 * Homepage: experience timeline.
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

$isp_ex = isp_c( 'experience' );
?>
<section class="sec" id="experience" aria-labelledby="experience-title">
	<div class="wrap">
		<div class="sec__head"><h2 class="sec__title sec__title--minor" id="experience-title"><?php echo esc_html( $isp_ex['title'] ); ?></h2></div>
		<ol class="wire-list" data-experience>
			<?php foreach ( $isp_ex['list'] as $isp_x ) : ?>
			<li><span class="wire-list__k"><?php echo esc_html( $isp_x['when'] ); ?></span><span class="wire-list__t"><?php echo esc_html( $isp_x['title'] ); ?></span><span class="wire-list__d"><?php echo esc_html( $isp_x['where'] ); ?></span></li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

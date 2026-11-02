<?php
/**
 * Homepage: about (portrait, text, facts).
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

$isp_ab = isp_c( 'about' );
?>
<section class="sec" id="about" aria-labelledby="about-title">
	<div class="wrap about">
		<div class="about__photo" data-about-photo><?php echo isp_photo_html( $isp_ab['photo'], 'Portrait of ' . isp_c( 'site.name' ), 'medium', array( 'label' => 'Portrait' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<div class="about__text">
			<h2 class="sec__title sec__title--minor" id="about-title"><?php echo esc_html( $isp_ab['title'] ); ?></h2>
			<div class="about__body">
				<?php foreach ( $isp_ab['text'] as $isp_x ) : ?>
				<p><?php echo esc_html( $isp_x ); ?></p>
				<?php endforeach; ?>
			</div>
			<ul class="about__facts">
				<?php foreach ( $isp_ab['facts'] as $isp_x ) : ?>
				<li><?php echo esc_html( $isp_x ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>

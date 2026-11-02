<?php
/**
 * Homepage: what clients say (numbers, the featured quote, short quotes).
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

$isp_cl = isp_c( 'clients' );
$isp_f  = $isp_cl['featured'];
?>
<section class="sec" id="clients" aria-labelledby="clients-title">
	<div class="wrap">
		<div class="sec__head">
			<h2 class="sec__title sec__title--minor" id="clients-title"><?php echo esc_html( $isp_cl['title'] ); ?></h2>
			<?php if ( '' !== $isp_cl['link']['label'] && '' !== $isp_cl['link']['href'] ) : ?>
				<a class="link mono" href="<?php echo esc_url( $isp_cl['link']['href'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $isp_cl['link']['label'] ); ?></a>
			<?php endif; ?>
		</div>
		<div class="stats" data-stats>
			<?php foreach ( $isp_cl['stats'] as $isp_x ) : ?>
			<div class="stat"><span class="stat__n"><?php echo esc_html( $isp_x['n'] ); ?></span><span class="stat__l"><?php echo esc_html( $isp_x['label'] ); ?></span></div>
			<?php endforeach; ?>
		</div>
		<?php if ( '' !== $isp_f['text'] ) : ?>
		<div data-featured><figure class="featured"><blockquote class="featured__text">“<?php echo esc_html( $isp_f['text'] ); ?>”</blockquote>
			<figcaption class="featured__by"><span class="featured__name"><?php echo esc_html( $isp_f['name'] ); ?></span><span class="featured__role"><?php echo esc_html( $isp_f['role'] ); ?></span><?php if ( '' !== $isp_f['source'] ) : ?><span class="featured__src"><?php echo esc_html( $isp_f['source'] ); ?></span><?php endif; ?></figcaption></figure></div>
		<?php endif; ?>
		<div class="quotes" data-quotes>
			<?php foreach ( $isp_cl['quotes'] as $isp_x ) : ?>
			<figure class="quote"><blockquote>“<?php echo esc_html( $isp_x['text'] ); ?>”</blockquote><figcaption><?php echo esc_html( $isp_x['who'] ); ?></figcaption></figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>

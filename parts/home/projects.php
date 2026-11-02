<?php
/**
 * Homepage: project cards with filters. projects.js opens the photo viewer.
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

$isp_ps       = isp_c( 'projectsSection' );
$isp_projects = (array) isp_c( 'projects', array() );
?>
<section class="sec" id="projects" aria-labelledby="projects-title">
	<div class="wrap">
		<div class="sec__head">
			<h2 class="sec__title" id="projects-title"><?php echo esc_html( $isp_ps['title'] ); ?></h2>
			<div class="projects__tools">
				<div class="filters" data-filters role="group" aria-label="Filter projects">
					<button type="button" class="chip" data-filter="all" aria-pressed="true"><?php echo esc_html( $isp_ps['all'] ); ?></button>
					<?php foreach ( $isp_ps['groups'] as $isp_g ) : ?>
						<button type="button" class="chip" data-filter="<?php echo esc_attr( $isp_g['id'] ); ?>" aria-pressed="false"><?php echo esc_html( $isp_g['label'] ); ?></button>
					<?php endforeach; ?>
				</div>
				<button type="button" class="chip chip--random" data-random title="Open a random project">
					<svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><rect x="1.5" y="1.5" width="13" height="13" rx="2.5" fill="none" stroke="currentColor" stroke-width="1.4"/><circle cx="5" cy="5" r="1.2" fill="currentColor"/><circle cx="11" cy="11" r="1.2" fill="currentColor"/><circle cx="8" cy="8" r="1.2" fill="currentColor"/></svg>
					<span><?php echo esc_html( $isp_ps['surprise'] ); ?></span>
				</button>
			</div>
		</div>
		<div class="grid" data-grid>
			<?php
			foreach ( $isp_projects as $isp_p ) :
				$isp_cover = $isp_p['photos'][0] ?? null;
				?>
			<article class="card" id="p-<?php echo esc_attr( $isp_p['id'] ); ?>" data-slug="<?php echo esc_attr( $isp_p['id'] ); ?>" data-domains="<?php echo esc_attr( implode( ' ', $isp_p['services'] ) ); ?>">
				<?php echo isp_frame_open( 'card__media', $isp_cover ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?>
					<?php echo isp_photo_html( $isp_cover, $isp_p['title'], 'card', array( 'sizes' => '(max-width: 760px) 50vw, (max-width: 1100px) 33vw, 300px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php if ( '' !== $isp_p['badge'] ) : ?>
						<span class="card__badge"><?php echo esc_html( $isp_p['badge'] ); ?></span>
					<?php endif; ?>
				</div>
				<div class="card__body">
					<p class="card__meta"><span class="card__type"><?php echo esc_html( $isp_p['type'] ); ?></span><span class="card__year"><?php echo esc_html( $isp_p['year'] ); ?></span></p>
					<h3 class="card__title"><a href="#p-<?php echo esc_attr( $isp_p['id'] ); ?>"><?php echo isp_keep_words( $isp_p['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?></a></h3>
					<p class="card__line"><?php echo esc_html( $isp_p['tagline'] ); ?></p>
				</div>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

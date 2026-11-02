<?php
/**
 * Blog list, archives and search results (and the fallback for everything else).
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( is_search() ) {
	/* translators: %s: the search terms */
	$isp_title = sprintf( 'Search: %s', get_search_query() );
} elseif ( is_archive() ) {
	$isp_title = wp_strip_all_tags( get_the_archive_title() );
} elseif ( is_home() && get_option( 'page_for_posts' ) ) {
	$isp_title = get_the_title( (int) get_option( 'page_for_posts' ) );
} else {
	$isp_title = 'Notes and write-ups';
}
?>
<main id="main" class="page-main">
	<div class="wrap">
		<header class="page-head">
			<h1><?php echo esc_html( $isp_title ); ?></h1>
			<?php if ( is_archive() && get_the_archive_description() ) : ?>
				<div class="page-head__lead"><?php the_archive_description(); ?></div>
			<?php endif; ?>
		</header>
		<?php
		if ( is_search() ) {
			get_search_form();
		}
		if ( have_posts() ) :
			?>
		<ol class="post-list">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
			<li class="post-item">
				<?php if ( has_post_thumbnail() ) : ?>
					<a class="post-item__thumb" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail( 'medium_large' ); ?></a>
				<?php endif; ?>
				<p class="post-item__meta"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
				<h2 class="post-item__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="post-item__excerpt"><?php the_excerpt(); ?></div>
			</li>
			<?php endwhile; ?>
		</ol>
			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 1,
					'prev_text' => '←',
					'next_text' => '→',
				)
			);
		else :
			?>
		<p class="empty-note"><?php echo is_search() ? 'Nothing matched. Try other words.' : 'Nothing here yet.'; ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();

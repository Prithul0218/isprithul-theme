<?php
/**
 * A single blog post (or any other post type without its own template).
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

get_header();
while ( have_posts() ) :
	the_post();
	if ( 'builder' === get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) ) :
		?>
<main id="main" class="page-builder">
		<?php the_content(); ?>
</main>
		<?php
		continue;
	endif;
	?>
<main id="main" class="page-main">
	<div class="wrap">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="page-head">
				<p class="page-head__meta"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				<?php
				$isp_cats = get_the_category_list( ', ' );
				if ( $isp_cats && 'post' === get_post_type() ) {
					echo ' · ' . $isp_cats; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core-escaped links.
				}
				?>
				</p>
				<h1><?php the_title(); ?></h1>
			</header>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="page-thumb"><?php the_post_thumbnail( 'large' ); ?></figure>
			<?php endif; ?>
			<div class="prose">
				<?php
				the_content();
				wp_link_pages();
				?>
			</div>
			<?php the_tags( '<p class="post-tags">', '', '</p>' ); ?>
		</article>
		<nav class="post-nav" aria-label="More posts">
			<?php
			previous_post_link( '%link', '← %title' );
			next_post_link( '%link', '%title →' );
			?>
		</nav>
		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		?>
	</div>
</main>
	<?php
endwhile;
get_footer();

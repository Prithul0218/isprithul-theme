<?php
/**
 * Pages. Pages built with Elementor keep their own full-width layout.
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
	else :
		?>
<main id="main" class="page-main">
	<div class="wrap">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="page-head"><h1><?php the_title(); ?></h1></header>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="page-thumb"><?php the_post_thumbnail( 'large' ); ?></figure>
			<?php endif; ?>
			<div class="prose">
				<?php
				the_content();
				wp_link_pages();
				?>
			</div>
		</article>
		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		?>
	</div>
</main>
		<?php
	endif;
endwhile;
get_footer();

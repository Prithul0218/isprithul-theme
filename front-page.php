<?php
/**
 * The homepage. Every word and photo comes from the isprithul core plugin
 * (Site content and Projects in the admin).
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

if ( ! isp_home_view() ) {
	// No isprithul core plugin (or Elementor's editor is open): show the front page's own content.
	require get_template_directory() . ( is_page() ? '/page.php' : '/index.php' );
	return;
}

get_header();
?>
<main id="top">
	<?php
	get_template_part( 'parts/home/hero' );
	get_template_part( 'parts/home/projects' );
	get_template_part( 'parts/home/services' );
	get_template_part( 'parts/home/clients' );
	get_template_part( 'parts/home/experience' );
	get_template_part( 'parts/home/about' );
	get_template_part( 'parts/home/contact' );
	?>
</main>
<?php
get_footer();

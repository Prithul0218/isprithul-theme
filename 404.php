<?php
/**
 * Page not found.
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="page-main">
	<div class="wrap">
		<div class="notfound">
			<p class="notfound__code">ERC · 404 · unconnected net</p>
			<h1>This page isn’t wired to anything.</h1>
			<p>The link may be old, or the page has moved. The projects are on the homepage.</p>
			<p><a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to the homepage</a></p>
		</div>
	</div>
</main>
<?php
get_footer();

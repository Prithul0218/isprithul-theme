<?php
/**
 * Comments on posts.
 *
 * @package isprithul
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section class="comments" id="comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments__title">
			<?php
			$isp_n = (int) get_comments_number();
			echo esc_html( 1 === $isp_n ? 'One comment' : $isp_n . ' comments' );
			?>
		</h2>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 36,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>
	<?php
	if ( comments_open() ) {
		comment_form(
			array(
				'title_reply'        => 'Leave a comment',
				'title_reply_before' => '<h2 id="reply-title" class="comments__title">',
				'title_reply_after'  => '</h2>',
			)
		);
	}
	?>
</section>

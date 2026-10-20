<?php
/**
 * Comments partial.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

if ( post_password_required() ) {
	return;
}

?>
<section id="comments" class="comments">
	<?php if ( have_comments() ) : ?>
		<h4><?php
			printf(
				/* translators: 1: comment count, 2: post title */
				esc_html( _nx( 'One response to &ldquo;%2$s&rdquo;', '%1$s responses to &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'sage' ) ),
				esc_html( number_format_i18n( get_comments_number() ) ),
				'<span>' . esc_html( get_the_title() ) . '</span>'
			);
			?></h4>

		<ol class="comment-list">
			<?php wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
				)
			); ?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav>
				<ul class="pager">
					<?php if ( get_previous_comments_link() ) : ?>
						<li class="previous"><?php previous_comments_link( __( '&larr; Older comments', 'sage' ) ); ?></li>
					<?php endif; ?>
					<?php if ( get_next_comments_link() ) : ?>
						<li class="next"><?php next_comments_link( __( 'Newer comments &rarr;', 'sage' ) ); ?></li>
					<?php endif; ?>
				</ul>
			</nav>
		<?php endif; ?>
	<?php endif; // have_comments() ?>

	<?php if ( ! comments_open() && '0' !== get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<div class="alert alert-warning">
			<?php _e( 'Comments are closed.', 'sage' ); ?>
		</div>
	<?php endif; ?>

	<?php comment_form(); ?>
</section>
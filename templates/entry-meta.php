<?php
/**
 * Entry meta partial.
 *
 * @package BasicWP
 * @since 1.0.0
 */

namespace BasicWP;

?>
<p class="entry-meta">

<span class="byline author vcard"><?php _e( 'By', 'sage' ); ?> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author" class="fn"><?php the_author(); ?></a></span>

&nbsp;&nbsp; / &nbsp;&nbsp;
<?php comments_number(); ?>



<?php
$posttags = get_the_tags();

if ( $posttags ) {
	foreach ( $posttags as $tag ) {
		echo '&nbsp;&nbsp; / &nbsp;&nbsp;' . esc_html( $tag->name );
	}
}

$id         = get_the_ID();
$categories = wp_get_post_categories( $id );

foreach ( $categories as $category ) {

	$cat = get_category( $category );
	if ( $cat->term_id != 1 ) {
		printf(
			'&nbsp;&nbsp; / &nbsp;&nbsp;' . '<a href="%1$s">%2$s</a>',
			esc_url( get_category_link( $cat->term_id ) ),
			esc_html( $cat->name )
		);
	}
}
?>



&nbsp;&nbsp; / &nbsp;&nbsp;

<time class="updated" datetime="<?php echo esc_attr( get_post_time( 'c', true ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>

</p>
<?php
/**
 * Page Children block
 *
 * @package BasicWP
 */

namespace BasicWP;

// Retrieve the current page ID and its children
$parentId = get_queried_object_id();

// Children: direct descendants only
$children = get_posts(
    array(
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => array(
            'menu_order' => 'ASC',
            'title'      => 'ASC',
        ),
        'order'          => 'ASC',
        'post_parent'    => $parentId,
        'fields'         => 'all',
        'no_found_rows'  => true,
    )
);

// Set classes
$classes = 'page-children container relative px-4 py-8 mx-auto sm:px-6 sm:py-12 lg:px-8';

// Set wrapper attributes
$wrapper = blockWrapperAttributes( $classes, $is_preview );
?>

<?php if ( ! empty( $children ) ) : ?>
	<section id="page-children" <?php echo wp_kses_post( $wrapper ); ?>>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
			<?php
            foreach ( $children as $child ) {
                // Grandchildren of *this child* only
                $grandchildren = get_posts(
                    array(
                        'post_type'      => 'page',
                        'post_status'    => 'publish',
                        'posts_per_page' => -1,
                        'orderby'        => array(
                            'menu_order' => 'ASC',
                            'title'      => 'ASC',
                        ),
                        'order'          => 'ASC',
                        'post_parent'    => $child->ID, // fixed depth: 2 levels total
                        'fields'         => 'all',
                        'no_found_rows'  => true,
                    )
                );

                if ( count( $grandchildren ) > 0 ) {
                    $gcHeight = 'max-h-[300px]';
                    $gcPad    = '';
                    $lnkArrow = '';
                } else {
                    $gcHeight = '';
                    $gcPad    = 'pb-4';
                    $lnkArrow = 'icon-chevron-right-after';
                }

                $imgChild = ( get_the_post_thumbnail_url( $child->ID, 'medium_large' ) ) ? get_the_post_thumbnail_url( $child->ID, 'medium_large' ) : 'https://picsum.photos/seed/' . $child->ID . '/400/300?';
                ?>

                <article class="flex flex-col lg:flex-row overflow-hidden border-2 rounded-lg shadow-lg card border-primary-100 group <?php echo esc_attr( $gcHeight ); ?>">
                    <div class="max-w-[40%]">
                        <img src="<?php echo esc_attr( $imgChild ); ?>" alt="<?php echo esc_attr( get_the_title( $child ) ); ?>" class="object-cover w-full h-full p-0 m-0 transition-transform duration-300 ease-in-out transform group-hover:scale-105">
                    </div>

                    <div class="flex flex-col justify-start items-start flex-1 h-full p-6">
                        <div class="w-full">
                            <h3 class="mt-0 mb-3 font-semibold text-gray-800 transition text-25px w-full text-left">
                                <?php
                                if ( empty( $grandchildren ) ) {
                                    ?>
                                    <a href="<?php echo esc_url( get_permalink( $child->ID ) ); ?>" class="block w-full card-link reset text-secondary hover:opacity-80 <?php echo esc_attr( $lnkArrow ); ?>">
                                        <?php echo wp_kses_post( $child->post_title ); ?>
                                    </a>
                                <?php } else { ?>
                                    <?php echo wp_kses_post( $child->post_title ); ?>
                                <?php } ?>
                            </h3>
                        </div>

                        <div class="my-0 grow-1 text-18px text-gray-600 <?php echo esc_attr( $gcPad ); ?>">
                            <?php
                            if ( ! empty( $grandchildren ) ) {
                                ?>
                                <ul class="p-0 m-0 mt-2 ml-4 list-outside text-16px marker:text-gray-500">
                                    <?php
                                    foreach ( $grandchildren as $grandchild ) {
                                        ?>
                                        <li class="p-0 m-0 mb-2 leading-tight">
                                            <a class="hover:underline text-secondary" href="<?php echo esc_url( get_permalink( $grandchild->ID ) ); ?>">
                                                <?php echo wp_kses_post( get_the_title( $grandchild ) ); ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                                <?php
                            } else {
                                $firstParagraph = '';
                                if ( $child->post_content ) {
                                    preg_match( '/<p>(.*?)<\/p>/i', apply_filters( 'the_content', $child->post_content ), $matches );
                                    $firstParagraph = $matches[1] ?? '';
                                }
                                ?>
                                <div class="leading-snug line-clamp-4">
                                    <?php echo wp_kses_post( $firstParagraph ); ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </article>
            <?php } ?>
        </div>
    </section>
<?php endif; ?>

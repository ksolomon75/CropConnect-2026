<?php
/**
 * Block Name: Accordion
 *
 * This is the template that renders the Accordion block.
 *
 * @package BasicWP
 */

namespace BasicWP;

$open     = get_field( 'open' );
$group    = get_field( 'group_items' );
$accItems = get_field( 'accordion_items' );

if ( $accItems ) :
	$classes = 'accordion';
	$wrapper = blockWrapperAttributes( $classes, $is_preview );
	?>

	<section <?php echo wp_kses_post( $wrapper ); ?>>
	<?php foreach ( $accItems as $index => $item ) : ?>
		<?php
		$itemID = 'accordion-' . ( $index + 1 );
		$isOpen = ( $index === 0 && $open ) ? 'open' : '';
		?>
		<details <?php echo esc_attr( $group ) ? 'name="' . esc_attr( $group ) . '"' : ''; ?> <?php echo esc_attr( $isOpen ); ?> class="accBody">
		<summary class="accHeader">
			<h2><?php echo esc_html( $item['title'] ); ?></h2>

			<svg xmlns="http://www.w3.org/2000/svg" class="marker" fill="none" height="1rem" width="1rem" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-labelledby="<?php echo esc_attr( $itemID ); ?>-title <?php echo esc_attr( $itemID ); ?>-desc">
			<title id="<?php echo esc_attr( $itemID ); ?>-title">Open icon</title>
			<desc id="<?php echo esc_attr( $itemID ); ?>-desc">icon that represents the state of the summary</desc>
			<path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
			</svg>
		</summary>

		<div id="<?php echo esc_attr( $itemID ); ?>" class="accContent">
			<?php echo wp_kses_post( $item['content'] ); ?>
		</div>
		</details>
	<?php endforeach; ?>
	</section>
<?php endif; ?>

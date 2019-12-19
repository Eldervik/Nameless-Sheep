<?php
/**
 * nameless_sheep functions and definitions
 *
 * @package nameless_sheep
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$nameless_sheep_includes = array(
	'/enqueue.php',                         // Enqueue scripts and styles.
);

foreach ( $nameless_sheep_includes as $file ) {
	$filepath = locate_template( 'inc' . $file );
	if ( ! $filepath ) {
		trigger_error( sprintf( 'Error locating /inc%s for inclusion', $file ), E_USER_ERROR );
	}
	require_once $filepath;
}

function register_menus() {
    register_nav_menus(
        [
          'header-menu' => __( 'Header Menu' ),
        ]
    );
}
add_action( 'init', 'register_menus' );

add_theme_support('post-thumbnails');

add_image_size('post_image', 1000, 1000, true);

add_filter( 'woocommerce_currency_symbol', 'my_custom_currency_symbol' );
function my_custom_currency_symbol( $symbol ){
	
	$symbol = ':-';
	
    return $symbol;
}

register_sidebar(
	array(
		'name' => 'Main bar',
		'id' => 'main-bar',
		'class' => '',
		'before_title' => '<h4>',
		'after_title' => '</h4>',

	)
);

/**
 * Add quantity field on the shop page.
 */
function ace_shop_page_add_quantity_field() {

	/** @var WC_Product $product */
	$product = wc_get_product( get_the_ID() );

	if ( ! $product->is_sold_individually() && 'variable' != $product->get_type() && $product->is_purchasable() ) {
		woocommerce_quantity_input( array( 'min_value' => 1, 'max_value' => $product->backorders_allowed() ? '' : $product->get_stock_quantity() ) );
	}

}
add_action( 'woocommerce_after_shop_loop_item', 'ace_shop_page_add_quantity_field', 12 );


/**
 * Add required JavaScript.
 */
function ace_shop_page_quantity_add_to_cart_handler() {

	wc_enqueue_js( '
		$(".woocommerce .products").on("click", ".quantity input", function() {
			return false;
		});
		$(".woocommerce .products").on("change input", ".quantity .qty", function() {
			var add_to_cart_button = $(this).parents( ".product" ).find(".add_to_cart_button");

			// For AJAX add-to-cart actions
			add_to_cart_button.data("quantity", $(this).val());

			// For non-AJAX add-to-cart actions
			add_to_cart_button.attr("href", "?add-to-cart=" + add_to_cart_button.attr("data-product_id") + "&quantity=" + $(this).val());
		});

		// Trigger on Enter press
		$(".woocommerce .products").on("keypress", ".quantity .qty", function(e) {
			if ((e.which||e.keyCode) === 13) {
				$( this ).parents(".product").find(".add_to_cart_button").trigger("click");
			}
		});
	' );

}
add_action( 'init', 'ace_shop_page_quantity_add_to_cart_handler' );
$product = new WP_Query([
	'post_type' => 'product',
	'posts_per_page' => -1,
]);
function category_after_third_post( $product ) {
        global $wp_query;

        if ( $wp_query->post != $product )
            return;

        if ( 3 != $wp_query->current_post || 6 != $wp_query->current_post )
            return;

        $args = array(
        'category_name' => 'reklam',
        'posts_per_page' => 3
        );

        $catquery = new WP_Query($args);

        if ( $catquery->have_posts() ) :

            while ( $catquery->have_posts() ) : $catquery->the_post();

                get_template_part( 'content', get_post_format() );

            endwhile;

        endif; 
    }

add_action( 'the_post', 'category_after_third_post' );
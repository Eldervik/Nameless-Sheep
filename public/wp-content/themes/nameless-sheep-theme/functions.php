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

add_theme_support('post-thumbnails');

add_image_size('post_image', 1000, 1000, true);

add_filter( 'woocommerce_currency_symbol', 'my_custom_currency_symbol' );
function my_custom_currency_symbol( $symbol ){
	
	$symbol = ':-';
	
    return $symbol;
}

/**
 * Register our sidebars and widgetized areas.
 *
 */

function arphabet_widgets_init() {

	register_sidebar( array(
		'name'          => 'Home right sidebar',
		'id'            => 'home_right_1',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="rounded">',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'arphabet_widgets_init' );
?>
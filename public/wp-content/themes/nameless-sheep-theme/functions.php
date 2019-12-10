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
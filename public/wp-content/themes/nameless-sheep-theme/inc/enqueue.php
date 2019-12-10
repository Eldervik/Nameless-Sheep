<?php
/**
 * nameless_sheep enqueue scripts
 *
 * @package nameless_sheep
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'nameless_sheep_scripts' ) ) {
	/**
	 * Load theme's JavaScript and CSS sources.
	 */
	function nameless_sheep_scripts() {
		// Get the theme data.
		$the_theme     = wp_get_theme();
		$theme_version = $the_theme->get( 'Version' );

		$css_version = $theme_version . '.' . filemtime( get_template_directory() . '/css/theme.min.css' );
		wp_enqueue_style( 'nameless_sheep-styles', get_template_directory_uri() . '/css/theme.min.css', array(), $css_version );

		wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, true);
	}
}

add_action( 'wp_enqueue_scripts', 'nameless_sheep_scripts' );

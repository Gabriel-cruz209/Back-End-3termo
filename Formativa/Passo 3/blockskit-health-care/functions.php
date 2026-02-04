<?php

/**
 * Blockskit Health Care functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Blockskit Health Care
 */

define( 'BLOCKSKIT_HEALTH_CARE_URL', trailingslashit( get_stylesheet_directory_uri() ) );

if ( ! function_exists( 'blockskit_health_care_setup' ) ) {

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function blockskit_health_care_setup() {

		// Make theme available for translation.
		load_theme_textdomain( 'blockskit-health-care', get_stylesheet_directory() . '/languages' );
	}
}
add_action( 'after_setup_theme', 'blockskit_health_care_setup' );

/**
 * Enqueue scripts and styles
 */
function blockskit_health_care_scripts() {
	$version = wp_get_theme( 'blockskit-health-care' )->get( 'Version' );
	// enqueue parent style
	wp_enqueue_style('blockskit-health-care-parent-style', get_template_directory_uri() . '/style.css');
}
add_action( 'wp_enqueue_scripts', 'blockskit_health_care_scripts' );

/**
 * Label update filter.
 */
function blockskit_health_care_block_pattern_categories_filter( $block_pattern_categories ){
	$block_pattern_categories['theme']['label'] = __( 'Theme Patterns', 'blockskit-health-care' );
	return $block_pattern_categories;
}
add_filter( 'blockskit_base_block_pattern_categories', 'blockskit_health_care_block_pattern_categories_filter' );
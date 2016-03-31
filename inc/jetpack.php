<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package boxes
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function boxes_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'page',
	) );
}
add_action( 'after_setup_theme', 'boxes_jetpack_setup' );

/*
 * Enqueue scripts and styles
 */
//add_action( 'wp_enqueue_scripts', 'enqueue_my_scripts' );
function enqueue_my_scripts() {

    // You can enqueue other scripts and styles within this very same function

    /* Jetpack Carousel JS modified to not make ajax calls for comments */
    wp_deregister_script('jetpack-carousel');
    wp_register_script( 'jetpack-carousel', get_stylesheet_directory_uri() . '/js/jetpack-carousel.js', array( 'jquery.spin' ), '2.2', true );
    // wp_register_script( 'jetpack-carousel'); is NOT required as the plugin already does that

}

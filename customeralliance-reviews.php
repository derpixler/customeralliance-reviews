<?php
/*
Plugin Name:    Customer Alliance Reviews
Plugin URI:     https://github.com/derpixler/customeralliance-reviews
Description:    Display customer alliance reviews on your page
Version:        0.2.1
Author:         Thomas Horster, René Reimann
Author URI:     https://github.com/derpixler/customeralliance-reviews
Contributors:   derpixler
License:        GNU gpl 3.0
License URI:    http://choosealicense.com/licenses/gpl-3.0/
Domain Path:    /languages
Text Domain:    ca-reviews
*/

defined( 'ABSPATH' ) or die();


# Set the activation hook for a plugin.
add_action( 'plugins_loaded', 'wp_customer_alliance_reviews_activate' );

/**
 * Run on plugin activation, checks requirements.
 */
function wp_customer_alliance_reviews_activate() {

	$required_php_version = '5.4.0';

	$lang_dir = plugin_basename( __DIR__ ) . '/l10n/';
	load_plugin_textdomain( 'ca-reviews', FALSE, $lang_dir );

	$correct_php_version  = version_compare( phpversion(), $required_php_version, '>=' );

	if ( ! $correct_php_version ) {

		deactivate_plugins( basename( __FILE__ ) );

		wp_die(
			'<p>' .
			sprintf(
				esc_attr__( 'The Customer Alliance WordPress Reviews plugin can not be activated because it requires at least PHP version %1$s. ', 'ca-reviews' ),
				$required_php_version
			)
			. '</p> <a href="' . admin_url( 'plugins.php' ) . '">' . esc_attr__( 'back', 'ca-reviews' ) . '</a>'
		);

	}else{

		//require autoloader
		require_once( plugin_dir_path( __FILE__ ) . 'src/load.php' );

		// init autoloader
		new wp_customer_alliance\Reviews\Load();

	}

}
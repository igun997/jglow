<?php
/**
 * Created by PhpStorm.
 * User: toido
 * Date: 11/1/2018
 * Time: 11:09 AM
 */

//namespace WooVR;
use WooVR\Display_Comment;
use WooVR\Admin_Settings;
use WooVR\Add_Multi_Reviews;

defined( 'ABSPATH' ) || exit();

$plugin_url = plugins_url( '', __FILE__ );

define( 'WVR_PLUGIN_DIR_PATH', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woo-virtual-reviews" . DIRECTORY_SEPARATOR );
define( 'WVR_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'WVR_PLUGIN_URL', plugins_url() . "/woo-virtual-reviews" );

define( 'WVR_OPTION', "wvr_data" );

define( 'WVR_INCLUDES', WVR_PLUGIN_DIR_PATH . "includes" . DIRECTORY_SEPARATOR );
define( 'WVR_VIEWS', WVR_PLUGIN_DIR_PATH . "views" . DIRECTORY_SEPARATOR );

define( 'WVR_CSS_URL', $plugin_url . "/assets/css/" );
define( 'WVR_CSS_DIR', WVR_PLUGIN_DIR_PATH . "assets" . DIRECTORY_SEPARATOR . "css" . DIRECTORY_SEPARATOR );

define( 'WVR_JS_URL', $plugin_url . "/assets/js/" );
define( 'WVR_JS_DIR', WVR_PLUGIN_DIR_PATH . "assets" . DIRECTORY_SEPARATOR . "js" . DIRECTORY_SEPARATOR );

define( 'WVR_IMAGES_URL', $plugin_url . "/assets/img/" );

spl_autoload_register( function ( $class ) {
	$prefix   = 'WooVR';
	$base_dir = __DIR__;
	$len      = strlen( $prefix );

	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return;
	}

	$relative_class = strtolower( substr( $class, $len ) );
	$relative_class = strtolower( str_replace( '_', '-', $relative_class ) );
	$file           = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

	if ( file_exists( $file ) ) {
		require_once $file;
	} else {
		return;
	}
} );

/**
 * Initialize Plugin
 *
 * @since 1.0.0
 */
function wvr_init() {

	if ( ! is_admin() ) {
		Display_Comment::get_instance();
	} else {
		Admin_Settings::get_instance();

		Add_Multi_Reviews::get_instance();

		include_once WVR_INCLUDES . 'villatheme-support.php';
	}
}

add_action( 'plugins_loaded', 'wvr_init' );



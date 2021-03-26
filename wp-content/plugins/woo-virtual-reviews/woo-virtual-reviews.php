<?php
/**
 * Plugin Name:  Virtual Reviews for WooCommerce
 * Plugin URI: https://villatheme.com/extensions/woocommerce-virtual-reviews/
 * Description: WooCommerce Virtual Reviews creates virtual reviews, display canned reviews to increase your conversion rate.
 * Author: VillaTheme
 * Version: 1.0.5
 * Author URI: http://villatheme.com
 * Text Domain: woo-virtual-reviews
 * Domain Path: /languages
 * Copyright 2018 VillaTheme.com. All rights reserved.
 * Tested up to: 5.6
 * WC tested up to: 4.8
 */
defined( 'ABSPATH' ) || exit();

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
define( 'VI_WOO_VIRTUAL_REVIEWS_VERSION', '1.0.5' );
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	$init_file = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "woo-virtual-reviews" . DIRECTORY_SEPARATOR . "define.php";
	require_once $init_file;
	register_activation_hook( __FILE__, 'wvr_add_option' );
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wvr_add_action_links' );
	function wvr_add_action_links( $links ) {
		$my_link = '<a href="' . admin_url( '?page=virtual-reviews' ) . '">' . __( 'Settings', 'woo-email-customizer' ) . '</a>';
		array_unshift( $links, $my_link );

		return $links;
	}
} else {
	deactivate_plugins( plugin_basename( __FILE__ ) );
	if ( ! function_exists( 'wvr_notification' ) ) {
		function wvr_notification() {
			?>
            <div id="message" class="error">
                <p><?php _e(
						'Please install and activate WooCommerce to use WooCommerce Virtual Reviews.',
						'woo-virtual-reviews'
					); ?></p>
            </div>
			<?php
		}
	}
	add_action( 'admin_notices', 'wvr_notification' );

}

function wvr_add_option() {
	if ( ! get_option( WVR_OPTION ) ) {
		$data = array( 'show_purchased_label' => 'yes', 'auto_rating' => 'yes', 'show_canned' => 'yes' );
		update_option( WVR_OPTION, $data );
	}
}













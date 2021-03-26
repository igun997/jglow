<?php
/**
 * Fired during plugin activation
 *
 * @link       http://www.webtoffee.com
 * @since      1.0.0
 *
 * @package    Wt_Smart_Coupon
 * @subpackage Wt_Smart_Coupon/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wt_Smart_Coupon
 * @subpackage Wt_Smart_Coupon/includes
 * @author     markhf <info@webtoffee.com>
 */
if( !class_exists('Wt_Smart_Coupon_Activator') )  {
	class Wt_Smart_Coupon_Activator {

		/**
		 * Short Description. (use period)
		 *
		 * Long Description.
		 *
		 * @since    1.0.0
		 */
		public static function activate() {
			/**
			 *  Enable woocommmerce coupon settings
			 * @since 1.1.8
			 */
			if ( ! class_exists( 'WooCommerce' ) ) {
				deactivate_plugins( WT_SMARTCOUPON_BASE_NAME );
				wp_die(__("Oops! Woocommerce not activated..", 'wt-smart-coupons-for-woocommerce'), "", array('back_link' => 1));
				
			}
			if( defined('WT_SMARTCOUPON_INSTALLED_VERSION') && WT_SMARTCOUPON_INSTALLED_VERSION == 'PREMIUM' ) {
				
				deactivate_plugins( WT_SMARTCOUPON_BASE_NAME );
				wp_die(__("Oops! PREMIUM Version of this Plugin Installed. Please deactivate the PREMIUM Version before activating BASIC.", 'wt-smart-coupons-for-woocommerce'), "", array('back_link' => 1));
				
			}
			
			update_option( 'woocommerce_enable_coupons', 'yes' );
			do_action('after_wt_smart_coupon_for_wocommerce_is_activated');
		}
	
	}
}

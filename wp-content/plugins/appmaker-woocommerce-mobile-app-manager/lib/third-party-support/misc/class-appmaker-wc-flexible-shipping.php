<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

class APPMAKER_WC_flexible_shipping
{

    public function __construct()
    {
        add_filter('appmaker_wc_cart_items', array($this, 'add_shipping_rate'), 10, 1);
    }

    public function add_shipping_rate( $return ){
        
       // print_r(WC()->cart->get_cart());exit;
       if( class_exists('WPDesk_Flexible_Shipping') && function_exists('cart_weight') ) {
            $WPDesk_Flexible_Shipping                        = new WPDesk_Flexible_Shipping();
            $total_weight                                    = $WPDesk_Flexible_Shipping->cart_weight();            
            $return['price_details']                         = array();
            if( $total_weight ) {
                $return['price_details'][]                   = array('label' => 'Total Weight' ,'value' => wc_format_weight( $total_weight ));
            }
       }      
       $shipping_packages                               = WC()->shipping()->calculate_shipping( WC()->cart->get_shipping_packages() ); 
       $shipping_methods_title = __( 'Shipping', 'woocommerce' );       
       if ( is_array( $shipping_packages ) &&  WC()->cart->needs_shipping() ) {
            foreach ( $shipping_packages as $shipping_package ) {
                if ( isset( $shipping_package['rates'] ) ) {
                    foreach ($shipping_package['rates'] as $package ){                       
                        $return['price_details'][] = array('label' => $shipping_methods_title , 'value' => $package->label.':'.APPMAKER_WC_Helper::get_display_price($package->cost) );

                    }
                }
            }
           //$return['shipping_methods'] = $methods;
        }

        return $return;
    }
}
new APPMAKER_WC_flexible_shipping();
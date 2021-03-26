<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class APPMAKER_WC_Yith_Brands {

	public function __construct() {
        add_filter( 'appmaker_wc_product_widgets', array( $this, 'brand_product_list' ), 10, 2 );
        add_filter( 'appmaker_wc_product_tabs', array( $this, 'product_tabs' ), 2, 1 );
		
    }	

    public function product_tabs( $tabs ) {     
        
        if( ! isset( $tabs['yith_product_brand'] ) ) {                         
 
              $tabs['yith_product_brand'] = array(
                  'title'    => 'Brand',
                  'priority' => 2,
                  'callback' => '',
              );
        }        
      
      return $tabs; 
    }
    	
	/**
	 * @param $return
	 * @param WC_Product $product
	 * @param $data
	 *
	 * @return mixed
	 */
    public function brand_product_list( $return, $product_local ) {
     
        global $product_obj,$product;
        $product_obj = $product_local;
        $product     = $product_local;
        
		$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );
        $product_tabs = apply_filters( 'appmaker_wc_product_tabs', $product_tabs );
        $widgets_enabled_in_app = APPMAKER_WC::$api->get_settings( 'product_widgets_enabled', array() );            
        if ( ! empty( $widgets_enabled_in_app ) && is_array( $widgets_enabled_in_app ) ) {
            foreach($widgets_enabled_in_app as $id){
                if(array_key_exists($id,$product_tabs)){
                    $tabs[$id] = $product_tabs[$id];
                }
            }
        }else{
            $tabs = $product_tabs;
        }  
        
        	
        $terms = get_the_terms( $product->get_id(), 'yith_product_brand' );
        foreach ( $tabs as $key => $tab ) {
            if( $key == 'yith_product_brand') {
            
               if(! empty( $terms ) ){
                    foreach ( $terms as $term ) {			
                        $label = get_option( 'yith_wcbr_brands_label' );
                        $title = $label ? $label : 'Brand: ';
                        
                        $return[$key] = array(
                            'type'  => 'menu',
                            'title' => $title.' '.strip_tags( html_entity_decode($term->name)),
                    
                            'action' => array(
                                'type'   => 'LIST_PRODUCT',
                                'params' => array(
                                    'product_brand' => $term
                                ),
                            )
                        );
                    }
                } else {
                      unset($return[$key]);
                }
            }
        }
            
        
			
		return $return;
	}
}

new APPMAKER_WC_Yith_Brands();

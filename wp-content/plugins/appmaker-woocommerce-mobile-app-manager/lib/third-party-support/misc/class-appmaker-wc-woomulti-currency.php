<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class APPMAKER_WC_WOOMULTI_CURRENCY {
	public static function init() {
        $settings = WOOMULTI_CURRENCY_Data::get_ins();
        $default_currency =  $settings->get_default_currency();
        if( ( isset ( $_REQUEST['currency'] )  && empty ( $_REQUEST['currency'] ) ) || ! isset( $_REQUEST['currency'] ) ) {
            $_REQUEST['currency'] = $default_currency;
        }        
        $_POST['wmc-currency'] = $_GET['wmc-currency'] = $_REQUEST['currency'];            
        $settings->set_current_currency( $_REQUEST['currency'] );
        
    }   
		
}

APPMAKER_WC_WOOMULTI_CURRENCY::init();

<?php
/**
 * Created by IntelliJ IDEA.
 * User: shifa
 * Date: 4/9/19
 * Time: 12:24 PM
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

class APPMAKER_WC_ECPay_invoice{


    public function __construct()
    {

        remove_filter('woocommerce_checkout_fields', array( WC_ECPayinvoice::instance(), 'ecpay_invoice_info_fields'));
        add_filter('appmaker_wc_checkout_fields', array($this, 'ecpay_invoice_fields' ),10,2);
        add_filter( 'appmaker_wc_dependency_billing_customer_identifier', array( $this, 'billing_customer_id_dependency' ), 10, 2 );
        add_filter( 'appmaker_wc_dependency_billing_love_code', array( $this, 'billing_love_code_dependency' ), 10, 2 );
        add_filter( 'appmaker_wc_dependency_billing_carruer_num', array( $this, 'billing_carruer_num_dependency' ), 10, 2 );
        add_filter( 'appmaker_wc_dependency_billing_carruer_number', array( $this, 'billing_carruer_dependency' ), 10, 2 );
        add_action( 'appmaker_wc_before_checkout', array( $this, 'before_checkout' ), 10, 1 );
    }

    public static function before_checkout( $request ){
        if(isset($request['billing_carruer_number'])){
            $_POST['billing_carruer_num']=$request['billing_carruer_number'];
        }
    }

    public function billing_customer_id_dependency($dependency,$key){
        if ( 'billing_customer_identifier' === $key ) {
            $dependency = array( 'on' => 'billing_invoice_type' ,
                'matchValue'=>'c');
        }
        return $dependency;
    }
    public function billing_love_code_dependency($dependency,$key){
        if ( 'billing_love_code' === $key ) {
            $dependency = array( 'on' => 'billing_invoice_type' ,
                'matchValue'=>'d');
        }
        return $dependency;
    }
    public function billing_carruer_num_dependency($dependency,$key){
        if ( 'billing_carruer_num' === $key ) {
            $dependency = array( 'on' => 'billing_carruer_type' ,
                'matchValue'=>'2');
        }
        return $dependency;
    }
    public function billing_carruer_dependency($dependency,$key){
        if ( 'billing_carruer_number' === $key ) {
            $dependency = array( 'on' => 'billing_carruer_type' ,
                'matchValue'=>'3');
        }
        return $dependency;
    }

    public function  ecpay_invoice_fields($return,$section){
        $additional_fields = array();
          if($section=='billing') {
              $additional_fields['billing_invoice_type'] = array(
                  'type' => 'select',
                  'label' => '????????????',
                  'required' => false,
                  'dependent'=>false,
                  'options' => array(
                      'p' => '??????',
                      'c' => '??????',
                      'd' => '??????'
                  )
              );


              $additional_fields['billing_customer_identifier'] = array(
                  'type' => 'dependent-select',
                  'dependent'=>true,
                  'label' => '????????????',
                  'required' => false
              );

              $additional_fields['billing_love_code'] = array(
                  'type' => 'dependent-select',
                  'label' => '?????????',
                  'dependent'=>true,
                  'required' => false
              );

              // ????????????
              $additional_fields['billing_carruer_type'] = array(
                  'type' => 'select',
                  'label' => '????????????',
                  'required' => false,
                  'options' => array(
                      '0' => '?????????',
                      '1' => '????????????',
                      '3' => '????????????',
                      '2' => '???????????????',
                  )
              );


              $additional_fields['billing_carruer_num'] = array(
                  'type' => 'dependent-select',
                  'label' => '????????????',
                  'dependent'=>true,
                  'required' => false
              );
              $additional_fields['billing_carruer_number'] = array(
                  'type' => 'dependent-select',
                  'label' => '????????????',
                  'dependent'=>true,
                  'required' => false
              );
          }
        return array_merge( $additional_fields, $return );

    }

}
new APPMAKER_WC_ECPay_invoice();
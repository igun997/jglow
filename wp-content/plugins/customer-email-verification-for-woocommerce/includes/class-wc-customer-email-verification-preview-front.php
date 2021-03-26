<?php
/**
 * cev_pro_admin_preview 
 *
 * @class   cev_pro_admin_preview
 * @package WooCommerce/Classes
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * cev_pro_admin_preview class.
 */
class WC_customer_email_verification_preview {

	/**
	 * Get the class instance
	 *
	 * @since  1.0.0
	 * @return customer-email-verification-pro
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	*/
	private static $instance;
	
	/**
	 * Initialize the main plugin function
	 * 
	 * @since  1.0.0
	*/
	public function __construct() {		
	}
	
	/*
	 * init function
	 *
	 * @since  1.0
	*/
	public function init(){
		add_action( 'wp_enqueue_scripts', array( $this, 'cev_pro_front_styles' ));		
		add_action( 'template_redirect', array( $this, 'preview_cev_page') );	
	}
	
	/**
	 * Include front js and css
	*/
	public function cev_pro_front_styles(){				
		$action = (isset($_REQUEST["action"])?$_REQUEST["action"]:"");
		
		if($action == 'preview_cev_verification_lightbox'){
			wp_enqueue_style( 'cev_front_style' );								
		}		
	}
	/*
	* CEV_PRO Page preview
	*/
	public static function preview_cev_page(){
		$action = (isset($_REQUEST["action"])?$_REQUEST["action"]:"");
		
		if($action != 'preview_cev_verification_lightbox')return;		
		
		wp_head();				
		
		include 'views/front/preview_cev_popup_page.php';exit;
	}
}	
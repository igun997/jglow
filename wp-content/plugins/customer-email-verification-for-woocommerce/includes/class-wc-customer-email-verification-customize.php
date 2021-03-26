<?php
/**
 * CEV  admin 
 *
 * @class   cev_admin
 * @package WooCommerce/Classes
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * cev_admin class.
 */
class WC_customer_email_verification_customize {

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
		$this->init();
	}
	
	/*
	 * init function
	 *
	 * @since  1.0
	*/
	public function init(){
		
		//adding hooks
		
		//callback add_action for new tab html
		add_action( "customize_popup_widget_cev_free", array( $this, "customize_email_verification_free" ) );
		add_action( 'wp_ajax_verification_widget_settings_free', array( $this, 'verification_widget_settings_free') );
		//remove_action( 'woocommerce_created_customer_notification', array( 'WC_Emails', 'customer_new_account' ), 10, 3 );	
	}
	
	public function customize_email_verification_free() {
		?>
        <form method="post" id="cev_verification_widget_settings_form" action="" enctype="multipart/form-data">	
					<h3 class="cev-verification-widget"><?php _e( 'Verification widget', 'customer-email-verification-for-woocommerce' ); ?><p class="design-p">This widget will open in a LighBox overlay</p></h3>
     <?php woo_customer_email_verification()->admin->get_html( $this->get_cev_verification_widget_settings_data_free() );?>
							<div class="submit">								
								<button name="save" class="button-primary btn_cev_outline_free cev_verification_widget_settings_save" type="submit" value="Save changes"><?php _e( 'Save Changes', 'customer-email-verification-pro' ); ?></button>
                                 <button name="save" class="button-primary btn_cev_preview_outline_free cev_verification_widget_preview" type="button" value="Save changes"><?php _e( 'Preview', 'customer-email-verification-pro' ); ?></button>
								<div class="spinner"></div>
								<div class="success_msg" style="display:none;"></div>							
								<?php wp_nonce_field( 'cev_verification_widget_settings', 'cev_verification_widget_settings_nonce' );?>
								<input type="hidden" name="action" value="verification_widget_settings_free">
       							<div id="" class="cev-popup cev_page_preview_popup" style="display:none;">
									<div class="cev-popup-row">
										<div class="cev-popup-body">
											<iframe id="cev_preview_iframe" class="cev_preview_iframe" src="<?php echo get_home_url(); ?>?action=preview_cev_verification_lightbox" class="tracking-preview-link"></iframe>	
										</div>
									</div>
												<div class="cev-popup-close"></div>
								</div>
                   			 </div>		
           </form> 
    <?php
	}
	/*
	* get customize  tab array data
	* return array
	*/
	function get_cev_verification_widget_settings_data_free(){		
	       	
			 $form_data = array(		
			'cev_verification_popup_overlay_background_color' => array(
				'type'		=> 'color',
				'title'		=> __( 'Verification popup overlay background color', 'customer-email-verification-pro' ),				
				'class'		=> 'cev_color_field border-top-verification',
				'show' => true,	
			),					
		);
		return $form_data;
	}

	public function verification_widget_settings_free(){
		if ( ! empty( $_POST ) && check_admin_referer( 'cev_verification_widget_settings','cev_verification_widget_settings_nonce' ) ) {
			$data = $this->get_cev_verification_widget_settings_data_free();	
			foreach( $data as $key => $val ){				
				if(isset($_POST[ $key ])){						
					update_option( $key, $_POST[ $key ] );
				}
			}	
		}
	}		
 }
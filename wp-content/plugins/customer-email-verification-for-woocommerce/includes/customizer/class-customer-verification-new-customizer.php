<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class wc_cev_customizer {	
	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	 */
	private static $instance;
	
	/**
	 * Initialize the main plugin function
	*/
    public function __construct() {
			
    }
	
		/**
	 * Register the Customizer panels
	 */
	public function cev_add_customizer_panels( $wp_customize ) {
		/**
		* Add our Header & Navigation Panel
		*/
		$wp_customize->add_panel( 'cev_naviation_panel',
			array(
				'title' => __( 'Customer Email Verification for WooCommerce', 'customer-email-verification-for-woocommerce' ),
				'description' => esc_html__( '', 'customer-email-verification-for-woocommerce' )
			)
		);		
	}	
	
	/**
	 * Register the Customizer sections
	 */
	public function cev_add_customizer_sections( $wp_customize ) {	
		$wp_customize->add_section( 'cev_controls_section',
			array(
				'title' => __( 'Email for Verification', 'customer-email-verification-for-woocommerce' ),
				'description' => '',
				'panel' => 'cev_naviation_panel'
			)
		);	

		$wp_customize->add_section( 'cev_new_account_email_section',
			array(
				'title' => __( 'Verification Email', 'customer-email-verification-for-woocommerce' ),
				'description' => '',
				'panel' => 'cev_naviation_panel'
			)
		);	
		
			
	}
	
	
	/**
     * Remove unrelated components
     *
     * @access public
     * @param array $components
     * @param object $wp_customize
     * @return array
     */
    public function remove_unrelated_components($components, $wp_customize)	{
        // Iterate over components
        foreach ($components as $component_key => $component) {

            // Check if current component is own component
            if ( ! $this->is_own_component( $component ) ) {
                unset($components[$component_key]);
            }
        }
        // Return remaining components
        return $components;
    }
	
	 /**
     * Remove unrelated sections
     *
     * @access public
     * @param bool $active
     * @param object $section
     * @return bool
     */
    public function remove_unrelated_sections( $active, $section ) {
        // Check if current section is own section
        if ( ! $this->is_own_section( $section->id ) ) {
            return false;
        }

        // We can override $active completely since this runs only on own Customizer requests
        return true;
    }
	
	/**
	* Check if current section is own section
	*
	* @access public
	* @param string $key
	* @return bool
	*/
	public static function is_own_section( $key ) {		
		if ($key === 'cev_new_account_email_section' || $key === 'cev_controls_section') {
			return true;
		}

		// Section not found
		return false;
	}
	
	/*
	 * Unhook Divi front end.
	 */
	public function unhook_divi() {
		// Divi Theme issue.
		remove_action( 'wp_footer', 'et_builder_get_modules_js_data' );
		remove_action( 'et_customizer_footer_preview', 'et_load_social_icons' );
	}
	
	/*
	 * Unhook flatsome front end.
	 */
	public function unhook_flatsome() {
		// Unhook flatsome issue.
		wp_dequeue_style( 'flatsome-customizer-preview' );
		wp_dequeue_script( 'flatsome-customizer-frontend-js' );
	}
	
	/**
	* Check if current component is own component
	*
	* @access public
	* @param string $component
	* @return bool
	*/
	public static function is_own_component( $component ) {
		return false;
	}

	
	/**
	 * add css and js for customizer
	*/
	public function enqueue_customizer_scripts(){	
		if(isset( $_REQUEST['cev-customizer'] ) && '1' === $_REQUEST['cev-customizer']){
			wp_enqueue_style('cev-customizer-styles', woo_customer_email_verification()->plugin_dir_url() . 'assets/css/customizer-styles.css', array(), woo_customer_email_verification()->version  );
			wp_enqueue_script('cev-customizer-scripts', woo_customer_email_verification()->plugin_dir_url() . 'assets/js/customizer-scripts.js', array('jquery', 'customize-controls'), woo_customer_email_verification()->version, true);
	
			// Send variables to Javascript
			wp_localize_script('cev-customizer-scripts', 'cev_customizer', array(
				'ajax_url'              => admin_url('admin-ajax.php'),				
				'trigger_click'        => '#accordion-section-'.$_REQUEST['section'].' h3',
			));		
		}
	}
}
/**
 * Returns an instance of zorem_woocommerce_cev.
 *
 * @since 1.6.5
 * @version 1.6.5
 *
 * @return zorem_woocommerce_advanced_shipment_tracking
*/
function wc_cev_customizer() {
	static $instance;

	if ( ! isset( $instance ) ) {		
		$instance = new wc_cev_customizer();
	}

	return $instance;
}

/**
 * Register this class globally.
 *
 * Backward compatibility.
*/
wc_cev_customizer();
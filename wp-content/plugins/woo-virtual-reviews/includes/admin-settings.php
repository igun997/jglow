<?php
/**
 * Created by PhpStorm.
 * User: toido
 * Date: 11/1/2018
 * Time: 11:12 AM
 */

namespace WooVR;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin_Settings {

	protected static $instance = null;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'woo_virtual_reviews_asset' ) );
		add_action( 'admin_menu', array( $this, 'add_woo_virtual_reviews' ) );
		add_action( 'admin_post_save_option', array( $this, 'save_option' ) );
		add_action( 'wp_ajax_search_product', array( $this, 'search_product' ) );
	}

	/**
	 * Setup instance attributes
	 *
	 * @since     1.0.0
	 */

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function woo_virtual_reviews_asset() {

		if ( get_current_screen()->id == 'toplevel_page_virtual-reviews' ) {
			$this->delete_script();
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );

			$style_arr = array(
				'style',
				'w3-grid',
				'menu.min',
				'segment.min',
				'checkbox.min',
				'tab.min',
				'form.min',
				'select2.min'
			);

			foreach ( $style_arr as $style ) {
				wp_enqueue_style( "wvr-" . $style, WVR_PLUGIN_URL . "/assets/css/" . $style . ".css" );
			}

			$js_arr = array(
				'admin',
				'checkbox.min',
				'tab.min',
				'form.min',
				'select2'
			);

			foreach ( $js_arr as $js ) {
				wp_enqueue_script( "wvr-" . $js, WVR_PLUGIN_URL . "/assets/js/" . $js . ".js", array( 'jquery' ), true, true );
			}

			$localize = array( 'ajax_url' => admin_url( "admin-ajax.php" ) );
			wp_localize_script( "wvr-admin", "wvrObject", $localize );
		}
	}

	public function delete_script() {
		global $wp_scripts;
		$scripts = $wp_scripts->registered;
		foreach ( $scripts as $k => $script ) {
//			check($script);
			preg_match( '/^\/wp-/i', $script->src, $result );
			if ( count( array_filter( $result ) ) < 1 ) {
				if ( $script->handle != 'query-monitor' ) {
					wp_dequeue_script( $script->handle );
				} //delete script not belong to wp
			}
		}
	}

//	public function get_my_option() {
//		return $data = ( get_option( WVR_OPTION ) );
//	}

	public function add_woo_virtual_reviews() {
		add_menu_page(
			__( 'Virtual Reviews', 'woo-virtual-reviews' ),
			__( 'Virtual Reviews', 'woo-virtual-reviews' ),
			'manage_options',
			'virtual-reviews',
			array( $this, 'page_settings_content' ),
			'dashicons-star-filled',
			40
		);
	}

	public function page_settings_content() {
		require_once( WVR_PLUGIN_DIR_PATH . "views/wvr-settings.php" );

	}


	public function display_textarea_values( $arg ) {
		$list_opts = Data::get_data_option();
		if ( ! empty( $list_opts[ $arg ] && is_array( $list_opts[ $arg ] ) ) ) {
			foreach ( $list_opts[ $arg ] as $list ) {
				esc_html_e( stripslashes( $list ) . "\n" );
			}
		}
	}

	public function display_option_data( $arg ) {
		$list_opts = Data::get_data_option();
		if ( isset( $list_opts[ $arg ] ) && ! is_array( $list_opts[ $arg ] ) ) {
			return $list_opts[ $arg ];
		}
	}

	public function save_option() {
		if ( check_admin_referer( 'wvr_settings' ) && isset( $_POST['wvr_params'] ) ) {

			$data['names']        = isset( $_POST['wvr_params']['names'] ) ? $this->filter_data( sanitize_textarea_field( $_POST['wvr_params']['names'] ), 1000 ) : array();
			$data['cmt']          = isset( $_POST['wvr_params']['cmt'] ) ? $this->filter_data( sanitize_textarea_field( $_POST['wvr_params']['cmt'] ) ) : array();
			$data['cmt_frontend'] = isset( $_POST['wvr_params']['cmt_frontend'] ) ? $this->filter_data( sanitize_textarea_field( $_POST['wvr_params']['cmt_frontend'] ) ) : array();
			$data['custom_css']   = isset( $_POST['wvr_params']['custom_css'] ) ? $this->filter_data( sanitize_textarea_field( $_POST['wvr_params']['custom_css'] ) ) : array();

			$text_data = wc_clean( $_POST['wvr_params'] );
			$data      = wp_parse_args( $data, $text_data );

			update_option( WVR_OPTION, $data, 'yes' );
			wp_safe_redirect( $_POST['_wp_http_referer'] );
			exit;
		}
	}

	public function filter_data( $arg, $limit = 100 ) {
		$arg = ( array_values( array_unique( array_filter( array_map( 'trim', explode( '<br />', trim( nl2br( $arg ) ) ) ) ) ) ) );
		$arg = array_slice( $arg, 0, $limit );
		sort( $arg );

		return $arg;
	}


	public function search_product() {
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'search_product' ) {
			$keyword = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );
			if ( empty( $keyword ) ) {
				die();
			}
			$arg            = array(
				'post_status'    => 'publish',
				'post_type'      => 'product',
				'posts_per_page' => 50,
				's'              => $keyword
			);
			$json           = array();
			$found_products = get_posts( $arg );
			foreach ( $found_products as $product ) {
				$json[] = [ 'id' => $product->ID, 'text' => $product->post_title ];
			}
			wp_send_json( $json );
		}
		wp_die();
	}
}




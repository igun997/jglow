<?php
/**
 * Created by PhpStorm.
 * User: toido
 * Date: 11/1/2018
 * Time: 11:12 AM
 */
defined( 'ABSPATH' ) or die;

class Woo_virtual_reviews {
	function __construct() {
		if ( ( isset( $_GET['page'] ) && $_GET['page'] == 'virtual-reviews' ) || ( isset( $_GET['action'] ) && $_GET['action'] == 'edit' || ! is_admin() ) ) {
			add_action( 'init', array( $this, 'woo_virtual_reviews_asset' ) );
		}
		add_action( 'admin_menu', array( $this, 'add_woo_virtual_reviews' ) );
	}

	public $wvr_select_star = [
		'5-5' => '5 star',
		'4-4' => '4 star',
		'3-3' => '3 star',
		'2-2' => '2 star',
		'1-1' => '1 star',
		'1-5' => 'Random 1-5 star',
		'2-5' => 'Random 2-5 star',
		'3-5' => 'Random 3-5 star',
		'4-5' => 'Random 4-5 star',
	];

	public function woo_virtual_reviews_asset() {
		$style_arr = array(
			'style',
			'w3-grid',
			'menu.min',
			'segment.min',
			'checkbox.min',
			'button.min',
			'tab.min',
			'form.min',
			'select2.min'
		);
		foreach ( $style_arr as $style ) {
			wp_enqueue_style( "wvr-" . $style, WVR_PLUGIN_URL . "/assets/css/" . $style . ".css" );
		}
		$js_arr = array(
			'front-script',
			'checkbox.min',
			'tab.min',
			'form.min',
			'add-single-comment',
			'select2'
		);
		foreach ( $js_arr as $js ) {
			wp_enqueue_script( "wvr-" . $js, WVR_PLUGIN_URL . "/assets/js/" . $js . ".js", array( 'jquery' ) );
		}
		wp_localize_script( "wvr-front-script", "ajax_url", admin_url( "admin-ajax.php" ) );
		wp_localize_script( "wvr-add-single-comment", "ajax_url", admin_url( "admin-ajax.php" ) );
	}

	public function get_my_option() {
		return $data = ( get_option( WVR_OPTION ) );
	}

	public function add_woo_virtual_reviews() {
		add_menu_page(
			__( 'Virtual Reviews', 'woo-virtual-reviews' ),
			__( 'Virtual Reviews', 'woo-virtual-reviews' ),
			'manage_options',
			'virtual-reviews',
			array( $this, 'page_settings_content' ),
			'dashicons-star-filled'
		);
	}

	public function page_settings_content() {
		require_once( WVR_PLUGIN_DIR_PATH . "views/wvr-settings.php" );
		if ( isset( $_POST['wvr-add-sample'] ) && $_POST['wvr-add-sample'] == 'save' ) {
			$this->add_sample();
		} elseif ( isset( $_POST['wvr-add-custom-review'] ) && $_POST['wvr-add-custom-review'] == 'add_review' ) {
			$content     = isset( $_POST['wvr_cmt_content'] ) ? sanitize_textarea_field( $_POST['wvr_cmt_content'] ) : '';
			$author_name = isset( $_POST['wvr_author_name'] ) ? sanitize_text_field( $_POST['wvr_author_name'] )  : '';
//			$post_ids    = isset( $_POST['wvr-select2-product'] ) ? array_map( "sanitize_text_field", $_POST['wvr-select2-product'] ) : '';
			$post_ids    = isset( $_POST['wvr-select2-product'] ) ?sanitize_text_field($_POST['wvr-select2-product'] ) : '';
			$rating      = isset( $_POST['wvr_rating'] ) ? sanitize_text_field( $_POST['wvr_rating'] ) : '';
			require_once( WVR_PLUGIN_DIR_PATH . "includes/class-add-custom-review.php" );
			$wvr_add = new Wvr_add_custom_review();
			$wvr_add->insert_comment( $content, $author_name, $post_ids, $rating );
		}
	}

	public function add_sample() {

		$data = array(
			'name'            => array(),
			'cmt'             => array(),
			'cmt_frontend'    => array(),
			'rating'          => array(),
			'cb_show_canned'  => array(),
			'cb_select_slide' => array()
		);

		$list_name         = isset( $_POST['wvr-list-name'] ) ? sanitize_textarea_field( $_POST['wvr-list-name'] ) : '';
		$list_cmt          = isset( $_POST['wvr-list-cmt'] ) ? sanitize_textarea_field( $_POST['wvr-list-cmt'] ) : '';
		$list_cmt_frontend = isset( $_POST['wvr-list-cmt-frontend'] ) ? sanitize_textarea_field( $_POST['wvr-list-cmt-frontend'] ) : '';
		$select_rating     = isset( $_POST['wvr-select-rating'] ) ? sanitize_text_field( $_POST['wvr-select-rating'] ) : '5';
		$cb_show_canned    = isset( $_POST['wvr-cb-show-canned'] ) ? sanitize_key( $_POST['wvr-cb-show-canned'] ) : '';
		$cb_select_slide   = isset( $_POST['wvr-cb-show-canned-slide'] ) ? sanitize_key( $_POST['wvr-cb-show-canned-slide'] ) : '';

		$list_name         = $this->filter_data( $list_name );
		$list_cmt          = $this->filter_data( $list_cmt );
		$list_cmt_frontend = $this->filter_data( $list_cmt_frontend );

		sort( $list_name );
		sort( $list_cmt );
		sort( $list_cmt_frontend );

		$data['name']            = $list_name;
		$data['cmt']             = array_slice( $list_cmt, 0, 300 );
		$data['cmt_frontend']    = array_slice( $list_cmt_frontend, 0, 50 );
		$data['rating']          = $select_rating;
		$data['cb_show_canned']  = $cb_show_canned;
		$data['cb_select_slide'] = $cb_select_slide;

		update_option( WVR_OPTION, $data, 'no' );
		header( 'Location: ' . $_SERVER['HTTP_REFERER'] );
	}

	public function show_data_from_option( $arg ) {
		$list_opts = $this->get_my_option();
		if ( ! empty( $list_opts[ $arg ] ) ) {
			foreach ( $list_opts[ $arg ] as $list ) {
				echo $list . "\n";
			}
		}
	}

	public function filter_data( $arg ) {
		return array_unique( array_filter( array_map( 'trim', explode( '<br />', trim( nl2br( $arg ) ) ) ) ) );
	}

	public function show_data_from_option_other( $arg ) {
		$list_opts = $this->get_my_option();

		return $rating = $list_opts[ $arg ];
	}

}

if ( class_exists( 'Woo_virtual_reviews' ) ) {
	$wvr = new Woo_virtual_reviews();
}




























<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 20/11/2018
 * Time: 2:34 CH
 */
defined( 'ABSPATH' ) || exit();

class Wvr_Ajax extends Woo_virtual_reviews {

	public function __construct() {
		add_action( 'wp_ajax_wvr_action', array( $this, 'wvr_my_action' ) );
	}

	public function wvr_my_action() {
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'wvr_action' ) {
			if ( isset( $_REQUEST['param'] ) && $_REQUEST['param'] == 'search_product' ) {
				$this->search_product();
			}
		}
		wp_die();
	}

	public function search_product() {
		ob_start();
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
		wp_die();
	}

}

$wvr_ajax = new Wvr_Ajax;
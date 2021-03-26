<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/11/2018
 * Time: 11:00 SA
 */
class Wvr_display_content_front_end extends Woo_virtual_reviews {

	function __construct() {
		add_filter( 'woocommerce_product_review_comment_form_args', array( $this, 'sv_add_wc_review_notes' ) );
		add_action( 'woocommerce_review_after_comment_text', array( $this, 'show_comments' ) );
		add_filter( 'get_avatar_comment_types', array( $this, 'wvr_add_avatar_for_review_comment_type' ) );
	}

	public function sv_add_wc_review_notes( $review_form ) {
		// Shown to all reviewers below "Your Review" field
		$datas           = $this->get_my_option();
		$sample_cmts     = $datas['cmt_frontend'];
		$cb_show_canned  = $datas['cb_show_canned'];
		$cb_select_slide = $datas['cb_select_slide'];
		$text_select     = $text_slide = '';
//		$emoji       = $this->emoji();

		if ( isset( $cb_show_canned ) && $cb_show_canned == 'yes' ) {
			if ( isset( $cb_select_slide ) && $cb_select_slide == 'slide' ) {
				foreach ( $sample_cmts as $sample_cmt ) {
					$text_slide .= "<span class='wvr-select-sample-cmt' value='".esc_html($sample_cmt)."'>".esc_html($sample_cmt)."</span>";
				}
				$review_form['comment_notes_after'] = '<div class="wvr-customer-sample-cmt">';
				$review_form['comment_notes_after'] .= '<div style="display: flex"><div class="wvr-customer-pick">' . $text_slide . '</div>';
				$review_form['comment_notes_after'] .= '<span class="wvr-clear-comment"></span></div></div>';
			} elseif ( isset( $cb_select_slide ) && $cb_select_slide == 'select' ) {
				foreach ( $sample_cmts as $sample_cmt ) {
					$text_select .= "<option>".esc_html($sample_cmt)."</option>";
				}
				$review_form['comment_notes_after'] = '<div class="wvr-customer-sample-cmt">';
				$review_form['comment_notes_after'] .= '<div style="display: flex"><select class="wvr-customer-select"><option value="">' . __( "Sample comments", "woo-virtual-reviews" ) . '</option>' . $text_select . '</select>';
				$review_form['comment_notes_after'] .= '<span class="wvr-clear-comment"></span></div></div>';
			}
		}

		return $review_form;
	}

	public function show_comments() {
		$results           = '';
		$comment_id_arr    = get_comment_ID();
		$comment           = get_comment( $comment_id_arr, OBJECT );
		$comment_author_id = $comment->user_id;

		if ( $comment_author_id != 0 ) {
			$post_id            = $this->wvr_get_post_id_by_meta_key_and_value( '_customer_user', $comment_author_id );
			$product_var_bought = $this->wvr_get_variation_product( $post_id );
			foreach ( $product_var_bought as $variation_id => $quantity ) {
				if ( $variation_id != 0 ) {
					$attributes[ $variation_id ] = '';
					$variations                  = wc_get_product( $variation_id );
					foreach ( $variations->get_variation_attributes() as $variation_key => $variation_value ) {
						$attributes[ $variation_id ] .= $variation_value . " ";
					}
					$results .= " " . $attributes[ $variation_id ] . " x " . $quantity . " | ";
				} else {
					$results = $quantity . " products";
				}
			}
			echo "<div class='wvr-product-bought'><span class='wvr-ordered'></span>";
			$results = rtrim( $results, "| " );
			echo $results . "</div>";
		} else {
			$comment_id   = $comment->comment_ID;
			$comment_meta = get_comment_meta( $comment_id, 'variation', true );
			if ( ! empty( $comment_meta ) ) {
				if ( is_numeric( $comment_meta ) ) {
					if ( $comment_meta == 1 ) {
						echo( "<div class='wvr-product-bought'><span class='wvr-ordered'></span><span>  " . $comment_meta . " product</span></div>" );
					} else {
						echo( "<span class='wvr-product-bought'><span class='wvr-ordered'></span><span>  " . $comment_meta . " products</span></span>" );
					}
				} else {
					echo( "<span  class='wvr-product-bought'><span class='wvr-ordered'></span> " . $comment_meta . "</span>" );
				}
			}
		}
	}

	public function wvr_get_post_id_by_meta_key_and_value( $key, $value ) {
		global $wpdb;
		$post_id = array();
		$meta    = $wpdb->get_results( "SELECT * FROM `" . $wpdb->postmeta . "` WHERE meta_key='" . $wpdb->escape( $key ) . "' AND meta_value='" . $wpdb->escape( $value ) . "'" );
		foreach ( $meta as $mt ) {
			$post_id[] = $mt->post_id;
		}

		return $post_id;
	}

	public function wvr_get_variation_product( $post_ids = array() ) {
		global $product;
		$current_product_id = $product->get_id();
		$product_bought     = array();
		foreach ( $post_ids as $post_id ) {
			$order = wc_get_order( $post_id );
			$items = $order->get_items();
			foreach ( $items as $item ) {
				$product_id = $item->get_product_id();
				if ( $current_product_id == $product_id ) {
					if ( isset( $product_bought[ $item->get_variation_id() ] ) ) {
						$product_bought[ $item->get_variation_id() ] += $item->get_quantity();
					} else {
						$product_bought[ $item->get_variation_id() ] = $item->get_quantity();
					}
				}
			}
		}

		return $product_bought;
	}

	public function wvr_add_avatar_for_review_comment_type( $comment_types ) {
		return array_merge( $comment_types, array( 'self_review' ) );
	}
//
//	public function emoji() {
//		require_once( WVR_PLUGIN_DIR_PATH . "../views/emoji.php" );
//
//		return emoji();
//	}
}

new Wvr_display_content_front_end;
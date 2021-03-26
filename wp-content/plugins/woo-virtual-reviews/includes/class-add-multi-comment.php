<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 08/11/2018
 * Time: 10:15 SA
 */
defined( 'ABSPATH' ) or die;

class Wvr_add_multi_review extends Woo_virtual_reviews {

	function __construct() {
		if ( ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'product' ) ) {
			add_action( 'init', array( $this, 'woo_virtual_reviews_asset' ) );
		}
		add_filter( 'manage_edit-product_columns', array( $this, 'change_columns_filter' ), 20 );
		add_action( 'admin_head', array( $this, 'my_custom_fonts' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'show_virtual_review_count' ) );
		add_action( 'manage_posts_extra_tablenav', array( $this, 'quantity_comment_once' ) );
		add_filter( 'manage_edit-product_sortable_columns', array( $this, 'my_sortable_cake_column' ) );
		add_action( 'pre_get_posts', array( $this, 'my_slice_orderby' ) );
		add_filter( 'admin_comment_types_dropdown', array( $this, 'wvr_admin_comment_types_dropdown' ) );
		add_filter( 'bulk_actions-edit-product', array( $this, 'register_delete_virtual_reviews' ) );
		add_filter( 'handle_bulk_actions-edit-product', array( $this, 'delete_virtual_reviews' ), 10, 3 );
	}

	public function wvr_admin_comment_types_dropdown( $types ) {
		$types['self_review'] = __( 'Self Review', 'woo-virtual-reviews' );

		return $types;
	}

	public function woo_virtual_reviews_asset() {
		wp_enqueue_script( 'wvr-front-script.js', WVR_PLUGIN_URL . "/assets/js/wvr-add-multi-reviews.js", '', true );
	}

	public function register_delete_virtual_reviews( $bulk_actions ) {
		$bulk_actions['delete_virtual_reviews'] = __( 'Delete Virtual Reviews', 'woo-virtual-reviews' );

		return $bulk_actions;
	}

	public function delete_virtual_reviews( $redirect_to, $action_name, $post_ids ) {
		if ( 'delete_virtual_reviews' != $action_name ) {
			return $redirect_to;
		}

		foreach ( $post_ids as $post_id ) {
			$arg  = array(
				'post_id' => $post_id,
				'type'    => 'self_review'
			);
			$cmts = get_comments( $arg );
			foreach ( $cmts as $cmt ) {
				wp_delete_comment( $cmt->comment_ID );
			}
		}

		return $redirect_to;
	}

	public function quantity_comment_once( $which ) {
		global $post_type;
		if ( $post_type == 'product' && $which == 'top' ) {
			echo "<div class='alignleft wvr-actions' style='margin-top: 2px;'>";
			echo "<select name='wvr-select-qty-cmt' class='wvr-select-qty-cmt'>";
			echo "<option value='1'>" . __( "Select quantity review", "woo-virtual-reviews" ) . "</option>";
			for ( $i = 1; $i <= 10; $i ++ ) {
				echo "<option>" . $i . "</option>";
			}
			echo "</select>";
			echo "<button type='submit' class='vi-ui button submit-add-reviews' style='height: 32px;' name='submit' value='add_reviews'>" . __( "Add reviews", "woo-virtual-reviews" ) . "</button>";
			echo "</div>";
		}

		if ( isset( $_REQUEST['submit'] ) && $_REQUEST['submit'] == 'add_reviews' ) {
			unset( $_REQUEST['submit'] );
			$this->add_multiple_reviews();
			wp_redirect( $_SERVER['PHP_SELF'] . "?post_type=product" );
		}
	}

	public function add_multiple_reviews() {
		if ( isset( $_REQUEST['post'] ) ) {
			$post_ids        = $_REQUEST['post'];
			$list_opts       = $this->get_my_option();
			$name            = isset( $list_opts['name'] ) ? $list_opts['name'] : '';
			$comment_content = isset( $list_opts['cmt'] ) ? $list_opts['cmt'] : '';
			$rating          = isset( $list_opts['rating'] ) ? $list_opts['rating'] : '';
			$rating          = explode( '-', $rating );
			$qty_cmt         = isset( $_REQUEST['wvr-select-qty-cmt'] ) && is_numeric( $_REQUEST['wvr-select-qty-cmt'] ) ? $_REQUEST['wvr-select-qty-cmt'] : 1;

			if ( ! empty( $name ) && ! empty( $comment_content ) ) {
				foreach ( $post_ids as $post_id ) {
					for ( $i = 0; $i < $qty_cmt; $i ++ ) {
						$time            = $this->random_time();
						$random_rating   = rand( $rating[0], $rating[1] );
						$random_key_cmt  = rand( 0, count( $list_opts['cmt'] ) - 1 );
						$random_key_name = rand( 0, count( $list_opts['name'] ) - 1 );
						$comment_content = ( $list_opts['cmt'][ $random_key_cmt ] );
						$name            = ( $list_opts['name'][ $random_key_name ] );
						$data            = array(
							'comment_post_ID'      => $post_id,
							'comment_author'       => $name,
							'comment_author_email' => 'comment by admin',
							'comment_author_url'   => '',
							'comment_content'      => $comment_content,
							'comment_type'         => 'self_review',
							'comment_parent'       => 0,
							'user_id'              => 0,
							'comment_author_IP'    => '127.0.0.1',
							'comment_agent'        => 'admin',
							'comment_date'         => $time,
							'comment_approved'     => 1,
						);

						$comment_id = wp_insert_comment( $data );
						add_comment_meta( $comment_id, 'verified', 1 );
						add_comment_meta( $comment_id, 'rating', $random_rating );
						$product = wc_get_product( $post_id );

						if ( $product->is_type( 'variable' ) ) {
							$available_variations = $product->get_available_variations();
							$count_var            = count( $available_variations );
							$random               = rand( 0, $count_var - 1 );
							$variation            = ( implode( " ", $available_variations[ $random ]['attributes'] ) . " x " . 1 );
							update_comment_meta( $comment_id, 'variation', $variation );
						} else {
							update_comment_meta( $comment_id, 'variation', 1 );
						}

						$review_count = get_post_meta( $post_id, '_wc_review_count', true );
						$rating_count = get_post_meta( $post_id, '_wc_rating_count', true );

						if ( $review_count != array_sum( $rating_count ) ) {
							if ( ! isset( $rating_count[ $random_rating ] ) ) {
								$rating_count[ $random_rating ] = 1;
							} else {
								$rating_count[ $random_rating ] += 1;
							}
							update_post_meta( $post_id, '_wc_rating_count', $rating_count );
							$sum = 0;
							foreach ( $rating_count as $key => $value ) {
								$sum += $key * $value;
							}
							$ave_rating = round( $sum / $review_count, 1 );
							update_post_meta( $post_id, '_wc_average_rating', $ave_rating );
						}
					}
				}
			}
		}
	}

	public function change_columns_filter( $columns ) {
		$new_columns                   = array();
		$new_columns['virtual_review'] = __( 'Self-review', 'woo-virtual-reviews' );
		$new_columns['wvr_rating']     = __( 'Rating', 'woo-virtual-reviews' );

		return $columns = array_merge( $columns, $new_columns );
	}

	public function show_virtual_review_count( $column_name ) {
		if ( $column_name == 'virtual_review' ) {
			$count = count( get_comments( array(
				'post_id' => get_the_ID(),
				'type'    => 'self_review'
			) ) );
			echo $count;
		} elseif ( $column_name == 'wvr_rating' ) {
			echo $rating = get_post_meta( get_the_ID(), '_wc_average_rating', true );
		}
	}

	public function my_sortable_cake_column( $columns ) {
		$columns['wvr_rating']     = 'wvr_rating';
		$columns['virtual_review'] = 'virtual_review';

		return $columns;
	}

	public function my_slice_orderby( $query ) {
		if ( ! is_admin() ) {
			return;
		}
		$orderby = $query->get( 'orderby' );

		if ( 'wvr_rating' == $orderby ) {
			$query->set( 'meta_key', '_wc_average_rating' );
			$query->set( 'orderby', 'meta_value_num' );
		} elseif ( 'virtual_review' == $orderby ) {
			$query->set( 'orderby', 'meta_value_num' );
		}
	}

	public function my_custom_fonts() {
		echo '<style> .column-virtual_review, .column-wvr_rating {width: 70px !important;text-align: center !important;} </style>';
	}

	public function random_time() {
		$time[0] = $cur_time = current_time( 'mysql' );
		$time[1] = date( 'Y-m-d H:i:s', strtotime( '-1 day', strtotime( $cur_time ) ) );
		$time[2] = date( 'Y-m-d H:i:s', strtotime( '-2 days', strtotime( $cur_time ) ) );
		$rand    = array_rand( $time, 1 );

		return $time[ $rand ];
	}
}

$wvr_add_multi_review = new Wvr_add_multi_review();










































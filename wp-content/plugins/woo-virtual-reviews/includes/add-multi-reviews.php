<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 08/11/2018
 * Time: 10:15 SA
 */

namespace WooVR;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Add_Multi_Reviews {

	protected static $instance = null;
	protected $current_time;

	function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'woo_virtual_reviews_asset' ) );
		add_filter( 'manage_edit-product_columns', array( $this, 'change_columns_filter' ), 20 );
		add_action( 'admin_head', array( $this, 'my_custom_fonts' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'show_virtual_review_count' ) );
		add_action( 'manage_posts_extra_tablenav', array( $this, 'quantity_comment_once' ) );
		add_filter( 'manage_edit-product_sortable_columns', array( $this, 'my_sortable_cake_column' ) );
		add_action( 'pre_get_posts', array( $this, 'my_slice_orderby' ) );
		add_filter( 'admin_comment_types_dropdown', array( $this, 'wvr_admin_comment_types_dropdown' ) );
		add_filter( 'bulk_actions-edit-product', array( $this, 'register_delete_virtual_reviews' ) );
		add_filter( 'handle_bulk_actions-edit-product', array( $this, 'delete_virtual_reviews' ), 10, 3 );
		add_action( 'admin_action_add_multi_reviews', array( $this, 'add_reviews_by_submit' ) );
		add_action( 'wp_ajax_add_reviews_all', array( $this, 'add_reviews_all' ) );
		add_action( 'wp_ajax_count_product', array( $this, 'count_product' ) );
		add_action( 'wp_ajax_delete_cmt', array( $this, 'delete_cmt' ) );
		add_action( 'admin_post_add_review', array( $this, 'add_review' ) );

		add_action( 'wp_update_comment_count', array( $this, 'clear_transients' ), 999 );
		add_action( 'comment_post', array( $this, 'recount_comment' ), 999 );
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

	public function wvr_admin_comment_types_dropdown( $types ) {
		$types['self_review'] = __( 'Self Review', 'woo-virtual-reviews' );

		return $types;
	}

	public function woo_virtual_reviews_asset() {
		wp_enqueue_script( 'wvr-multi-reviews.js', WVR_PLUGIN_URL . "/assets/js/wvr-add-multi-reviews.js", '', true );
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
			if ( ! empty( $cmts ) ) {
				foreach ( $cmts as $cmt ) {
					wp_delete_comment( $cmt->comment_ID );
				}
			}
		}

		return $redirect_to;
	}

	public function quantity_comment_once( $position ) {
		global $post_type;
		if ( $post_type == 'product' && $position == 'top' ) {
//			echo "<form id='add_multi_reviews' method='post'>";
			echo "<div class='alignleft wvr-actions' style='margin-top: 2px;'>";
			echo "<select name='wvr-select-qty-cmt' class='wvr-select-qty-cmt'>";
			echo "<option value='1'>" . __( "Select quantity review", "woo-virtual-reviews" ) . "</option>";
			for ( $i = 1; $i <= 10; $i ++ ) {
				echo "<option>" . $i . "</option>";
			}
			echo "</select>";
			echo "<button type='submit' class='vi-ui button submit-add-reviews' style='height: 32px;' name='action' id='add_multi_reviews' value='add_multi_reviews'>" . __( "Add reviews", "woo-virtual-reviews" ) . "</button>";
			echo "</div>";
		}
	}

	function add_reviews_by_submit() {
		if ( isset( $_REQUEST['post'] ) ) {
			$post_ids = wc_clean( $_REQUEST['post'] );
			$qty_cmt  = isset( $_REQUEST['wvr-select-qty-cmt'] ) && is_numeric( $_REQUEST['wvr-select-qty-cmt'] ) ? sanitize_text_field( $_REQUEST['wvr-select-qty-cmt'] ) : 0;
			$this->add_multi_reviews( $post_ids, $qty_cmt );
		}
	}

	public function add_multi_reviews( $post_ids, $qty_cmt ) {
		$post_ids           = ! is_array( $post_ids ) ? array( $post_ids ) : $post_ids;
		$list_opts          = Data::get_data_option();
		$name               = isset( $list_opts['names'] ) ? $list_opts['names'] : '';
		$comment_content    = isset( $list_opts['cmt'] ) ? $list_opts['cmt'] : '';
		$rating             = isset( $list_opts['rating'] ) ? $list_opts['rating'] : '';
		$rating             = explode( '-', $rating );
		$this->current_time = current_time( 'U' );

		if ( ! empty( $name ) && ! empty( $comment_content ) && $qty_cmt > 0 ) {
			foreach ( $post_ids as $post_id ) {
				for ( $i = 0; $i < $qty_cmt; $i ++ ) {
					$time            = $this->random_time();
					$random_rating   = rand( $rating[0], $rating[1] );
					$random_key_cmt  = rand( 0, count( $list_opts['cmt'] ) - 1 );
					$random_key_name = rand( 0, count( $list_opts['names'] ) - 1 );
					$comment_content = ( $list_opts['cmt'][ $random_key_cmt ] );
					$name            = ( $list_opts['names'][ $random_key_name ] );
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
						'comment_date_gmt'     => $time,
						'comment_approved'     => 1,
					);

					$comment_id = wp_insert_comment( $data );
					if ( ! $comment_id ) {
						return false;
					}
					add_comment_meta( $comment_id, 'verified', 1 );
					add_comment_meta( $comment_id, 'rating', $random_rating );

					$product = wc_get_product( $post_id );

					if ( $product->is_type( 'variable' ) ) {
						$variation = $product->get_children();
						if ( $variation ) {
							$key = array_rand( $variation, 1 );
							update_comment_meta( $comment_id, 'wvr_variation', $variation[ $key ] );
						} else {
							wp_delete_comment( $comment_id );
							continue;
						}
					}

					$product->set_rating_counts( $this->get_rating_counts_for_product( $product ) );
					$product->set_average_rating( $this->get_average_rating_for_product( $product ) );
					$product->set_review_count( $this->get_review_count_for_product( $product ) );
					$product->save();

				}
			}

			return true;

		}
	}


	public function get_rating_counts_for_product( &$product ) {
		global $wpdb;

		$counts     = array();
		$raw_counts = $wpdb->get_results(
			$wpdb->prepare( "SELECT meta_value, COUNT( * ) as meta_value_count FROM $wpdb->commentmeta
				LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
				WHERE meta_key = 'rating'
				AND comment_post_ID = %d
				AND comment_approved = '1'
				AND meta_value > 0
				GROUP BY meta_value",
				$product->get_id()
			)
		);

		foreach ( $raw_counts as $count ) {
			$counts[ $count->meta_value ] = absint( $count->meta_value_count ); // WPCS: slow query ok.
		}

		return $counts;
	}


	public function get_average_rating_for_product( &$product ) {
		global $wpdb;

		$count = $product->get_rating_count();

		if ( $count ) {
			$ratings = $wpdb->get_var(
				$wpdb->prepare( "SELECT SUM(meta_value) FROM $wpdb->commentmeta
					LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
					WHERE meta_key = 'rating'
					AND comment_post_ID = %d
					AND comment_approved = '1'
					AND meta_value > 0",
					$product->get_id()
				)
			);
			$average = number_format( $ratings / $count, 2, '.', '' );
		} else {
			$average = 0;
		}

		return $average;
	}

	public function get_review_count_for_product( &$product ) {
		global $wpdb;

		$count = $wpdb->get_var(
			$wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->comments
				WHERE comment_parent = 0
				AND comment_post_ID = %d
				AND comment_approved = '1'
				AND (comment_type = 'review' OR comment_type = 'self_review')",
				$product->get_id()
			)
		);

		return $count;
	}

	public function random_time() {
		$now  = $this->current_time;
		$rand = rand( $now - 172800, $now );

		return date_i18n( 'Y-m-d H:i:s', $rand );
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

	public function delete_cmt() {
		$pid     = sanitize_text_field( $_POST['id'] );
		$cmt_arg = array(
			'post_id' => $pid,
			'type'    => 'self_review',
		);

		$cmts = get_comments( $cmt_arg );
		if ( count( $cmts ) ) {
			foreach ( $cmts as $cmt ) {
//				check($cmt->comment_ID);
				wp_delete_comment( $cmt->comment_ID );
			}
		}
		wp_send_json( $pid );
		wp_die();
	}

	public function add_reviews_all() {
		$pid     = sanitize_text_field( $_POST['id'] );
		$qty_cmt = sanitize_text_field( $_POST['qty'] );
		$review  = $this->add_multi_reviews( $pid, $qty_cmt ) == true ? true : false;
		$product = wc_get_product( $pid );
		$name    = $product->get_name();
		$link    = $product->get_permalink();
		$res     = array( $review, $name, $link );

		wp_send_json( $res );
		wp_die();
	}

	public function count_product() {

		$ids  = $ids_no_cmt = $id_cmt_exist = array();
		$args = array(
			'posts_per_page' => - 1,
			'post_type'      => 'product',
			'post_parent'    => '',
			'post_status'    => 'publish'
		);

		$products = get_posts( $args );
		foreach ( $products as $product ) {
			$ids[]   = $product->ID;
			$cmt_arg = array(
				'post_id' => $product->ID,
				'type'    => 'self_review',
				'offset'  => 1,
				'number'  => 1
			);

			$cmts      = get_comments( $cmt_arg );
			$cmt_exist = count( $cmts );

			if ( $cmt_exist == 0 ) {
				$ids_no_cmt[] = $product->ID;
			}
			if ( $cmt_exist > 0 ) {
				$id_cmt_exist[] = $product->ID;
			}
		}

		wp_send_json( array( $ids, $ids_no_cmt, $id_cmt_exist ) );
		wp_die();
	}

	public function add_review() {
		if ( check_admin_referer( 'wvr_add_manual_review' ) && isset( $_POST['wvr_manual_review'] ) ) {
			$data        = wc_clean( $_POST['wvr_manual_review'] );
			$post_id     = isset( $data['product'] ) ? sanitize_text_field( $data['product'] ) : null;
			$author_name = isset( $data['author'] ) ? sanitize_text_field( $data['author'] ) : null;
			$rating      = isset( $data['rating'] ) ? sanitize_text_field( $data['rating'] ) : null;
			$content     = isset( $data['review_content'] ) ? sanitize_text_field( $data['review_content'] ) : null;

			if ( $post_id && $author_name && $rating && $content ) {
				$time = current_time( 'mysql' );
				$data = array(
					'comment_post_ID'      => $post_id,
					'comment_author'       => $author_name,
					'comment_author_email' => 'comment by admin',
					'comment_author_url'   => '',
					'comment_content'      => $content,
					'comment_type'         => 'self_review',
					'comment_parent'       => 0,
					'user_id'              => 0,
					'comment_author_IP'    => '127.0.0.1',
					'comment_agent'        => 'admin',
					'comment_date'         => $time,
					'comment_date_gmt'     => $time,
					'comment_approved'     => 1,
				);

				$comment_id = wp_insert_comment( $data );
				add_comment_meta( $comment_id, 'verified', 1 );
				add_comment_meta( $comment_id, 'rating', $rating );

				$product = wc_get_product( $post_id );
				if ( $product->is_type( 'variable' ) ) {
					$available_variations = $product->get_available_variations();
					$count_var            = count( $available_variations );
					$random               = rand( 0, $count_var - 1 );
					$variation            = ( implode( " ", $available_variations[ $random ]['attributes'] ) );
					update_comment_meta( $comment_id, 'variation', $variation . " x 1" );
				} else {
					update_comment_meta( $comment_id, 'variation', 1 );
				}

				$product->set_rating_counts( $this->get_rating_counts_for_product( $product ) );
				$product->set_average_rating( $this->get_average_rating_for_product( $product ) );
				$product->set_review_count( $this->get_review_count_for_product( $product ) );
				$product->save();

			}
			wp_safe_redirect( $_POST['_wp_http_referer'] );
			exit;
		}
	}

	public function recount_comment() {
		if ( isset( $_POST['comment_post_ID'] ) && 'product' === get_post_type( absint( $_POST['comment_post_ID'] ) ) ) { // WPCS: input var ok, CSRF ok.
			$post_id = isset( $_POST['comment_post_ID'] ) ? absint( $_POST['comment_post_ID'] ) : 0; // WPCS: input var ok, CSRF ok.
			if ( $post_id ) {
				$this->clear_transients( $post_id );
			}
		}
	}

	public function clear_transients( $post_id ) {
		if ( 'product' === get_post_type( $post_id ) ) {
			$product = wc_get_product( $post_id );
			$product->set_review_count( $this->get_review_count_for_product( $product ) );
			$product->save();
		}
	}


}

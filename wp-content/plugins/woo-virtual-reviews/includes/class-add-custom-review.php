<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 07/11/2018
 * Time: 10:39 SA
 */
defined( 'ABSPATH' ) || exit();

class Wvr_add_custom_review extends Woo_virtual_reviews {

	public function insert_comment( $content, $author_name, $post_id, $rating ) {
		$time = current_time( 'mysql' );
		if ( ! empty( $post_id ) && ! empty( $content ) && ! empty( $author_name ) ) {
//			foreach ( $post_ids as $post_id ) {
//				$rand_key = ( array_rand( $author_name, 1 ) );
				$data     = array(
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
					'comment_approved'     => 1,
				);

				$comment_id = wp_insert_comment( $data );
				add_comment_meta( $comment_id, 'verified', 1, '' );
				add_comment_meta( $comment_id, 'rating', $rating, '' );

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

				$review_count = get_post_meta( $post_id, '_wc_review_count', true );
				$rating_count = get_post_meta( $post_id, '_wc_rating_count', true );

				if ( $review_count != array_sum( $rating_count ) ) {

					if ( ! isset( $rating_count[ $rating ] ) ) {
						$rating_count[ $rating ] = 1;
					} else {
						$rating_count[ $rating ] += 1;
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
//	}
}


<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.webtoffee.com
 * @since      1.0.0
 *
 * @package    Wt_Smart_Coupon
 * @subpackage Wt_Smart_Coupon/admin/partials
 */


 if( !function_exists('wt_smart_coupon_premium_features')) {
     function wt_smart_coupon_premium_features(){
         ?>
         <div class="wt_smart_coupon_pro_features">
                        <div class="wt_smart_coupon_premium">

                            <ul id="premium-upgrade-small-box" style="font-weight: bold; color:#666; list-style: none; background:#f8f8f8; padding:20px; margin:20px 15px; font-size: 15px; line-height: 26px;">
                                <li style=""><?php _e( '30 Day Money Back Guarantee','wt-smart-coupons-for-woocommerce' ); ?></li>
                                <li style=""><?php _e( 'Fast and Superior Support','wt-smart-coupons-for-woocommerce'); ?></li>
                                <li style="margin-top: 6px;">
                                    <a href="https://www.webtoffee.com/product/smart-coupons-for-woocommerce/" target="_blank" class="button button-primary button-go-pro"><?php _e( 'Upgrade to Premium','wt-smart-coupons-for-woocommerce'); ?></a>
                                </li>
                            </ul>
                            <span>
                                <ul class="ticked-list">
                                    <li><?php _e( 'Configure the coupons with extensive usage restrictions and checkout options','wt-smart-coupons-for-woocommerce'); ?></li>
                                    <li><?php _e( 'Create and manage bulk coupons with add to store/email/export to CSV options','wt-smart-coupons-for-woocommerce');?></li>
                                    <li><?php _e( 'Giveaway free products with coupons','wt-smart-coupons-for-woocommerce'); ?></li>
                                    <li><?php _e( 'Import coupons, Duplicate coupons','wt-smart-coupons-for-woocommerce'); ?></li>
                                    <li><?php _e( 'Ability to impose coupon usage restrictions on the basis of the country/location precisely with shipping or billing address apart from the default restrictions.','wt-smart-coupons-for-woocommerce'); ?> </li>
                                    <li><?php _e( 'Giveaway multiple free products with coupons.','wt-smart-coupons-for-woocommerce') ?> </li>
                                    <li><?php _e( 'Generate and manage bulk coupons with add to store/email/ export to CSV options.','wt-smart-coupons-for-woocommerce') ?> </li>
                                    <li><?php _e( 'Provision to upload and import coupons by simultaneously emailing it directly to the recipients.','wt-smart-coupons-for-woocommerce') ?> </li>
                                    <li><?php _e( 'Create and design gift vouchers of any amount range by associating a store credit product.','wt-smart-coupons-for-woocommerce') ?> </li>
                                    <li><?php _e( 'Manage store credits - create/purchase/transaction history/issue refunds/email.','wt-smart-coupons-for-woocommerce') ?> </li>
                                    <li><?php _e( 'Categorized view of Active/Used/Expired coupons from My Account > My Coupon.','wt-smart-coupons-for-woocommerce'); ?> </li>
                                    <li><?php _e( 'Provision to use Combo coupon for purchase.','wt-smart-coupons-for-woocommerce'); ?> </li>
                                    <li><?php _e( 'Option to exclude a product from coupon/s.','wt-smart-coupons-for-woocommerce'); ?> </li>
                                    <li><?php _e( 'Shortcode for displaying coupon on any page.','wt-smart-coupons-for-woocommerce'); ?> </li>
                                </ul>
                            </span>
                            <center> 
                        
                                <a href="https://www.webtoffee.com/smart-coupons-for-woocommerce-userguide/" target="_blank" style="margin-bottom: 15px;" class="button button-doc-demo"><?php _e( 'Documentation','wt-smart-coupons-for-woocommerce'); ?></a>
                            </center>

                        

                        </div>

                        <div class="wt-review-widget"><?php
                            echo '<div class=""><p><i>';
                            echo sprintf( __('If you like the plugin please leave us a %s review','wt-smart-coupons-for-woocommerce'),'<a href="https://wordpress.org/support/plugin/wt-smart-coupons-for-woocommerce/reviews/?rate=5#new-post" target="_blank" class="wt-rating-link" data-reviewed="' . esc_attr__('Thanks for the review.', 'wf-woocommerce-packing-list') . '">&#9733;&#9733;&#9733;&#9733;&#9733;</a>');
                            echo '</i><p></div>';
                            ?>
                        </div>
                    </div>
                </div>


         <?php
     }
 }
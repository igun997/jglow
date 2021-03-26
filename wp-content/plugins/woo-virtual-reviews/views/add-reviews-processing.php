<?php
/**
 * Created by PhpStorm.
 * User: Villatheme-Thanh
 * Date: 13-05-19
 * Time: 2:58 PM
 */
namespace WooVR;
defined( 'ABSPATH' ) || exit();
?>
<div class="vil-w3-row-padding">
    <!--    <div class="wvr-count-product-result"></div>-->
    <form class="vi-ui form" action="#">
        <table class="wvr-add-virtual-reviews-control-table">
            <tr>
                <td>
                    <div>
                        <input type="number" class="wvr-number-of-generate wvr-qty-cmt-all-products" min="0" max="20"
                               placeholder="<?php esc_html_e( 'Input quantity reviews per each product', 'woo-virtual-reviews' ) ?>">
                    </div>
                    <div>
                        <button type="button"
                                class="wvr-start-generate-reviews wvr-generate-for-all-products vil-w3-button vil-w3-blue vil-w3-round">
							<?php esc_attr_e( 'Start', 'woo-virtual-reviews' ) ?>
                        </button>
                    </div>
                    <div>
						<?php esc_attr_e( 'Add virtual reviews to all products (', 'woo-virtual-reviews' ) ?>
                        <span class="wvr-all-product-for-reviews"> </span>
						<?php echo ')' ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div>
                        <input type="number" class="wvr-number-of-generate wvr-qty-no-cmt-products" min="0" max="20"
                               placeholder="<?php esc_html_e( 'Input quantity reviews per each product', 'woo-virtual-reviews' ) ?>">
                    </div>
                    <div>
                        <button type="button"
                                class="wvr-start-generate-reviews wvr-generate-for-no-cmt-products vil-w3-button vil-w3-blue vil-w3-round">
							<?php esc_attr_e( 'Start', 'woo-virtual-reviews' ) ?>
                        </button>
                    </div>
                    <div>
						<?php esc_attr_e( 'Add to products which have no virtual reviews (', 'woo-virtual-reviews' ) ?>
                        <span class="wvr-product-no-vr-for-reviews"> </span>
						<?php echo ')' ?>
                    </div>
                </td>
            </tr>
        </table>
        <div class="wvr-processing-bar-group vil-w3-margin-top vil-w3-margin-bottom">
            <div class="wvr-processing-bar-outside">
                <div class="wvr-processing-bar-inside">
                </div>
            </div>
        </div>
        <div class="wvr-generate-processing-result-group">
            <table class="wvr-generate-processing-result-table">
                <tr class="wvr-generate-processing-result"></tr>
            </table>
        </div>
    </form>
</div>


<?php
/*
*   Add reviews
*/
defined( 'ABSPATH' ) || exit();
?>
    <h3><b><?php _e( 'Virtual Review Settings', 'woo-virtual-reviews' ) ?></b></h3>

    <div class="wvr-container">


        <div class="vi-ui top attached tabular menu">
            <a class="item active" data-tab="wvr-first"><?php _e( 'General', 'woo-virtual-reviews' ) ?></a>
            <a class="item" data-tab="wvr-second">
				<?php _e( 'Add Manual Review', 'woo-virtual-reviews' ) ?></a>
        </div>


        <div class="vi-ui bottom attached tab segment active " data-tab="wvr-first">
            <form class="vi-ui form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<?php wp_nonce_field( 'wvr_settings' ); ?>
				<?php require_once( WVR_VIEWS . "general-settings.php" ); ?>
				<?php require_once( WVR_VIEWS . "front-end-settings.php" ); ?>
                <div class="vil-w3-row-padding">
                    <div class="column">
                        <button type="submit" name="action"
                                class="wvr-btn wvr-save-sample vil-w3-button vil-w3-blue vil-w3-round vil-w3-margin-right"
                                value="save_option"><?php _e( "Save settings", "woo-virtual-reviews" ) ?>
                        </button>

                        <button type="button" name=""
                                class="wvr-btn wvr-add-reviews-all-product vil-w3-button vil-w3-green vil-w3-round"
                                value=""><?php _e( "Generate reviews", "woo-virtual-reviews" ) ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="vi-ui bottom attached tab segment " data-tab="wvr-second" id="wvr-add-manual-review-tab">
			<?php require_once( WVR_VIEWS . "add-custom-comment.php" ); ?>
        </div>


    </div>

    <div class="vi-ui bottom attached wvr-processing-block segment vil-w3-round" data-tab="" id="">
		<?php require_once( WVR_VIEWS . "add-reviews-processing.php" ); ?>
    </div>
<?php
do_action( 'villatheme_support_woo-virtual-reviews' );

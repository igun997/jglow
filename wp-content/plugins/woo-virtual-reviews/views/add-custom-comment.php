<?php
/*
*   Add single reviews
*/
defined( 'ABSPATH' ) || exit( ':)' );

?>
<form class="vi-ui form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	<?php wp_nonce_field( 'wvr_add_manual_review' ); ?>
    <div class="vil-w3-row-padding">
        <div class="vil-w3-third">
            <label class="vil-w3-col"><b><?php _e( 'Select product', 'woo-virtual-reviews' ); ?></b></label>
            <label class="wvr-note-label vil-w3-col"><i><?php _e( 'Select products to add manual review', 'woo-virtual-reviews' ) ?></i></label>
        </div>
        <div class="vil-w3-twothird">
            <!--            <select class="wvr-select2-product" name="wvr-select2-product[]" multiple="multiple"></select>-->
            <select required class="wvr-select2-product" name="wvr_manual_review[product]"></select>
        </div>
    </div>

    <div class="vil-w3-row-padding">
        <div class="vil-w3-third">
            <label class="vil-w3-col"><b><?php _e( 'Review', 'woo-virtual-reviews' ); ?></b></label>
            <label class="wvr-note-label vil-w3-col"><i><?php _e( 'Add your reviews', 'woo-virtual-reviews' ) ?></i></label>
        </div>
        <div class="vil-w3-twothird">
            <textarea required name="wvr_manual_review[review_content]"></textarea>
        </div>
    </div>

    <div class="vil-w3-row-padding">
        <div class="vil-w3-third">
            <label><b><?php _e( 'Author', 'woo-virtual-reviews' ); ?></b></label>
            <label class="wvr-note-label vil-w3-col"><i><?php _e( "Author's name which you want to display", 'woo-virtual-reviews' ) ?></i></label>
        </div>
        <div class="vil-w3-twothird">
            <input required type="text" class="wvr_author_name" name="wvr_manual_review[author]">
            <!--            <label class="wvr-note-label"><i>-->
			<?php //_e( "Nếu nhập nhiều tên thì các tên cách nhau bằng dấu phẩy ','. Tên tác giả sẽ được random cho sản phẩm đã chọn", 'woo-virtual-reviews' ) ?><!--</i></label>-->
        </div>
    </div>

    <div class="vil-w3-row-padding">
        <div class="vil-w3-third">
            <label><b><?php _e( 'Rating', 'woo-virtual-reviews' ); ?></b></label>
        </div>
        <div class="vil-w3-twothird">
            <select name="wvr_manual_review[rating]">
				<?php
				for ( $i = 5; $i >= 1; $i -- ) {
					echo "<option>$i</option>";
				}
				?>
            </select>
        </div>
    </div>

    <div class="vil-w3-row-padding">
        <div class="">
            <button type="submit" class="vil-w3-button vil-w3-blue vil-w3-round wvr-btn"
                    name="action" value="add_review">
				<?php _e( "Add review", "woo-virtual-reviews" ) ?>
            </button>
        </div>
        <label class="vil-w3-col wvr-manual-note"><?php esc_html_e( "* Use this feature when you want to add reviews for some specific products.", "woo-virtual-reviews" ) ?></label>

    </div>
</form>
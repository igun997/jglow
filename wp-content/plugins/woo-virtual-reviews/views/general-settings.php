<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 20/11/2018
 * Time: 1:59 CH
 */
namespace WooVR;
defined( 'ABSPATH' ) || exit();
?>

    <div class="vil-w3-row-padding">
        <div class="vil-w3-third">
            <label class="wvr-label vil-w3-col"><b><?php _e( 'Author', 'woo-virtual-reviews' ) ?></b></label>
            <label class="wvr-note-label vil-w3-col"><i><?php _e( 'List virtual author for generate multiple reviews', 'woo-virtual-reviews' ) ?></i></label>
        </div>
        <div class="vil-w3-twothird vil-w3-tooltip">
                <textarea class="wvr-list-name"
                          name="wvr_params[names]"
                          placeholder="<?php _e( "Add your list virtual names, example:&#10Alex&#10Anna&#10Ben", 'woo-virtual-reviews' ) ?>"><?php $this->display_textarea_values( 'names' ); ?></textarea>
            <span class="vil-w3-tooltip-content vil-w3-text"><?php _e( "Add your list virtual names, example:&#10Alex&#10Anna&#10Ben", 'woo-virtual-reviews' ) ?></span>
        </div>
    </div>

    <div class="vil-w3-row-padding">
        <div class="vil-w3-third">
            <label class="wvr-label vil-w3-col"><b><?php _e( 'Reviews', 'woo-virtual-reviews' ) ?></b></label>
            <label class="wvr-note-label vil-w3-col"><i><?php _e( 'List virtual reviews for generate multiple reviews', 'woo-virtual-reviews' ) ?></i></label>
        </div>
        <div class="vil-w3-twothird vil-w3-tooltip">
                <textarea class="wvr-list-cmt"
                          name="wvr_params[cmt]"
                          placeholder="<?php _e( "Add your list virtual comments, example:&#10I like it&#10Best product&#10Shipping fast", "woo-virtual-reviews" ) ?>"><?php $this->display_textarea_values( 'cmt' ); ?></textarea>
            <span class="vil-w3-tooltip-content vil-w3-text"><?php _e( "Add your list virtual comments, example:&#10I like it&#10Best product&#10Shipping fast", 'woo-virtual-reviews' ) ?></span>
        </div>
    </div>

    <div class="vil-w3-row-padding wvr-border-end">
        <div class="vil-w3-third">
            <label class="wvr-label vi-ui small header"><b><?php _e( 'Rating', 'woo-virtual-reviews' ) ?></b></label>
            <label class="wvr-note-label vil-w3-col"><i><?php _e( 'Random rating for generate multiple reviews', 'woo-virtual-reviews' ) ?></i></label>
        </div>
        <div class="vil-w3-twothird">
            <select name="wvr_params[rating]" class="wvr-select-rating ">
				<?php
				foreach ( Data::get_instance()->get_star_option() as $value => $view ) {
					?>
                    <option value="<?php echo $value ?>"
						<?php if ( $this->display_option_data( 'rating' ) == $value ) {
							echo "selected";
						} ?>>
						<?php echo $view ?>
                    </option>
					<?php
				}
				?>
            </select>
        </div>
    </div>



<!--<input type="hidden" name="action" value="save_option">-->

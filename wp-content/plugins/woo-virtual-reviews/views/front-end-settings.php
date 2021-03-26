<?php
/**
 * Created by PhpStorm.
 * User: Villatheme-Thanh
 * Date: 14-05-19
 * Time: 3:52 PM
 */
namespace WooVR;
defined( 'ABSPATH' ) || exit();
?>
<!------Canned------->

<div class="vil-w3-row-padding">
    <div class="vil-w3-third">
        <label class="wvr-label vi-ui small header"><b><?php _e( 'Auto reviews', 'woo-virtual-reviews' ) ?></b></label>
        <label class="wvr-note-label vil-w3-col"><i><?php esc_html_e( 'Auto select rating 5 star & auto add first comment', 'woo-virtual-reviews' ) ?></i></label>
    </div>
    <div class="vil-w3-twothird ">
        <div class="vi-ui toggle checkbox">
            <input type="checkbox" class="wvr-cb-auto-review" name="wvr_params[auto_rating]"
                   value="yes" <?php if ( $this->display_option_data( 'auto_rating' ) == 'yes' ) {
				echo "checked";
			} ?>><label></label>
        </div>
        <div>
            <input type="text" class="vil-w3-input wvr-first-comment" name="wvr_params[first_comment]"
                   value="<?php esc_html_e( $this->display_option_data( 'first_comment' ) ) ?>">
        </div>
    </div>
</div>

<div class="vil-w3-row-padding">
    <div class="vil-w3-third">
        <label class="wvr-label vi-ui small header"><b><?php _e( 'Show canned reviews', 'woo-virtual-reviews' ) ?></b></label>
    </div>
    <div class="vil-w3-twothird vi-ui toggle checkbox">
        <input type="checkbox" class="wvr-cb-show-canned" name="wvr_params[show_canned]"
               value="yes" <?php if ( $this->display_option_data( 'show_canned' ) == 'yes' ) {
			echo "checked";
		} ?>><label></label>
    </div>
</div>
<div class="vil-w3-row-padding">
    <div class="vil-w3-third">
        <label class="wvr-label vi-ui small header"><b><?php _e( 'Canned reviews', 'woo-virtual-reviews' ) ?></b></label>
        <label class="wvr-note-label vil-w3-col"><i><?php _e( 'List sample reviews for front-end display', 'woo-virtual-reviews' ) ?></i></label>
    </div>
    <div class="vil-w3-twothird vil-w3-tooltip">
                <textarea class="wvr-list-cmt-frontend"
                          name="wvr_params[cmt_frontend]"
                          placeholder="<?php _e( "Add your list comments display on front of your website (max = 50 sentences), example:&#10I like it&#10Best product&#10Shipping fast", "woo-virtual-reviews" ) ?>"><?php $this->display_textarea_values( 'cmt_frontend' ); ?></textarea>
        <span class="vil-w3-tooltip-content vil-w3-text"><?php _e( "Add your list virtual comments, example:&#10I like it&#10Best product&#10Shipping fast", 'woo-virtual-reviews' ) ?></span>
    </div>
</div>

<div class="vil-w3-row-padding ">
    <div class="vil-w3-third">
        <label class="wvr-label vi-ui small header"><b><?php _e( 'Canned style for Desktop', 'woo-virtual-reviews' ) ?></b></label>
        <label class="wvr-note-label vil-w3-col"><i><?php _e( 'Canned style for front-end display (width device > 800px)', 'woo-virtual-reviews' ) ?></i></label>
    </div>
    <div class="vil-w3-twothird">
        <select class="wvr-cb-show-canned-slide" name="wvr_params[canned_style_desktop]">
            <option value="select" <?php if ( $this->display_option_data( 'canned_style_desktop' ) == 'select' ) {
				echo "selected";
			} ?>><?php _e( "Dropdown list", "woo-virtual-reviews" ) ?></option>
            <option value="slide" <?php if ( $this->display_option_data( 'canned_style_desktop' ) == 'slide' ) {
				echo "selected";
			} ?>><?php _e( "Slide", "woo-virtual-reviews" ) ?></option>
        </select>
    </div>
</div>

<div class="vil-w3-row-padding ">
    <div class="vil-w3-third">
        <label class="wvr-label vi-ui small header"><b><?php _e( 'Canned style for Mobile', 'woo-virtual-reviews' ) ?></b></label>
        <label class="wvr-note-label vil-w3-col"><i><?php _e( 'Canned style for front-end display (width device < 800px)', 'woo-virtual-reviews' ) ?></i></label>
    </div>
    <div class="vil-w3-twothird">
        <select class="wvr-cb-show-canned-slide" name="wvr_params[canned_style_mobile]">
            <option value="select" <?php if ( $this->display_option_data( 'canned_style_mobile' ) == 'select' ) {
				echo "selected";
			} ?>><?php _e( "Dropdown list", "woo-virtual-reviews" ) ?></option>
            <option value="slide" <?php if ( $this->display_option_data( 'canned_style_mobile' ) == 'slide' ) {
				echo "selected";
			} ?>><?php _e( "Slide", "woo-virtual-reviews" ) ?></option>
        </select>
    </div>
</div>

<div class="vil-w3-row-padding">
    <div class="vil-w3-third">
        <label class="wvr-label vi-ui small header"><b><?php _e( 'Canned text color', 'woo-virtual-reviews' ) ?></b></label>
        <label class="wvr-note-label vil-w3-col"><i><?php _e( 'Canned text color for slide style', 'woo-virtual-reviews' ) ?></i></label>
    </div>
    <div class="vil-w3-twothird ">
        <input type="text" class="wvr-color-picker" name="wvr_params[canned_text_color]"
               value="<?php echo $this->display_option_data( 'canned_text_color' ) ?>"
               data-default-color="#000000">
    </div>
</div>

<div class="vil-w3-row-padding">
    <div class="vil-w3-third">
        <label class="wvr-label vi-ui small header"><b><?php _e( 'Canned background color', 'woo-virtual-reviews' ) ?></b></label>
        <label class="wvr-note-label vil-w3-col"><i><?php _e( 'Canned background color for slide style', 'woo-virtual-reviews' ) ?></i></label>
    </div>
    <div class="vil-w3-twothird ">
        <input type="text" class="wvr-color-picker" name="wvr_params[canned_bg_color]"
               value="<?php echo $this->display_option_data( 'canned_bg_color' ) ?>"
               data-default-color="#dddddd">
    </div>
</div>

<div class="vil-w3-row-padding">
    <div class="vil-w3-third">
        <label class="wvr-label vi-ui small header"><b><?php _e( 'Canned text hover color', 'woo-virtual-reviews' ) ?></b></label>
        <label class="wvr-note-label vil-w3-col"><i><?php _e( 'Canned text hover color for slide style', 'woo-virtual-reviews' ) ?></i></label>
    </div>
    <div class="vil-w3-twothird ">
        <input type="text" class="wvr-color-picker" name="wvr_params[canned_text_hover_color]"
               value="<?php echo $this->display_option_data( 'canned_text_hover_color' ) ?>"
               data-default-color="#ffffff">
    </div>
</div>

<div class="vil-w3-row-padding wvr-border-end">
    <div class="vil-w3-third">
        <label class="wvr-label vi-ui small header"><b><?php _e( 'Canned background hover color', 'woo-virtual-reviews' ) ?></b></label>
        <label class="wvr-note-label vil-w3-col"><i><?php _e( 'Canned background hover color for slide style', 'woo-virtual-reviews' ) ?></i></label>
    </div>
    <div class="vil-w3-twothird ">
        <input type="text" class="wvr-color-picker" name="wvr_params[canned_hover_color]"
               value="<?php echo $this->display_option_data( 'canned_hover_color' ) ?>"
               data-default-color="#ffffff">
    </div>
</div>

<!------Purchased Label------->

<div class="vil-w3-row-padding">
    <div class="vil-w3-third">
        <label class="wvr-label vi-ui small header"><b><?php _e( 'Show purchased label', 'woo-virtual-reviews' ) ?></b></label>
    </div>
    <div class="vil-w3-twothird vi-ui toggle checkbox">
        <input type="checkbox" class="wvr-cb-show-purchased" name="wvr_params[show_purchased_label]"
               value="yes" <?php if ( $this->display_option_data( 'show_purchased_label' ) == ( 'yes' || 1 || 'on' ) ) {
			echo "checked";
		} ?>>
        <label class=""> </label>
    </div>
</div>

<div class="vil-w3-row-padding">
    <div class="vil-w3-third">
        <label class="wvr-label vi-ui small header"><b><?php _e( 'Purchased icon', 'woo-virtual-reviews' ) ?></b></label>
        <label class="wvr-note-label vil-w3-col"><i><?php _e( 'Purchased icon for front-end display', 'woo-virtual-reviews' ) ?></i></label>
    </div>
    <div class="vil-w3-twothird">
		<?php
		foreach ( Data::get_icons() as $icon => $class ) {
			$checked = $this->display_option_data( 'purchased_label_icon' ) == $icon ? 'checked' : '';

			?>
            <div class="wvr-select-purchased-icon-block">
                <input type='radio' name='wvr_params[purchased_label_icon]'
                       value='<?php echo $icon ?>' <?php echo $checked ?>
                       id="wvr-<?php echo $icon ?>"
                />
                <label for="wvr-<?php echo $icon ?>"
                       class="wvr-select-purchased-icon <?php echo $checked ?>">
                    <i class="<?php echo $class ?>"> </i>
                </label>
                <!--                    <i class="" style="position: absolute; top: 0; left: 0">ICON</i>-->
            </div>
			<?php
		}
		?>
    </div>
</div>

<div class="vil-w3-row-padding">
    <div class="vil-w3-third">
        <label class="wvr-label vi-ui small header"><b><?php _e( 'Purchased icon color', 'woo-virtual-reviews' ) ?></b></label>
        <label class="wvr-note-label vil-w3-col"><i><?php _e( 'Purchased icon color for front-end display', 'woo-virtual-reviews' ) ?></i></label>
    </div>
    <div class="vil-w3-twothird ">
        <input type="text" class="wvr-color-picker" name="wvr_params[purchased_icon_color]"
               value="<?php echo $this->display_option_data( 'purchased_icon_color' ) ?>"
               data-default-color="#000000">
    </div>
</div>

<div class="vil-w3-row-padding">
    <div class="vil-w3-third">
        <label class="wvr-label vi-ui small header"><b><?php _e( 'Purchased label text color', 'woo-virtual-reviews' ) ?></b></label>
        <label class="wvr-note-label vil-w3-col"><i><?php _e( 'Purchased label text color for front-end display', 'woo-virtual-reviews' ) ?></i></label>
    </div>
    <div class="vil-w3-twothird ">
        <input type="text" class="wvr-color-picker" name="wvr_params[purchased_text_color]"
               value="<?php echo $this->display_option_data( 'purchased_text_color' ) ?>"
               data-default-color="#000000">
    </div>
</div>

<div class="vil-w3-row-padding">
    <div class="vil-w3-third">
        <label class="wvr-label vi-ui small header"><b><?php _e( 'Purchased label background color', 'woo-virtual-reviews' ) ?></b></label>
        <label class="wvr-note-label vil-w3-col"><i><?php _e( 'Purchased label background color for front-end display', 'woo-virtual-reviews' ) ?></i></label>
    </div>
    <div class="vil-w3-twothird ">
        <input type="text" class="wvr-color-picker" name="wvr_params[purchased_bg_color]"
               value="<?php echo $this->display_option_data( 'purchased_bg_color' ) ?>"
               data-default-color="#ffffff">
    </div>
</div>

<div class="vil-w3-row-padding">
    <div class="vil-w3-third">
        <label class="wvr-label vi-ui small header"><b><?php _e( 'Custom CSS', 'woo-virtual-reviews' ) ?></b></label>
    </div>
    <div class="vil-w3-twothird ">
            <textarea class="wvr-custom-css"
                      name="wvr_params[custom_css]"><?php $this->display_textarea_values( 'custom_css' ) ?></textarea>
    </div>
</div>

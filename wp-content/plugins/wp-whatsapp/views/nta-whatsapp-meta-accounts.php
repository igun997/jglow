<table class="form-table" id="nta-custom-wc-button-settings">
    <tbody>
        <tr>
            <th scope="row">
                <label for="nta_group_number">
                    <?php echo __('Account Number or group chat URL', 'ninjateam-whatsapp') ?>
                </label>
            </th>
            <td>
                <p>
                    <input type="text" class="widefat" id="nta_group_number" name="nta_group_number" value="<?php echo esc_attr(!empty($edit_account) ? $edit_account['nta_group_number'] : '') ?>" autocomplete="off">
                </p>
                <p class="description">
                    <?php echo __('Refer to <a href="https://faq.whatsapp.com/en/general/21016748" target="_blank">https://faq.whatsapp.com/en/general/21016748</a> for a detailed explanation.', 'ninjateam-whatsapp')?>
                </p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="nta_title"><?php echo __('Title','ninjateam-whatsapp')?></label>
            </th>
            <td>
                <input type="text" id="nta_title" name="nta_title" value="<?php echo esc_attr(!empty($edit_account) ? $edit_account['nta_title'] : '') ?>" class="widefat" autocomplete="off">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="nta_predefined_text"><?php echo __('Predefined Text', 'ninjateam-whatsapp')?></label>
            </th>
            <td>
                <textarea name="nta_predefined_text" id="nta_predefined_text" rows="3" class="widefat"><?php echo esc_textarea(!empty($edit_account) ? $edit_account['nta_predefined_text'] : '') ?></textarea>
                <p class="description"> 
                    <?php echo __('Use [njwa_page_title] and [njwa_page_url] shortcodes to output the page\'s title and URL respectively.', 'ninjateam-whatsapp') ?>
                </p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="nta_button_label"><?php echo __('Button Label','ninjateam-whatsapp')?></label>
            </th>
            <td>
                <input type="text" id="nta_button_label" name="nta_button_label" value="<?php echo esc_attr(!empty($edit_button_label['button-text']) ? $edit_button_label['button-text'] : '') ?>" placeholder="Need help? Chat via WhatsApp"
                       class="widefat" autocomplete="off">
                <p class="description"><?php echo __('This text applies only on shortcode button. Leave empty to use the default label.','ninjateam-whatsapp')?>
                </p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="nta_button_available"><?php echo __('Always available online','ninjateam-whatsapp')?></label>
            </th>
            <td>
            <div class="nta-wa-switch-control">
                <input type="checkbox" id="nta-wa-switch" name="nta_button_available" <?php echo esc_attr(isset($edit_account['nta_button_available']) ? 'checked' : '') ?>>
                <label for="nta-wa-switch" class="green"></label>
            </div>
            </td>
        </tr>
        
        <tr class="nta-btncustom-offline <?php echo (isset($edit_account['nta_button_available'])) ? 'hidden' : '' ?>">
            <th scope="row">
                <label><?php echo __('Custom Availability', 'ninjateam-whatsapp')?></label>
            </th>
            <td>
                <table class="form-table time-available">
                    <tbody>
                        <?php foreach($dayOfWeek as $day): ?>
                        <tr>
                            <td width="150">
                                <input type="checkbox" id="nta_<?php echo esc_attr($day) ?>" name="nta_<?php echo esc_attr($day) ?>" <?php echo (!empty($edit_account) ? $edit_account["nta_{$day}"] : '') ?>>
                                <label for="nta_<?php echo esc_attr($day) ?>"><?php echo __(ucfirst($day),'ninjateam-whatsapp')?> </label>
                            </td>   
                            <td width="100">
                                <select name="nta_<?php echo esc_attr($day) ?>_hour_start" class="nta_<?php echo esc_attr($day) ?>_hour_start nta_hour_start"><?php echo (!empty($edit_account) ? NTA_Helper::get_times(substr($edit_account["nta_{$day}_working"], 0, 5)) : NTA_Helper::get_times()); ?></select>
                            </td>
                            <td width="100">
                                <select name="nta_<?php echo esc_attr($day) ?>_hour_end" class="nta_<?php echo esc_attr($day) ?>_hour_end nta_hour_end"><?php echo (!empty($edit_account) ? NTA_Helper::get_times(substr($edit_account["nta_{$day}_working"], 6, 5)) : NTA_Helper::get_times('17:30')); ?></select>
                            </td>
                            <?php if ($day === 'sunday'): ?>
                                <td>
                                    <a href="javascript:;" type="button" class="button" id="btn-apply-time"><?php echo __('Apply to All Days','ninjateam-whatsapp')?></button>
                                </td>
                            <?php endif?>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr class="nta-btncustom-offline <?php echo (isset($edit_account['nta_button_available'])) ? 'hidden' : '' ?>">
            <th scope="row"><label for="nta_offline_text"><?php echo __('Description text when offline','ninjateam-whatsapp')?></label></th>
            <td>
                <input type="text" id="nta_offline_text" name="nta_offline_text" value="<?php echo esc_attr(!empty($edit_account) ? $edit_account['nta_offline_text'] : 'I will be back in [njwa_time_work]') ?>" class="widefat" autocomplete="off">
                <p class="description"><?php echo __('You can use shortcode [njwa_time_work] to display the exact time this account is back to work on a working day.', 'ninjateam-whatsapp')?></p>
                <input type="text" id="nta_over_time" name="nta_over_time" value="<?php echo esc_attr(!empty($edit_account) ? $edit_account['nta_over_time'] : 'I will be back soon') ?>" class="widefat" autocomplete="off">
                <p class="description"><?php echo __('You can use this text to display on days this account does not work.','ninjateam-whatsapp') ?>
                </p>
            </td>
        </tr>
    </tbody>
</table>
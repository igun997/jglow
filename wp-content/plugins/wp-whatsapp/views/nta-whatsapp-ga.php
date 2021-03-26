<input type="hidden" name="option_page" value="<?php echo esc_attr($option_group); ?>">
<input type="hidden" name="action" value="update">
<?php wp_nonce_field($option_group . '-options'); ?>
<p><?php echo __('By enabling this function, you can see our WhatsApp tracking in your Google Analytics > Behavior > Events.', 'ninjateam-whatsapp') ?></p>
<table class="form-table">
    <tbody>
        <tr>
            <th scope="row"><label for="nta-wa-switch-control"><?php echo __('Enabled', 'ninjateam-whatsapp') ?></label></th>
            <td>
                <div class="nta-wa-switch-control">
                    <input type="checkbox" id="nta-wa-switch" name="nta_wa_ga_status" <?php echo esc_attr($status ? 'checked' : '') ?>>
                    <label for="nta-wa-switch" class="green"></label>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<p><input type="submit" id="submit" class="button button-primary" value="<?php echo __('Save','ninjateam-whatsapp') ?>"></p>
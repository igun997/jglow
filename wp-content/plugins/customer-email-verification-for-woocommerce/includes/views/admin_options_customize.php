<?php
/**
 * html code for customize tab
 */
?>
<section id="cev_content_customize" class="cev_tab_section">
	<div class="cev_tab_inner_container">
					<h3 class="cev-customer-view"><?php _e( 'Customer View', 'customer-email-verification-for-woocommerce' ); ?></h3>		
        <table class="form-table table-border">
			<tbody>
				<tr valign="top" class="cev-verification-border">
                	<th class="middle-cev"> Customize the verification email </th>						
					<td class="button-column">
						<a href="<?php echo cev_initialise_customizer_settings::get_customizer_url('cev_controls_section'); ?>" class="button-primary cev-btn-large"><?php _e( 'Launch Email Customizer', 'customer-email-verification-for-woocommerce' ); ?></a>
                        <?php do_action('customize_verification_new_account_email');?> 
					</td>
			</tbody>	
		</table>
        <?php do_action('customize_popup_widget');
         	  do_action('customize_popup_widget_cev_free');?>
      			   			
	</div>	
		
</section>
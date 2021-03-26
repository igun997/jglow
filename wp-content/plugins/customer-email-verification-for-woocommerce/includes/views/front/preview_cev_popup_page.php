<?php 
$cev_verification_overlay_color = get_option('cev_verification_popup_overlay_background_color','#74c2e1'); 
 ?> <style>
		.cev-authorization-grid__visual{
			background: <?php echo woo_customer_email_verification()->hex2rgba($cev_verification_overlay_color,'0.7'); ?>;	
		}		
		</style> <?php
		
	$current_user = wp_get_current_user();
	$email = $current_user->user_email;	 ?>

		<div class="cev-authorization-grid__visual" style="background: <?php echo get_option('cev_verification_popup_overlay_background_color'); ?>;" >
			<div class="cev-authorization-grid__holder">
				<div class="cev-authorization-grid__inner">
					<div class="cev-authorization">				
						<form class="cev_pin_verification_form" method="post">                    					
					<section class="cev-authorization__holder">
                   			 <div class="popup_image">	                                 
							<?php 
									$image = apply_filters( 'cev_verification_popup_image', woo_customer_email_verification()->plugin_dir_url(). 'assets/css/images/email-verification-icon.svg'); ?>                                 
									<img src="<?php echo $image; ?>">
                            </div>
								<div class="cev-authorization__heading">
									<span class="cev-authorization__title">
								<?php 
										$heading = apply_filters( 'cev_verification_popup_heading', __( 'Verify its you.', 'customer-email-verification-for-woocommerce' ) ); 
										echo $heading; ?></span>
									<span class="cev-authorization__description">
							 <?php
									$message = apply_filters( 'cev_verification_popup_message', __('We sent verification code to johny@example.com. To verify your email address, please check your inbox and enter the code below.', 'customer-email-verification-for-woocommerce' ),$email); 
									$message = str_replace('{customer_email}',$email, $message);
									    echo $message; ?> 
                            		 </span>
								</div>
						<div class="cev-pin-verification">								
							<div class="cev-pin-verification__row">
								<div class="cev-field cev-field_size_extra-large cev-field_icon_left cev-field_event_right cev-field_text_center">
									<h5 class="required-filed"><?php $codelength = apply_filters( 'cev_verification_code_length', __( '4-digits code', 'customer-email-verification-for-woocommerce' ) ); 
										echo  $codelength; ?>*</h5>
									<input class="cev_pin_box" id="cev_pin1" name="cev_pin1" type="text" placeholder="Enter <?php $codelength = apply_filters( 'cev_verification_code_length', __( '4-digits code', 'customer-email-verification-for-woocommerce' ) ); 
										echo  $codelength; ?>" >
								</div>
							</div>
							<div class="cev-pin-verification__failure js-pincode-invalid" style="display: none;">
								<div class="cev-alert cev-alert_theme_red">										
									<span class="js-pincode-error-message">Invalid PIN Code</span>
								</div>
							</div>
							<div class="cev-pin-verification__events">
								<input type="hidden" name="cev_user_id" value="8">
								<input type="hidden" name="action" value="cev_verify_user_email_with_pin">
								<h2 class="cev-button cev-button_color_success cev-button_size_promo cev-button_type_block cev-pin-verification__button is-disabled" id="" type="submit">
									<?php _e('Verify Code','customer-email-verification-for-woocommerce') ?><i class="cev-icon cev-icon_size_medium dmi-continue_arrow_24 cev-button__visual cev-button__visual_type_fixed"></i>
								</h2>									
							</div>
						</div>
					</section>
					<footer class="cev-authorization__footer">
                     <?php $Troubleshooting = apply_filters( 'cev_verification_popup_Troubleshooting', __( 'Troubleshooting guide', 'customer-email-verification-for-woocommerce' ), '' ); 
							if($Troubleshooting != 'Troubleshooting guide'){
								$Troubleshooting_link = esc_url( get_page_link( $Troubleshooting ) );
							}
							?>
							<?php _e( 'Didn’t receive an email?', 'customer-email-verification-for-woocommerce' ); ?> <?php if($Troubleshooting != 'Troubleshooting guide'){ _e( 'check out our', 'customer-email-verification-for-woocommerce' ); ?><a href="<?php echo $Troubleshooting_link;?>"class="cev-link-try-agin"> <?php _e( 'Troubleshooting guide', 'customer-email-verification-for-woocommerce' );?></a><?php _e( ' or ', 'customer-email-verification-for-woocommerce' );?><?php } ?> 
                             <h7 href=""class="cev-link-try-again">
                            <?php _e( 'Try again', 'customer-email-verification-for-woocommerce' );?></h7>
							</footer>
				</form>            
			</div>
		</div>
	</div>
</div>

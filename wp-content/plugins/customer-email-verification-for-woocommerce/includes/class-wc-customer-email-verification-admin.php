<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_customer_email_verification_admin {		
	
	public $my_account_id;
	
	/**
	 * Initialize the main plugin function
	*/
    public function __construct() {	
		$this->my_account_id = get_option( 'woocommerce_myaccount_page_id' );
	}
	
	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	 */
	private static $instance;
	
	/**
	 * Get the class instance
	 *
	 * @return woo_customer_email_verification_Admin
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	/*
	* init from parent mail class
	*/
	public function init(){
		add_action( 'wp_ajax_cev_settings_form_update', array( $this, 'cev_settings_form_update_fun') );
		add_filter( 'manage_users_columns', array( $this, 'add_column_users_list' ), 10, 1 );
		add_filter( 'manage_users_custom_column', array( $this, 'add_details_in_custom_users_list' ), 10, 3 );
		add_action( 'show_user_profile', array( $this, 'show_cev_fields_in_single_user' ) );
		add_action( 'edit_user_profile', array( $this, 'show_cev_fields_in_single_user' ) );
		add_action( 'admin_head', array( $this, 'cev_manual_verify_user' ) );      

		/*** Sort and Filter Users ***/
		add_action('restrict_manage_users', array( $this, 'filter_user_by_verified' ));	
		add_filter('pre_get_users', array( $this, 'filter_users_by_user_by_verified_section' ));
		
		/*** Bulk actions for Users ***/
		add_filter( 'bulk_actions-users', array( $this, 'add_custom_bulk_actions_for_user' ) );
		add_filter( 'handle_bulk_actions-users', array( $this, 'users_bulk_action_handler' ), 10, 3 );
		add_action( 'admin_notices', array( $this, 'user_bulk_action_notices' ) );
	}
	
	/*
	* Admin Menu add function
	* WC sub menu
	*/
	public function register_woocommerce_menu() {
		add_submenu_page( 'woocommerce', 'Customer Verification', 'Customer Verification', 'manage_woocommerce', 'customer-email-verification-for-woocommerce', array( $this, 'wc_customer_email_verification_page_callback' ) ); 
	}
	
	/**
	* Load admin styles.
	*/
	public function admin_styles($hook) {						
		
		if(!isset($_GET['page'])) {
			return;
		}
		if( $_GET['page'] != 'customer-email-verification-for-woocommerce') {
			return;
		}
		
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';				

		wp_register_script( 'select2', WC()->plugin_url() . '/assets/js/select2/select2.full' . $suffix . '.js', array( 'jquery' ), '4.0.3' );
		wp_enqueue_script( 'select2');
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'customer_email_verification_styles',  woo_customer_email_verification()->plugin_dir_url() . 'assets/css/admin.css', array(), woo_customer_email_verification()->version );
				
		wp_enqueue_script( 'customer_email_verification_script', woo_customer_email_verification()->plugin_dir_url() . 'assets/js/admin.js', array( 'jquery','wp-util' ), woo_customer_email_verification()->version , true);
		
		wp_localize_script( 'customer_email_verification_script', 'customer_email_verification_script', array() );
		
		wp_register_script( 'selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo.full' . $suffix . '.js', array( 'jquery' ), '1.0.4' );
		wp_register_script( 'wc-enhanced-select', WC()->plugin_url() . '/assets/js/admin/wc-enhanced-select' . $suffix . '.js', array( 'jquery', 'selectWoo' ), WC_VERSION );
		wp_register_script( 'jquery-blockui', WC()->plugin_url() . '/assets/js/jquery-blockui/jquery.blockUI' . $suffix . '.js', array( 'jquery' ), '2.70', true );
		
		wp_enqueue_script( 'selectWoo');
		wp_enqueue_script( 'wc-enhanced-select');
		
		wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
		wp_enqueue_style( 'woocommerce_admin_styles' );
		
		wp_enqueue_script( 'cev-material-min-js',  woo_customer_email_verification()->plugin_dir_url() . 'assets/js/material.min.js', array(), woo_customer_email_verification()->version );
		
		wp_enqueue_style( 'cev-material-css',  woo_customer_email_verification()->plugin_dir_url() . 'assets/css/material.css', array(), woo_customer_email_verification()->version );
		
		wp_register_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip.min.js', array( 'jquery' ), WC_VERSION, true );
		wp_enqueue_script( 'jquery-tiptip' );
		wp_enqueue_script( 'jquery-blockui' );
		wp_enqueue_script( 'wp-color-picker' );									
	}
	
	/*
	* callback for Customer Email Verification page
	*/
	public function wc_customer_email_verification_page_callback(){ 
		
	wp_enqueue_script( 'customer_email_verification_table_rows' );	
		$wc_ast_api_key = get_option('wc_ast_api_key'); ?>
        
        
			<div class="zorem-layout-cev__header">
			<h1 class="zorem-layout-cev__header-breadcrumbs">
				<span><a href="<?php echo esc_url( admin_url( '/admin.php?page=wc-admin' ) ); ?>"><?php _e('WooCommerce', 'woocommerce'); ?></a></span>
				<span><a href="<?php echo esc_url( admin_url( '/admin.php?page=customer-email-verification-for-woocommerce' ) ); ?>"><?php _e('Customer Email Verification', 'customer-email-verification-for-woocommerce'); ?></a></span><span class="header-breadcrumbs-last-cev"><?php _e('Settings', 'woocommerce'); ?></span>
			</h1>
			<div class="zorem-layout__logo-panel">
				<img class="header-plugin-logo" src="<?php echo woo_customer_email_verification()->plugin_dir_url(__FILE__)?>assets/images/cev-logo.png">	
                <div class="cev_menu cev_dropdown">
							<span class="dashicons dashicons-ellipsis cev-dropdown-menu"></span>
							<ul class="cev-dropdown-content">
								<li><a href="javaScript:void(0);" data-label="<?php _e('Settings', 'woocommerce'); ?>" data-tab="settings" data-section="cev_content_settings"><?php _e('Settings', 'woocommerce'); ?></a></li>
								<li><a href="javaScript:void(0);" data-label="<?php _e('Customer View', 'customer-email-verification-for-woocommerce'); ?>" data-tab="customer-view" data-section="cev_tab_inner_container"><?php _e('Customize', 'customer-email-verification-for-woocommerce'); ?></a></li>
								<li><a href="javaScript:void(0);" data-label="<?php _e('Tools', 'customer-email-verification-for-woocommerce'); ?>" data-tab="tools" data-section="cev_tab_inner_container"><?php _e('Tools', 'customer-email-verification-for-woocommerce'); ?></a></li>
                                <li><a href="javaScript:void(0);" data-label="<?php _e('Add-ons', 'customer-email-verification-for-woocommerce'); ?>" data-tab="add-ons" data-section="cev_tab_inner_container"><?php _e('Add-ons', 'customer-email-verification-for-woocommerce'); ?></a></li>
							</ul>	
						</div>			
			</div>
		</div>
        
      
        <div class="woocommerce cev_admin_layout">
            <div class="cev_admin_content" >
            	<div class="cev_nav_div">	
					<?php 
						$this->get_html_menu_tab( $this->get_cev_tab_settings_data());												   								
						require_once( 'views/admin_options_settings.php' );
						require_once( 'views/admin_options_customize.php' ); 
						do_action('cev_pro_tools_settings_tab');
						require_once( 'views/cev_addons_tab.php' );
					?>		
				</div>
            </div>				 
        </div> 
    
		<div id="cev-toast-example" aria-live="assertive" aria-atomic="true" aria-relevant="text" class="mdl-snackbar mdl-js-snackbar">
			<div class="mdl-snackbar__text"></div>
			<button type="button" class="mdl-snackbar__action"></button>
		</div>
	<?php }

	public function get_html_menu_tab( $arrays ){ 
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field($_GET['tab']) : 'settings';
		foreach( (array)$arrays as $id => $array ){ ?>
			<input class="cev_tab_input" id="<?php echo $id?>" name="<?php echo $array['name']; ?>" type="radio"  data-tab="<?php echo $array['data-tab']; ?>" data-label="<?php echo $array['data-label']; ?>" <?php if($tab == $array['data-tab']){ echo 'checked'; } ?> />
			<label class="<?php echo $array['class']; ?>" for="<?php echo $id?>"><?php echo $array['title']; ?></label>
        <?php }
	}	
	function get_cev_tab_settings_data(){	
			
		$setting_data = array(
			'setting_tab' => array(					
				'title'		=> __( 'Settings', 'customer-email-verification-for-woocommerce' ),
				'show'      => true,
				'class'     => 'cev_tab_label first_label',
				'data-tab'  => 'settings',
				'data-label' => 'settings',
				'name'  => 'tabs',				
			),
			'customize_tab' => array(					
				'title'		=> __( 'Customer View', 'customer-email-verification-for-woocommerce' ),
				'show'      => true,
				'class'     => 'cev_tab_label',
				'data-tab'  => 'customer-view',
				'data-label' => 'Customer View',
				'name'  => 'tabs',
			),			
			'Add_tab' => array(					
				'title'		=> __( 'Add-ons', 'customer-email-verification-for-woocommerce' ),
				'show'      => true,
				'class'     => 'cev_tab_label',
				'data-tab'  => 'add-ons',
				'data-label' => 'Add-ons',
				'name'  => 'tabs',
			),
		);
		$setting_data = apply_filters( 'cev_tools_tab_options', $setting_data );
		return $setting_data;
	}
		
	/*
	* get html of fields
	*/
	
	public function get_html( $arrays ){
		
		$checked = '';
		?>
		<table class="form-table box-border ">
			<tbody>
            	<?php foreach( (array)$arrays as $id => $array ){
				
					if($array['show']){
					?>
                	<?php if($array['type'] == 'title'){ ?>
                		<tr valign="top titlerow">
                        	<th colspan="2"><h3><?php echo $array['title']?></h3></th>
                        </tr>    	
                    <?php continue;}
						if( $array['type'] == 'multiple_checkbox' ){
							$op = 1;
							?>
							<tr valign="top titlerow">
								<td colspan="2" class="cev-skip-padding">
									<strong for=""><?php echo $array['title']?><?php if(isset($array['title_link'])){ echo $array['title_link']; } ?>
										<?php if( isset($array['tooltip']) ){?>
											<span class="woocommerce-help-tip tipTip" title="<?php echo $array['tooltip']?>"></span>
										<?php } ?>
									</strong>
									<div class="cev-skip-multiple-checkbox">
									<?php
									foreach((array)$array['options'] as $key => $val ){
																			
											$multi_checkbox_data = get_option($id);
											if(isset($multi_checkbox_data[$key]) && $multi_checkbox_data[$key] == 1){
												$checked="checked";
											} else{
												$checked="";
											}?>
									<span class="multiple_checkbox">
										<label class="" for="<?php echo $key?>">
											<input type="hidden" name="<?php echo $id?>[<?php echo $key?>]" value="0"/>
											<input type="checkbox" id="<?php echo $key?>" name="<?php echo $id?>[<?php echo $key?>]" class=""  <?php echo $checked; ?> value="1"/>
											<span class="multiple_label"><?php echo $val; ?></span>	
											</br>
										</label>																		
									</span>												
									<?php 								
									}  ?>
									</div>
								</td>
							</tr>
					<?php continue; } ?>
				<tr valign="top" class="<?php echo $array['class']; ?>">
					<?php if($array['type'] != 'desc'){ ?>										
					<th scope="row" class="titledesc"  >
						<label for=""><?php echo $array['title']?><?php if(isset($array['title_link'])){ echo $array['title_link']; } ?>
							<?php if( isset($array['tooltip']) ){?>
                            	<span class="woocommerce-help-tip tipTip" title="<?php echo $array['tooltip']?>"></span>
                            <?php } ?>
                        </label>
					</th>
					<?php } ?>
                    <td class="forminp" <?php if($array['type'] == 'desc'){ ?> colspan=2 <?php } ?>>
                    	<?php if( $array['type'] == 'checkbox' ){								
							if(get_option($id)){
									$checked = 'checked';
								} else{
									$checked = '';
								} 							
							?>
						<input type="hidden" name="<?php echo $id?>" value="0"/>
						<input class="tgl tgl-flat-cev" id="<?php echo $id?>" name="<?php echo $id?>" type="checkbox" <?php echo $checked ?> value="1"/>
							<label class="tgl-btn" for="<?php echo $id?>"></label>
                           								
                        <?php } elseif( isset( $array['type'] ) && $array['type'] == 'dropdown' ){ ?>
                        	<?php
								if( isset($array['multiple']) ){
									$multiple = 'multiple';
									$field_id = $array['multiple'];
								} else {
									$multiple = '';
									$field_id = $id;
								}
							?>
                        	<fieldset>
								<select class="select select2" id="<?php echo $field_id?>" name="<?php echo $id?>" <?php echo $multiple;?>>  
								<?php foreach((array)$array['options'] as $key => $val ){?>
                                    	<?php
											$selected = '';
											if( isset($array['multiple']) ){
												if (in_array($key, (array)$this->data->$field_id ))$selected = 'selected';
											} else {
												if( get_option($id) == (string)$key )$selected = 'selected';
											}
                                        
										?>
										<option value="<?php echo $key?>" <?php echo $selected?> ><?php echo $val?></option>
                                    <?php } ?>
                                    
                                    
								</select>
							</fieldset>
                           <?php } elseif( isset( $array['type'] ) && $array['type'] == 'dropdown_checkbox' ){ ?>
                        	<?php
								if( isset($array['multiple']) ){
									$multiple = 'multiple';
									$field_id = $array['multiple'];
								} else {
									$multiple = '';
									$field_id = $id;
								}
							?>
                        	<fieldset>
								<select class="cev_dropdown select2" id="<?php echo $field_id?>" name="<?php echo $id?>" <?php echo $multiple;?>>  
								<?php foreach((array)$array['options'] as $key => $val ){?>
                                    	<?php
											$selected = '';
											if( isset($array['multiple']) ){
												if (in_array($key, (array)$this->data->$field_id ))$selected = 'selected';
											} else {
												if( get_option($id) == (string)$key )$selected = 'selected';
											}
                                        
										?>
										<option value="<?php echo $key?>" <?php echo $selected?> ><?php echo $val?></option>
                                  
								 <?php } ?>	
								</select>											                                           								 
							</fieldset>
                            
                        <?php } elseif( isset( $array['type'] ) && $array['type'] == 'radio' ){ ?>                        	
                        	<fieldset class="radio-option">
								<?php foreach((array)$array['options'] as $key => $val ){
									$selected = '';
									if( get_option($id,$array['default']) == (string)$key )$selected = 'checked';
									?>
									<span class="radio_section">
										<label class="" for="<?php echo $id?>_<?php echo $key?>">												
											<input type="radio" id="<?php echo $id?>_<?php echo $key?>" name="<?php echo $id?>" class="<?php echo $id?>"  value="<?php echo $key?>" <?php echo $selected?>/>
                                            <span class=""><?php echo $val; ?></span></br>
                                            
                                            
										</label>   
                                      </span></br>	
                                <?php } ?>								
							</fieldset>
                                                    
                        <?php } elseif( isset( $array['type'] ) && $array['type'] == 'radio_checkbox' ){ ?>                        	
                        	<fieldset class="radio-option">
								<?php foreach((array)$array['options'] as $key => $val ){
									$selected = '';
									if( get_option($id,$array['default']) == (string)$key )$selected = 'checked';
									?>
									<span class="radio_section">
                                   
										<label class="cev_radio_checkbox" for="<?php echo $id?>_<?php echo $key?>">
											<input type="radio" id="<?php echo $id?>_<?php echo $key?>" name="<?php echo $id?>" class="<?php echo $id?>"  value="<?php echo $key?>" <?php echo $selected?>/>
                                            <span class=""><?php echo $val; ?></span></br>
										</label>
                                       
                                      </span></br>	
                                <?php } 
										foreach((array)$array['checkbox_options'] as $key => $val ){
											$checked = '';											
											if( get_option($key,'') == 1 )$checked = 'checked'; ?>
                                            <div class="delay_border">	
											<input type="hidden" name="<?php echo $key?>" value="0" />
											<input class="" id="<?php echo $key?>" name="<?php echo $key?>" type="checkbox" <?php echo $checked ?> value="1" />
											<label class="" for="<?php echo $key?>"><?php echo $val; ?></label>
                                 </div>   
								 <?php } ?>								
							</fieldset>
                                                    
                        <?php } elseif( $array['type'] == 'title' ){ ?>
						<?php }
						elseif( $array['type'] == 'label' ){ ?>
                        
							<fieldset>
                               <label><?php echo $array['value']; ?></label>
                            </fieldset>
						<?php }
						elseif( $array['type'] == 'tooltip_button' ){ ?>
							<fieldset>
								<a href="<?php echo $array['link']; ?>" class="button-primary" target="<?php echo $array['target'];?>"><?php echo $array['link_label'];?></a>
                            </fieldset>
						<?php }
						elseif( $array['type'] == 'link' ){ ?>
							<fieldset>
								<a href="<?php echo $array['url'];?>" class="button-primary"><?php echo $array['label'];?></a>								
							</fieldset>
						<?php }
						elseif( $array['type'] == 'textarea' ){ ?>
							<fieldset>
								<textarea placeholder="<?php if(!empty($array['placeholder'])){echo $array['placeholder'];} ?>" class="input-text regular-input" name="<?php echo $id?>" id="<?php echo $id?>"><?php echo get_option($id)?></textarea>                                
                            </fieldset>
                            <span class="" style="font-size: 12px;"><?php echo $array['desc_tip'];?></span>
						<?php }
						elseif( $array['type'] == 'media' ){ ?>
							<fieldset>
                                <input id="upload-button" id="<?php echo $id?>" type="button" class="button " value="Upload Image" <?php if(!empty(get_option($id))) {?>style="display:none;"<?php } ?>/>
                                <input id="uploaded_image" name="<?php echo $id?>" type="hidden" value="" />
                                <img id="widget-image" height="65" <?php if(!empty(get_option($id))) {?>src="<?php echo get_option($id);?>" <?php }?>><?php if(!empty(get_option($id))) {?><span class="dashicons dashicons-no cev-close-btn"></span><?php } ?>
								
                            </fieldset>
						<?php }
						elseif( $array['type'] == 'tag_block' ){ ?>
							<fieldset class="tag_block">
								<code>{customer_email_verification_code}</code><code>{cev_user_verification_link}</code><code>{cev_resend_email_link}</code><code>{cev_display_name}</code><code>{cev_user_login}</code><code>{cev_user_email}</code> 								
                            </fieldset>
						<?php }
						else { ?>
                                                    
                        	<fieldset>
                                <input class="input-text regular-input " type="text" name="<?php echo $id?>" id="<?php echo $id?>" style="" value="<?php echo get_option($id)?>" placeholder="<?php if(!empty($array['placeholder'])){echo $array['placeholder'];} ?>">
                            </fieldset>
                        <?php } ?>
                        
					</td>
				</tr>
				
				<?php 
				if(isset($array['dropdown_checkbox_options'])){
					foreach((array)$array['dropdown_checkbox_options'] as $key => $val ){
						$checked = '';											
						if( get_option($key,'') == 1 )$checked = 'checked'; ?>
						<tr class="cev-setting-checkbox">
							<td colspan="2" class="cev-setting-checkbox-padding">
								<input type="hidden" name="<?php echo $key?>" value="0" />
								<input class="" id="<?php echo $key?>" name="<?php echo $key?>" type="checkbox" <?php echo $checked ?> value="1" />
								<label class="" for="<?php echo $key?>"><?php echo $val; ?></label>
							</td>
						</tr> 
					<?php  }
				}
				if(isset($array['desc']) && $array['desc'] != ''){ ?>
					<tr class="<?php echo $array['class']; ?>"><td colspan="2" style=""><p class="description"><?php echo (isset($array['desc']))? $array['desc']: ''?></p></td></tr>
				<?php } ?>				
	<?php } } ?>
			</tbody>
		</table>
	<?php 
	}

	/*
	* get settings tab array data
	* return array
	*/
	function get_cev_settings_data(){		
				global $wp_roles;
		$all_roles = $wp_roles->roles;
		$all_roles_array = array();
		foreach($all_roles as $key=>$role){
			if($key != 'administrator'){
				$role = array( $key => $role['name'] );
				$all_roles_array = array_merge($all_roles_array,$role);	
			}
		}	
		$page_list = wp_list_pluck( get_pages(), 'post_title', 'ID' );
	   	
		$form_data = array(
			'cev_enable_email_verification' => array(
				'type'		=> 'checkbox',
				'title'		=> __( 'Enable customer email verification', 'customer-email-verification-for-woocommerce' ),
				'show' => true,
				'class'     => '',
			),
			
			'cev_enter_account_after_registration' => array(
				'type'		=> 'checkbox',
				'title'		=> __( 'Allow first login after registration without email verification', 'customer-email-verification-for-woocommerce' ),'show' => true,
				'class'     => '',
			),	
			'cev_email_for_verification' => array(
				'type'		=> 'dropdown_checkbox',
				'title'		=> __( 'Verification Emails', 'customer-email-verification-for-woocommerce' ),				
				'show'		=> true,
				'class'		=> 'email_verification',		
				'options'   =>  array(
					'1' => ' Separate Verification Email',
					'2' => 'Verification Code in New Account Email ',
				),
			),
			'cev_redirect_page_after_varification' => array(
				'type'		=> 'dropdown',
				'title'		=> __( 'Redirect after successful verification', 'customer-email-verification-for-woocommerce' ),				
				'class'		=> 'redirect_page',
				'show' => true,	
				'options'   => $page_list, 				
			),
			'cev_verification_success_message' => array(
				'type'		=> 'textarea',
				'title'		=> __( 'Verification Success Message', 'customer-email-verification-for-woocommerce' ),				
				'show'		=> true,
				'placeholder' => __( 'Your Email is verified!', 'customer-email-verification-for-woocommerce' ),	
				'desc_tip'      => '',
				'class'     => '',
			),	
			'cev_skip_verification_for_selected_roles' => array(
				'type'		=> 'multiple_checkbox',
				'title'		=> __( 'Skip email verification for the selected user roles', 'customer-email-verification-for-woocommerce' ),
				'options'   => $all_roles_array,				
				'show' => true,				
				'class'     => '',
			),			
		);
		$form_data = apply_filters( 'cev_general_settings_options', $form_data );
		return $form_data;
	}

	public function cev_settings_form_update_fun(){
		if ( ! empty( $_POST ) && check_admin_referer( 'cev_settings_form_nonce', 'cev_settings_form_nonce' ) ) {
			$data = $this->get_cev_settings_data();				
			
			foreach( $data as $key => $val ){				
				if(isset($_POST[ $key ])){						
					update_option( $key, $_POST[ $key ] );
				}
				if(isset($val['type']) && $val['type']=='dropdown_checkbox' ){
					if(isset($val['dropdown_checkbox_options'])){
						foreach((array)$val['dropdown_checkbox_options'] as $key1 => $val1){
							if(isset($_POST[ $key1 ])){						
								update_option( $key1, wc_clean($_POST[ $key1 ]) );
							}
						}					
					}
				}
			}
		}
	}
	
	/**
	 * This function adds custom columns in user listing screen in wp-admin area.
	 */
	public function add_column_users_list( $column ){
		$column['cev_verified'] = __( 'Email Verification', 'customer-email-verification-for-woocommerce' );
		$column['cev_action'] = __( 'Actions', 'customer-email-verification-for-woocommerce' );
		return $column;
	}
	
	/**
	 * This function adds custom values to custom columns in user listing screen in wp-admin area.
	 */	
	public function add_details_in_custom_users_list( $val, $column_name, $user_id ){
		
		$user_role = get_userdata( $user_id );
		$verified  = get_user_meta( $user_id, 'customer_email_verified', true );
		$admin_approval = get_user_meta( $user_id, 'customer_admin_approval_verified', true );
		$cev_skip_verification_for_selected_roles = get_option('cev_skip_verification_for_selected_roles');
		if ( 'cev_verified' === $column_name ) {
			if(isset($user_role->roles[0])){
				if ( 'administrator' !== $user_role->roles[0]) {
					if(isset($cev_skip_verification_for_selected_roles[$user_role->roles[0]]) && $cev_skip_verification_for_selected_roles[$user_role->roles[0]] == 0){
						if ( 'true' === $verified ) {
							$text = __( 'Unverify', 'customer-email-verification-for-woocommerce' );
							return '<span class="dashicons dashicons-yes cev_5" title="Verified"></span>'; 
							
						} else{
							$text = __( 'Verify', 'customer-email-verification-for-woocommerce' );
							$text2 = __( 'Resend Email', 'customer-email-verification-for-woocommerce' );
							return '<span class="dashicons dashicons-no no-border cev_5" title="Unverified"></span>';
							}
					}
				} else {
					return $user_role->roles[0];
				}
			}
		}
		if ( 'cev_action' === $column_name ) {
			if(isset($user_role->roles[0])){
				if ( 'administrator' !== $user_role->roles[0]) {
					if(isset($cev_skip_verification_for_selected_roles[$user_role->roles[0]]) && $cev_skip_verification_for_selected_roles[$user_role->roles[0]] == 0){
						if ( 'true' === $verified ) {
							$text = __( 'Unverify', 'customer-email-verification-for-woocommerce' );
							return '<a class="cev_10" href=' . add_query_arg( array(
								'user_id'    => $user_id,
								'wp_nonce'   => wp_create_nonce( 'wc_cev_email' ),
								'wc_cev_confirm' => 'false',
							), get_admin_url() . 'users.php' ) . '><span class="dashicons dashicons-no"></span></a>';
						} else{
							$text = __( 'Verify', 'customer-email-verification-for-woocommerce' );
							$text2 = __( 'Resend Email', 'customer-email-verification-for-woocommerce' );
							return '<a class="cev_10" href=' . 
							add_query_arg( array(
									'user_id'    => $user_id,
									'wp_nonce'   => wp_create_nonce( 'wc_cev_email' ),
									'wc_cev_confirm' => 'true',
								), get_admin_url() . 'users.php' ) . '><span class="dashicons dashicons-yes small-yes"></span></a><a class="" href=' . add_query_arg( array(
							'user_id'         => $user_id,
							'wp_nonce'        => wp_create_nonce( 'wc_cev_email_confirmation' ),
							'wc_cev_confirmation' => 'true',
						), get_admin_url() . 'users.php' ) . '><span class="dashicons dashicons-redo"></span></span></a>';
						}
					}
				} else {
					return $user_role->roles[0];
				}
			}
		}		
		return $val;
	}
	
	/**
	 * This function manually verifies a user from wp-admin area.
	 */
	public function cev_manual_verify_user() {
		
		if ( isset( $_GET['user_id'] ) && isset( $_GET['wp_nonce'] ) && wp_verify_nonce( $_GET['wp_nonce'], 'wc_cev_email' ) ) { 
			if ( isset( $_GET['wc_cev_confirm'] ) && 'true' === $_GET['wc_cev_confirm'] ) { 
				update_user_meta( $_GET['user_id'], 'customer_email_verified', 'true' );
				add_action( 'admin_notices', array( $this, 'manual_cev_verify_email_success_admin' ) );
			} else {
				delete_user_meta( $_GET['user_id'], 'customer_email_verified' ); 
				add_action( 'admin_notices', array( $this, 'manual_cev_verify_email_unverify_admin' ) );				
			}			
		}
		
		if ( isset( $_GET['user_id'] ) && isset( $_GET['wp_nonce'] ) && wp_verify_nonce( $_GET['wp_nonce'], 'wc_cev_admin_approve' ) ) { 
			if ( isset( $_GET['wc_cev_admin_approve'] ) && 'true' === $_GET['wc_cev_admin_approve'] ) { 
					update_user_meta( $_GET['user_id'], 'customer_admin_approval_verified', 'true' );
					add_action( 'admin_notices', array( $this, 'manual_cev_admin_approv' ) );
				} else {
					delete_user_meta( $_GET['user_id'], 'customer_admin_approval_verified' ); 
					add_action( 'admin_notices', array( $this, 'manual_cev_verify_admin_approv_admin' ) );
				}
		}
		
		if ( isset( $_GET['user_id'] ) && isset( $_GET['wp_nonce'] ) && wp_verify_nonce( $_GET['wp_nonce'], 'wc_cev_email_confirmation' ) ) {			
			$current_user           = get_user_by( 'id', $_GET['user_id'] );
			$is_secret_code_present = get_user_meta( $_GET['user_id'], 'customer_email_verification_code', true );

			if ( '' === $is_secret_code_present ) {
				$secret_code = md5( $_GET['user_id'] . time() );
				update_user_meta( $_GET['user_id'], 'customer_email_verification_code', $secret_code );
			}					
			
			WC_customer_email_verification_email_Common::$wuev_user_id = $_GET['user_id']; // WPCS: input var ok, CSRF ok.
			WC_customer_email_verification_email_Common::$wuev_myaccount_page_id = $this->my_account_id;
			
			WC_customer_email_verification_email_Common::code_mail_sender( $current_user->user_email );
			add_action( 'admin_notices', array( $this, 'manual_confirmation_email_success_admin' ) );
		}		
		?>
		<style>
			span.dashicons.dashicons-redo {
					background: #03a9f4;
                    color: #ffffff;
					border-radius: 3px;
					padding:0px;
					box-sizing:border-box;
					font-size:20px;	
             }	
			.cev_action .dashicons {
				font-size:25px;
				width: 25px;
   				height: 25px;
			}
			span.dashicons.dashicons-no.no-border {
   				 color: #f44336;
				 background:none;
				 font-size:25px;
				 text-decoration: none;
			}
			span.dashicons.dashicons-no {
   				background: #616161;
   				color: #ffffff;
   				border-radius: 3px;
			}
			span.dashicons.dashicons-yes.cev_5 {
			color:#8bc34a;
			background:none;
			font-size:25px;
			text-decoration: none;
			}
			span.dashicons.dashicons-yes.small-yes {
   			background: #8bc34a;
   			color: #ffffff;
			border-radius: 3px;
			}
			.cev_10{
			margin-right:10px;
			}
			.cev_5{
			margin-right:5px;
			}	
			h4.cev_admin_user {
   			width: max-content;
    		font-weight: bold;
    		font-size: 16px;
			margin-bottom: -2px;
			margin-top: 0px;
			}
			a.button-primary.cev-admin-resend-button {
    		background: #03a9f4;
    		border-color: #03a9f4;
			font-size: 15px;
			margin-right:10px;
			}
            a.button-primary.cev-admin-resend-button:focus,a.button-primary.cev-admin-resend-button:hover,a.button-primary.cev-admin-resend-button:active{
    		background: #03a9f4;
    		border-color: #03a9f4;
			box-shadow:none;
			}
			a.button-primary.cev-admin-unverify-button{
    		background: #616161;
   		    border: #616161;
    		font-size: 15px;
			margin-right:10px;
            }
			a.button-primary.cev-admin-unverify-button:focus,a.button-primary.cev-admin-unverify-button:hover,a.button-primary.cev-admin-unverify-button:active{
    		background: #616161;
    		border-color: #616161;
			box-shadow:none;
			}
			a.button-primary.cev-admin-verify-button {
    		background: #8bc34a;
    		border-color: #8bc34a;
    		font-size: 15px;
			margin-right:10px;
			}
			a.button-primary.cev-admin-verify-button:focus,a.button-primary.cev-admin-verify-button:hover,a.button-primary.cev-admin-verify-button:active{
    		background: #8bc34a;
    		border-color: #8bc34a;
			box-shadow:none;
			}
			table.form-table.cev-admin-menu{
    		 width: 800px;
   			 border: solid 1px #e0e0e0;
   			 background: #ffffff;
   			}
			.cev-admin-menu tr {
    		border-bottom: 1px solid #e0e0e0;
			}
			.cev-admin-menu th {
    		padding: 20px 10px 20px 10px;
			}
			.cev-admin-dashicons{
			vertical-align: text-bottom;
			}
		</style>	
        
		<?php
	}
	
	public function manual_confirmation_email_success_admin() {
		$text = __( 'Verification Email Successfully Sent.', 'customer-email-verification-for-woocommerce' );
		?>
        <div class="updated notice">
            <p><?php echo $text; ?></p>
        </div>
		<?php
	}
	
	public function manual_cev_verify_email_success_admin() {
		$text = __( 'User Verified Successfully.', 'customer-email-verification-for-woocommerce' );
		?>
        <div class="updated notice">
            <p><?php echo $text; ?></p>
        </div>
		<?php
	}
	
	public function manual_cev_admin_approv() {
		$text = __( 'User approv Successfully.', 'customer-email-verification-for-woocommerce' );
		?>
        <div class="updated notice">
            <p><?php echo $text; ?></p>
        </div>
		<?php
	}

	public function manual_cev_verify_email_unverify_admin() {
		$text = __( 'User Unverified.', 'customer-email-verification-for-woocommerce' );
		?>
        <div class="updated notice">
            <p><?php echo $text; ?></p>
        </div>
		<?php
	}
	
	public function manual_cev_verify_admin_approv_admin() {
		$text = __( 'User Unapprov.', 'customer-email-verification-for-woocommerce' );
		?>
        <div class="updated notice">
            <p><?php echo $text; ?></p>
        </div>
		<?php
	}
	
	// define the woocommerce_login_form_end callback 
	public function action_woocommerce_login_form_end() { ?>
		<p class="woocommerce-LostPassword lost_password">
			<a href="<?php echo get_home_url(); ?>?p=reset-verification-email"><?php esc_html_e( 'Resend Verification Email', 'customer-email-verification-for-woocommerce' ); ?></a>
		</p>
	<?php } 

	public function show_cev_fields_in_single_user( $user ){ 
		$user_id = $user->ID; 
		$verified  = get_user_meta( $user_id, 'customer_email_verified', true );
		$admin_approval = get_user_meta( $user_id, 'customer_admin_approval_verified', true );
		$user_role = get_userdata( $user_id );
		$cev_skip_verification_for_selected_roles = get_option('cev_skip_verification_for_selected_roles');
		?>
        
		<table class="form-table cev-admin-menu">
        <th colspan="2" ><h4 class="cev_admin_user"><?php esc_html_e( 'Customer Verification
',   'customer-email-verification-for-woocommerce' ); ?></h4></th>
			<tr>
				<th class="cev-admin-padding"><label for="year_of_birth"><?php esc_html_e( 'Email Verification Status:', 'customer-email-verification-for-woocommerce' ); ?></label></th>
				<td><?php 
				if ( 'administrator' !== $user_role->roles[0] && $cev_skip_verification_for_selected_roles[$user_role->roles[0]] == 0) {
					if ( 'true' === $verified ) {
						echo __( '<span class="dashicons dashicons-yes cev_5" title="Verified"></span>', 'customer-email-verification-for-woocommerce' );
					} else {
						echo __( '<span class="dashicons dashicons-no no-border" title="Unverified"></span> ', 'customer-email-verification-for-woocommerce' );
					}
				} else {
					echo 'Admin';
				} ?></td>
			</tr>
            <!--tr>
				<th><label for="year_of_birth"><?php esc_html_e( 'Admin Approval Status:', 'customer-email-verification-for-woocommerce' ); ?></label></th>
				<td><?php 
				if ( 'administrator' !== $user_role->roles[0] && $cev_skip_verification_for_selected_roles[$user_role->roles[0]] == 0) {
					remove_query_arg( array( 'user_id', 'wc_cev_admin_approve', 'wp_nonce', 'wc_cev_confirmation', 'send_verification_emails', 'verify_users_email' ));
					if ( 'true' == $admin_approval ) {
						$text = __( '<span class="dashicons dashicons-yes cev_5" title="Approv">', 'customer-email-verification-for-woocommerce' );
	
						echo '<a  href=' . add_query_arg( array(
								'user_id'    => $user_id,
								'wp_nonce'   => wp_create_nonce( 'wc_cev_admin_approve' ),
								'wc_cev_admin_approve' => 'true',
							), get_admin_url() . 'users.php' ) . '>' . $text . '</a>';
					} else {
						$text = __( '<span class="dashicons dashicons-no no-border" title="unapprove">', 'customer-email-verification-for-woocommerce' );
	
						echo '<a  href=' . add_query_arg( array(
								'user_id'    => $user_id,
								'wp_nonce'   => wp_create_nonce( 'wc_cev_admin_approve' ),
								'wc_cev_admin_approve' => 'false',
							), get_admin_url() . 'users.php' ) . '>' . $text . '</a>';
					}
				} ?></td>
			</tr-->
			<tr>
				<td colspan="2"><?php 
					
					if ( 'administrator' != $user_role->roles[0] && $cev_skip_verification_for_selected_roles[$user_role->roles[0]] == 0) {
						remove_query_arg( array( 'user_id', 'wc_cev_confirm', 'wp_nonce', 'wc_cev_confirmation', 'send_verification_emails', 'verify_users_email' ));
						$text = __( '<span class="dashicons dashicons-redo cev-admin-dashicons"></span> Resend Verification Email', 'customer-email-verification-for-woocommerce' );
	
						if ( 'true' === $verified ) {
							echo '';
						}
		
						echo '<a class="button-primary cev-admin-resend-button" href=' . add_query_arg( array(
								'user_id'         => $user_id,
								'wp_nonce'        => wp_create_nonce( 'wc_cev_email_confirmation' ),
								'wc_cev_confirmation' => 'true',
							), get_admin_url() . 'users.php' ) . '>' . $text . '</a>';
					} ?>
                    <?php 
				if ( 'administrator' !== $user_role->roles[0] && $cev_skip_verification_for_selected_roles[$user_role->roles[0]] == 0) {
					remove_query_arg( array( 'user_id', 'wc_cev_confirm', 'wp_nonce', 'wc_cev_confirmation', 'send_verification_emails', 'verify_users_email' ));
					if ( 'true' !== $verified ) {
						$text = __( '<span class="dashicons dashicons-yes cev-admin-dashicons" title="Verified" style="color:#ffffff"></span> Verify Email Manually', 'customer-email-verification-for-woocommerce' );
	
						echo '<a class="button-primary cev-admin-verify-button " href=' . add_query_arg( array(
								'user_id'    => $user_id,
								'wp_nonce'   => wp_create_nonce( 'wc_cev_email' ),
								'wc_cev_confirm' => 'true',
							), get_admin_url() . 'users.php' ) . '>' . $text . '</a>';
					} else {
						$text = __( '<span class="dashicons dashicons-no cev-admin-dashicons"></span> Un-verify email', 'customer-email-verification-for-woocommerce' );
	
						echo '<a class="button-primary cev-admin-unverify-button" href=' . add_query_arg( array(
								'user_id'    => $user_id,
								'wp_nonce'   => wp_create_nonce( 'wc_cev_email' ),
								'wc_cev_confirm' => 'false',
							), get_admin_url() . 'users.php' ) . '>' . $text . '</a>';
					}
				} ?>
              <?php 
				/*if ( 'administrator' !== $user_role->roles[0] && $cev_skip_verification_for_selected_roles[$user_role->roles[0]] == 0) {
					remove_query_arg( array( 'user_id', 'wc_cev_admin_approve', 'wp_nonce', 'wc_cev_confirmation', 'send_verification_emails', 'verify_users_email' ));
					if ( 'true' !== $admin_approval) {
						$text = __( '<span class="dashicons dashicons-yes cev-admin-dashicons" style="color:#ffffff"></span> Approve Customer', 'customer-email-verification-for-woocommerce' );
	
						echo '<a class="button-primary cev-admin-verify-button" href=' . add_query_arg( array(
								'user_id'    => $user_id,
								'wp_nonce'   => wp_create_nonce( 'wc_cev_admin_approve' ),
								'wc_cev_admin_approve' => 'true',
							), get_admin_url() . 'users.php' ) . '>' . $text . '</a>';
					} else {
						$text = __( '<span class="dashicons dashicons-no cev-admin-dashicons"></span> Un-approve Customer', 'customer-email-verification-for-woocommerce' );
	
						echo '<a class="button-primary cev-admin-unverify-button" href=' . add_query_arg( array(
								'user_id'    => $user_id,
								'wp_nonce'   => wp_create_nonce( 'wc_cev_admin_approve' ),
								'wc_cev_admin_approve' => 'false',
							), get_admin_url() . 'users.php' ) . '>' . $text . '</a>';
					}
				}*/ ?></td>
       
			</tr>
		</table>
       
	<?php }

	public function filter_user_by_verified( $which ){
		
		$true_selected = '';
		$false_selected = '';
		// figure out which button was clicked. The $which in filter_by_job_role()
		if(isset($_GET['customer_email_verified_top'])){
			$top = $_GET['customer_email_verified_top'] ? $_GET['customer_email_verified_top'] : null;
		}
		
		if(isset($_GET['customer_email_verified_bottom'])){
			$bottom = $_GET['customer_email_verified_bottom'] ? $_GET['customer_email_verified_bottom'] : null;
		}
		
		if (!empty($top) OR !empty($bottom))
		{
			$section = !empty($top) ? $top : $bottom;
			if($section == 'true'){
				$true_selected = 'selected';	
			}
			if($section == 'false'){
				$false_selected = 'selected';	
			}
		}
		
		// template for filtering
		$st = '<select name="customer_email_verified_%s" style="float:none;margin-left:10px;">
			<option value="">%s</option>%s</select>';
		
		
		// generate options
		$options = '<option value="true" '.$true_selected.'>'.__( 'Verified', 'customer-email-verification-for-woocommerce' ).'</option>
			<option value="false" '.$false_selected.'>'.__( 'Non Verified', 'customer-email-verification-for-woocommerce' ).'</option>';
		
		// combine template and options
		$select = sprintf( $st, $which, __( 'User Verification', 'customer-email-verification-for-woocommerce' ), $options );
		
		// output <select> and submit button
		echo $select;
		submit_button(__( 'Filter' ), null, $which, false);	
	}
	
	public function filter_users_by_user_by_verified_section( $query ){
		global $pagenow;
		if (is_admin() && 'users.php' == $pagenow) {
			
			// figure out which button was clicked. The $which in filter_by_job_role()
			if(isset($_GET['customer_email_verified_top'])){
				$top = $_GET['customer_email_verified_top'] ? $_GET['customer_email_verified_top'] : null;
			}
			
			if(isset($_GET['customer_email_verified_bottom'])){
				$bottom = $_GET['customer_email_verified_bottom'] ? $_GET['customer_email_verified_bottom'] : null;
			}
			
			if (!empty($top) OR !empty($bottom))
			{
				$section = !empty($top) ? $top : $bottom;
				if($section == 'true'){
					// change the meta query based on which option was chosen
					$meta_query = array (array (
						'key' => 'customer_email_verified',
						'value' => $section,
						'compare' => 'LIKE'
					));
				} else{
					$meta_query = array (
						'relation' => 'AND',
						array (
							'key' => 'cev_email_verification_pin',							
							'compare' => 'EXISTS'
						),
						array (
							'key' => 'customer_email_verified',
							'value' => $section,
							'compare' => 'NOT EXISTS'
						),	
					);
				}
				$query->set('meta_query', $meta_query);				
			}
		}	
	}
 
	function add_custom_bulk_actions_for_user( $bulk_array ) {
	 
		$bulk_array['verify_users_email'] = 'Verify users email';
		$bulk_array['send_verification_email'] = 'Send verification email';
		return $bulk_array;
	 
	}

	function users_bulk_action_handler( $redirect, $doaction, $object_ids ) {
	 
		$redirect = remove_query_arg( array( 'user_id', 'wc_cev_confirm', 'wp_nonce', 'wc_cev_confirmation', 'verify_users_emails', 'send_verification_emails' ), $redirect );

		if ( $doaction == 'verify_users_email' ) {
	 
			foreach ( $object_ids as $user_id ) {
				update_user_meta( $user_id, 'customer_email_verified', 'true' );
			}
	 
			$redirect = add_query_arg( 'verify_users_emails', count( $object_ids ), $redirect );
	 
		}
	 
		if ( $doaction == 'send_verification_email' ) {
			foreach ( $object_ids as $user_id ) {
				$current_user = get_user_by( 'id', $user_id );
				$this->user_id                         = $current_user->ID;
				$this->email_id                        = $current_user->user_email;
				$this->user_login                      = $current_user->user_login;
				$this->user_email                      = $current_user->user_email;
				WC_customer_email_verification_email_Common::$wuev_user_id  = $current_user->ID;
				WC_customer_email_verification_email_Common::$wuev_myaccount_page_id = $this->my_account_id;
				$this->is_user_created                 = true;		
				$is_secret_code_present                = get_user_meta( $this->user_id, 'customer_email_verification_code', true );
		
				if ( '' === $is_secret_code_present ) {
					$secret_code = md5( $this->user_id . time() );
					update_user_meta( $user_id, 'customer_email_verification_code', $secret_code );
				}
				$cev_email_for_verification = get_option('cev_email_for_verification',1);
				$verified = get_user_meta( $this->user_id, 'customer_email_verified', true );
				$cev_email_for_verification_mode = get_option('cev_email_for_verification_mode',1);
				$admin_approval = get_user_meta( $this->user_id, 'customer_admin_approval_verified', true );
				if($cev_email_for_verification == 1 && $verified != 'true' ){
					WC_customer_email_verification_email_Common::code_mail_sender( $current_user->user_email );
				}	
			}
			$redirect = add_query_arg( 'send_verification_emails', count( $object_ids ), $redirect );
		}
	 
		return $redirect;
	 
	}
 
	function user_bulk_action_notices() {
	 
		if ( ! empty( $_REQUEST['verify_users_emails'] ) ) {
			printf( '<div id="message" class="updated notice is-dismissible"><p>' .
				_n( 'Varification Status updated from  %s user.',
				'Varification Status updated from  %s users.',
				intval( $_REQUEST['verify_users_emails'] )
			) . '</p></div>', intval( $_REQUEST['verify_users_emails'] ) );
		}
	 
		if( ! empty( $_REQUEST['send_verification_emails'] ) ) {
	 
			printf( '<div id="message" class="updated notice is-dismissible"><p>' .
				_n( 'Sent Varification email from  %s user.',
				'Sent Varification email from  %s users.',
				intval( $_REQUEST['send_verification_emails'] )
			) . '</p></div>', intval( $_REQUEST['send_verification_emails'] ) );
	 
		}
	 
	}	
}
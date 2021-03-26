( function( $, data, wp, ajaxurl ) {
		
	var $cev_settings_form = $("#cev_settings_form");
	var $cev_frontend_messages_form = $("#cev_frontend_messages_form");	
			
	var cev_settings_init = {
		
		init: function() {									
			$cev_settings_form.on( 'click', '.cev_settings_save', this.save_wc_cev_settings_form );
			$cev_frontend_messages_form.on( 'click', '.cev_frontend_messages_save', this.save_wc_cev_frontend_messages_form );			
		},

		save_wc_cev_settings_form: function( event ) {
			
			event.preventDefault();
			
			$cev_settings_form.find(".spinner").addClass("active");
			var ajax_data = $cev_settings_form.serialize();
			
			$.post( ajaxurl, ajax_data, function(response) {
				$cev_settings_form.find(".spinner").removeClass("active");
				var snackbarContainer = document.querySelector('#cev-toast-example');
				var data = {message: 'Your Settings have been successfully saved.'};
				snackbarContainer.MaterialSnackbar.showSnackbar(data);
			});
			
		}, 
		save_wc_cev_frontend_messages_form: function( event ) {
			
			event.preventDefault();
			
			$cev_frontend_messages_form.find(".spinner").addClass("active");
			var ajax_data = $cev_frontend_messages_form.serialize();
			
			$.post( ajaxurl, ajax_data, function(response) {
				$cev_frontend_messages_form.find(".spinner").removeClass("active");
				$cev_frontend_messages_form.find('.success_msg').show();
			});
			
		}
	};
	
	$(window).load(function(e) {
        cev_settings_init.init();
    });	
	
})( jQuery, customer_email_verification_script, wp, ajaxurl );


jQuery( document ).ready(function() {
	jQuery('#cev_verification_popup_overlay_background_color').wpColorPicker({
		change: function(e, ui) {
			jQuery('.cev_verification_widget_preview').prop("disabled", true);
		},
	});	
});

jQuery(document).on("click", ".cev_tab_input", function(){
	"use strict";
	var tab = jQuery(this).data('tab');
	var label = jQuery(this).data('label');
	jQuery('.zorem-layout-cev__header-breadcrumbs .header-breadcrumbs-last-cev').text(label);
	var url = window.location.protocol + "//" + window.location.host + window.location.pathname+"?page=customer-email-verification-for-woocommerce&tab="+tab;
	window.history.pushState({path:url},'',url);	
});

jQuery(document).click(function(){
	var $trigger = jQuery(".cev_dropdown");
    if($trigger !== event.target && !$trigger.has(event.target).length){
		jQuery(".cev-dropdown-content").hide();
    }   
});

jQuery(document).on("click", ".cev-dropdown-menu", function(){	
	jQuery('.cev-dropdown-content').show();
});

jQuery(document).on("click", ".cev-dropdown-content li a", function(){
	var tab = jQuery(this).data('tab');
	var label = jQuery(this).data('label');
	var section = jQuery(this).data('section');
	jQuery('.inner_tab_section').hide();
	jQuery('.cev_nav_div').find("[data-tab='" + tab + "']").prop('checked', true); 
	jQuery('#'+section).show();
	jQuery('.zorem-layout-cev__header-breadcrumbs .header-breadcrumbs-last-cev').text(label);
	var url = window.location.protocol + "//" + window.location.host + window.location.pathname+"?page=customer-email-verification-for-woocommerce&tab="+tab;
	window.history.pushState({path:url},'',url);
	jQuery(".cev-dropdown-content").hide();
});

( function( $, data, wp, ajaxurl ) {
	"use strict";
		
	var $cev_verification_widget_settings_form = $("#cev_verification_widget_settings_form");	
			
	var cev_settings_pro_init = {
		
		init: function() {									
			$cev_verification_widget_settings_form.on( 'click', '.cev_verification_widget_settings_save', this.save_wc_cev_verification_widget_settings_form );					
		},

		save_wc_cev_verification_widget_settings_form: function( event ) {
			
			event.preventDefault();
			
			$cev_verification_widget_settings_form.find(".spinner").addClass("active");
			var ajax_data = $cev_verification_widget_settings_form.serialize();
			
			$.post( ajaxurl, ajax_data, function(response) {
					jQuery('.cev_verification_widget_preview').prop("disabled", false);

				$cev_verification_widget_settings_form.find(".spinner").removeClass("active");
				var snackbarContainer = document.querySelector('#cev-toast-example');
				var data = {message: 'Your Settings have been successfully saved.'};
				snackbarContainer.MaterialSnackbar.showSnackbar(data);				
			});
			
		}
	};		
	
	$(window).load(function(e) {
        cev_settings_pro_init.init();
    });	
})( jQuery, customer_email_verification_script, wp, ajaxurl );

jQuery(document).on("click", ".cev_verification_widget_preview", function(){
	"use strict";	
	document.getElementById('cev_preview_iframe').contentDocument.location.reload(true);
	jQuery('#cev_preview_iframe').load(function(){
		jQuery('.cev_page_preview_popup').show();	
		var iframe = document.getElementById("cev_preview_iframe");
	});	
});

jQuery(document).on("click", ".cev-popup-close", function(){	
	"use strict";
	jQuery('.cev_page_preview_popup').hide();
});

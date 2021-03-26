(function ($) {
  var wa_time_out, wa_time_in;
  ntaWA.init = function(){
    function setCookie(cname, cvalue, exdays) {
      var d = new Date();
      d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
      var expires = "expires=" + d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
      var name = cname + "=";
      var ca = document.cookie.split(";");
      for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == " ") {
          c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
        }
      }
      return "";
    }

    $("#nta-wa-gdpr").change(function () {
      if (this.checked) {
        setCookie("nta-wa-gdpr", "accept", 30);
        if (getCookie("nta-wa-gdpr") != "") {
          $(".nta-wa-gdpr").hide(500);
          $(".wa__popup_content_item").each(function () {
            $(this).removeClass("pointer-disable");
            $(".wa__popup_content_list").off("click");
          });
        }
      }
    });

    if (getCookie("nta-wa-gdpr") != "") {
      $(".wa__popup_content_list").off("click");
    } else {
      $(".wa__popup_content_list").click(function () {
        $(".nta-wa-gdpr").delay(500).css({ background: "red", color: "#fff" });
      });
    }

    // google analytics
    if (ntaWA.ga) {
      $("a.wa__stt_online").click(function () {
        var href = $(this).attr("href");
        var startPhone = href.indexOf("phone=") + 6;
        var endPhone = href.indexOf("&text=");
        if (endPhone === -1) {
          endPhone = href.length;
        }
        var phone = href.substring(startPhone, endPhone);
        var gaCategory = "NinjaTeam WhatsApp";
        var gaAction = "Phone Number: " + phone;
        var gaLabel = document.title;

        if (typeof gtag !== "undefined") {
          gtag("event", gaAction, {
            event_category: gaCategory,
            event_label: gaLabel,
          });
          return;
        }
        if (typeof ga !== "undefined" && typeof ga.getAll !== "undefined") {
          var tracker = ga.getAll();
          tracker[0].send("event", gaCategory, gaAction, gaLabel);
          return;
        }

        if (typeof __gaTracker !== "undefined") {
          __gaTracker("send", "event", gaCategory, gaAction, gaLabel);
        }
      });
    }

    function isMobileWA() {
      return /Android|webOS|iPhone|iPad|iPod|Windows Phone|IEMobile|Mobile|BlackBerry/i.test(navigator.userAgent);
    }
    var elm = jQuery('a[href*="whatsapp.com"]');
    jQuery.each(elm, function (index, value) {
      var item = jQuery(value).attr("href");
      if (item.indexOf("chat") != -1) {
        //nothing
      } else if (item.indexOf("web.whatsapp") != -1 && isMobileWA()) {
        var itemLink = item;
        var newLink = itemLink.replace("web.whatsapp", "api.whatsapp");
        jQuery(value).attr("href", newLink);
      } else if (item.indexOf("api.whatsapp") != -1 && !isMobileWA()) {
        var itemLink = item;
        var newLink = itemLink.replace("api.whatsapp", "web.whatsapp");
        jQuery(value).attr("href", newLink);
      }
    });
  }
  // $(document).on('ntawa_init', function(){
  //   ntaWA.init()
  // })
  $(document).ready(function () {
    ntaWA.init()

    // if (!jQuery('.wa__btn_popup').length) return false
    // $.ajax({
    //   url: ntaWA.ajaxurl,
    //   type: 'POST',
    //   data: {
    //     'action': 'njt_wa_load_popup',
    //     'nonce': ntaWA.nonce
    //   },
    //   success(res){
    //     // $(res).insertAfter('.wa__btn_popup').trigger('ntawa_init')
    //   }
    // })

    $(".wa__btn_popup").on("click", function () {
      if ($(".wa__popup_chat_box").hasClass("wa__active")) {
        $(".wa__popup_chat_box").removeClass("wa__active");
        $(".wa__btn_popup").removeClass("wa__active");
        clearTimeout(wa_time_in);
        if ($(".wa__popup_chat_box").hasClass("wa__lauch")) {
          wa_time_out = setTimeout(function () {
            $(".wa__popup_chat_box").removeClass("wa__pending");
            $(".wa__popup_chat_box").removeClass("wa__lauch");
          }, 400);
        }
      } else {
        $(".wa__popup_chat_box").addClass("wa__pending");
        $(".wa__popup_chat_box").addClass("wa__active");
        $(".wa__btn_popup").addClass("wa__active");
        clearTimeout(wa_time_out);
        if (!$(".wa__popup_chat_box").hasClass("wa__lauch")) {
          wa_time_in = setTimeout(function () {
            $(".wa__popup_chat_box").addClass("wa__lauch");
          }, 100);
        }
      }
    });
  })
})(jQuery);

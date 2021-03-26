'use strict';
jQuery(document).ready(function ($) {

    $('.wvr-select-purchased-icon').on('click', function () {
        $('.wvr-select-purchased-icon').removeClass('checked');
        ($(this).addClass('checked'));
    });

    $('.wvr-color-picker').wpColorPicker();

    $('.wvr-select2-product').select2({
        ajax: {
            url: wvrObject.ajax_url + "?action=search_product",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            timeout: 4000,
            data: function (params) {
                return {
                    keyword: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        minimumInputLength: 2
    });

    $('.menu .item').tab();

    $('.submit-add-reviews').click(function (e) {
        if (confirm('OK?')) {
            return true;
        } else {
            e.preventDefault();
        }
    });

    auto_review_setting($('.wvr-cb-auto-review'));

    $('.wvr-cb-auto-review').on('change', function () {
        auto_review_setting($(this));
    });

    function auto_review_setting(el) {
        if (el.is(':checked')) {
            $('.wvr-first-comment').css('color', 'inherit');
        } else {
            $('.wvr-first-comment').css('color', '#ddd');
        }
    }

    //generate reviews for all products
    var product_ids, product_no_cmt_ids, processing = 0;

    $('.wvr-add-reviews-all-product').on('click', function () {
        $.ajax({
            url: wvrObject.ajax_url,
            type: 'POST',
            data: {action: 'count_product'},
            success: function (data) {
                // console.log(data);
                product_ids = data[0];
                product_no_cmt_ids = data[1];
                $('.wvr-all-product-for-reviews').html(data[0].length);
                $('.wvr-product-no-vr-for-reviews').html(data[1].length);
                $('.wvr-processing-block').show();

                // let pExistCmt = data[2];
                // deleteComment(0, pExistCmt);
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    // function deleteComment(index, pExistCmt) {
    //     if (pExistCmt[index + 1] !== undefined) {
    //         $.ajax({
    //             url: wvrObject.ajax_url,
    //             type: 'POST',
    //             data: {action: 'delete_cmt', id: pExistCmt[index]},
    //             success: function (data) {
    //                 console.log(data);
    //                 // console.log(pExistCmt[index]);
    //                 deleteComment(index + 1, pExistCmt);
    //             },
    //             error: function (data) {
    //                 console.log(data);
    //             }
    //         });
    //     }
    // }

    $('.wvr-generate-for-all-products').on('click', function () {
        let qty = $('.wvr-qty-cmt-all-products').val();
        beforeSendAjax(product_ids, qty);
        // console.log(product_ids);
    });

    $('.wvr-generate-for-no-cmt-products').on('click', function () {
        let qty = $('.wvr-qty-no-cmt-products').val();
        beforeSendAjax(product_no_cmt_ids, qty);
    });

    function beforeSendAjax(obj, qty) {
        processing = 0;
        $('.wvr-processing-bar-group').show();
        $('.wvr-processing-bar-inside').css('width', '0');
        if (qty && obj) {
            generateReviews(0, obj, qty);
        }
    }

    function generateReviews(index, obj, qty) {
        // console.log(processing);
        $('.wvr-notice-completed').remove();
        $.ajax({
            url: wvrObject.ajax_url,
            type: 'POST',
            data: {action: 'add_reviews_all', id: obj[index], qty: qty},
            success: function (data) {
                // console.log(data);
                if (data[0]) {
                    $('.wvr-generate-processing-result').first().before(`<tr class="wvr-generate-processing-result"><td><a href="${data[2]}" target="_blank">${data[1]}</a></td><td class="wvr-right">Done</td></tr>`);
                } else {
                    $('.wvr-generate-processing-result').first().before(`<tr class="wvr-generate-processing-result"><td><a href="${data[2]}" target="_blank">${data[1]}</a></td><td class="wvr-right">Failed<span class="wvr-explain">Variable product have no variation</span></td></tr>`);
                }

                processing++;
                let ratio = (processing / obj.length) * 100;

                $('.wvr-processing-bar-inside').css('width', ratio + '%');
                if (parseInt(ratio) === 100) {
                    $('.wvr-processing-bar-outside').after('<div class="wvr-notice-completed" style="text-align: right; padding-right: 3px">Completed!</div>');
                }

                if (index + 1 < obj.length) {
                    generateReviews(index + 1, obj, qty);
                }
            }
        });
    }

});
// jQuery(document).ready(function ($) {
//     $('.wvr-show-sample-cmt').change(function () {
//         var content = $(this).attr('value');
//         var textarea_content = $('textarea.wvr-comment-content').val();
//         $('textarea.wvr-comment-content').val(textarea_content + content + ". ");
//     });
//
//     $('.wvr-rating').click(function () {
//         var star = $(this).attr('value');
//         $('.wvr-save-rating').val(star);
//     });
//
//     $('.wvr-add-single-review').click(function (e) {
//         var wvr_content = $('.wvr-comment-content').val();
//         var wvr_name = $('.wvr-select-name').val();
//         var wvr_rating = $('.wvr-save-rating').val();
//         var id = $('#post_ID').val();
//
//         if (wvr_content && wvr_name && wvr_rating && id) {
//             $.post({
//                 url: ajax_url + "?action=wvr_action&param=insert_comment",
//                 data: {
//                     id: id,
//                     content: wvr_content,
//                     name: wvr_name,
//                     rating: wvr_rating
//                 },
//                 success: function (data) {
//                     location.reload();
//                 }
//             });
//         } else {
//             e.preventDefault();
//         }
//     });
//
//
// });

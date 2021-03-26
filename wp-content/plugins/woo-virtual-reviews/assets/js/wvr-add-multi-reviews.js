jQuery(document).ready(function ($) {
    $('.submit-add-reviews').on('click', function (e) {
        var check = $("input[type='checkbox'][name='post[]']").is(':checked');
        if (!check) {
            alert("You have to select at least one product");
            e.preventDefault();
        }
    });
});
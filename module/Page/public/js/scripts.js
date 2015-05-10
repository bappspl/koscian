$(function () {
    $('#k-top-search form').submit(function (e) {
        e.preventDefault();
        var searchValue = $('#k-top-search form input[name="s"]').val();
        window.location.href = '/wyszukiwanie/' + searchValue;
    });

    $('.widget_newsletter form').submit(function (e) {
        e.preventDefault();
        var email = $('.widget_newsletter form input[type="email"]').val();
        $.ajax({
            type: "POST",
            url: "/save-new-subscriber",
            dataType : 'json',
            data: {
                email: email
            },
            success: function(json)
            {
                $('.widget_newsletter form input[type="email"]').val('');
                $('#newsletterModal').modal('show');
            }
        });
    })
});
$(document).ready(function() {
    $("#clean_cart").click(function(e) {
        $.post("clean", {
                'action': 'clean'
            })
            .done(function(data) {
                location.reload();
            });
    });

    $(".remove").click(function(e) {
        $.post("clean", {
                'action': 'remove',
                'id': $(this).attr("id")
            })
            .done(function(data) {
                location.reload();
            });
    });

    $(".update").click(function(e) {
        e.preventDefault();
        if ($(this).parent().parent().find('.qty').val() >= 0) {
            $.post("clean", {
                    'action': 'update',
                    'id': $(this).attr("id"),
                    'qty': $(this).parent().parent().find('.qty').val()
                })
                .done(function(data) {
                    location.reload();
                });
        } else {
            alert("Quantity can't be negative.");
        }
    });
    $(window).resize(function() {
        $(".container").first().css("padding-top", $("nav").css("height"));
    });
    $(".container").first().css("padding-top", $("nav").css("height"));
});

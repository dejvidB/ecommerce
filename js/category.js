$(document).ready(function () {
    $(".container").css("padding-top", $("nav").css("height"));
    $("body").on('click', '.product', function (e) {
        window.location.href = "product/" + $(this).attr("id");
    });
    $(window).resize(function () {
        $(".container").css("padding-top", $(".navbar-fixed-top").css("height"));
    });
});

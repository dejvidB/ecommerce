document.querySelectorAll('.items').forEach(function (slider) {
    let isDown = false;
    let startX;
    let scrollLeft;

    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        slider.classList.add('active');
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });
    slider.addEventListener('mouseleave', () => {
        isDown = false;
        slider.classList.remove('active');
    });
    slider.addEventListener('mouseup', () => {
        isDown = false;
        slider.classList.remove('active');
    });
    slider.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 2; //scroll-fast
        slider.scrollLeft = scrollLeft - walk;
        console.log(walk);
    });
});

function isScrolledIntoView(elem) {
    var nav_height = 0;
    if ($("nav").is(":visible")) {
        nav_height = $("nav").height();
    }
    var docViewTop = $(window).scrollTop() + nav_height;
    var docViewBottom = docViewTop + $(window).height();
    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();
    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

$(document).ready(function () {
    if (window.matchMedia('(max-width:768px)').matches) {
        $("#add_to_cart_fixed_btn").html("<span class='fa fa-cart-plus'></span>");
        $("#add_to_cart_fixed_btn").css("border-radius", "15px");
    } else {
        $("#add_to_cart_fixed_btn").html("Add to cart");
    }
    $("#add_to_cart_fixed_btn").height($("#myBtn").height());
    scrollFunction2();
    // When the user scrolls down from the add_to_cart button, show the add_to_cart_fixed_btn
    window.addEventListener('scroll', scrollFunction2);

    function scrollFunction2() {
        if (!isScrolledIntoView($("#add_to_cart"))) {
            document.getElementById("add_to_cart_fixed_btn").style.display = "block";
        } else {
            document.getElementById("add_to_cart_fixed_btn").style.display = "none";
        }
    }

    $("#add_to_cart_fixed_btn").click(function () {
        $("#add_to_cart").click();
    });
    $("body").keydown(function (e) {
        if (e.keyCode == 27) {
            $("#myModal").modal("hide");
        }
    });
    var page = 0;


    load_reviews();
    $("body").on('click', '#load_more', function (e) {
        e.preventDefault();
        load_reviews();
    });

    $("body").on('click', '.read_more', function (e) {
        e.preventDefault();
        var dots = $(this).parent().find('.dots');
        var moreText = $(this).parent().find('.more');
        var btnText = $(this);

        if (dots.css("display") === "none") {
            dots.css("display", "inline");
            btnText.html("Read more");
            moreText.css("display", "none");
        } else {
            dots.css("display", "none");
            btnText.html("Read less");
            moreText.css("display", "inline");
        }
    });
    var ratingValue = 0;
    var msg = "";

    $("body").on('mouseover', '#stars li', function () {
        var onStar = parseInt($(this).data('value'), 10);
        $(this).parent().children('li.star').each(function (e) {
            if (e < onStar) {
                $(this).addClass('hover');
            } else {
                $(this).removeClass('hover');
            }
        });

    });
    $("body").on('mouseout', '#stars li', function () {
        $(this).parent().children('li.star').each(function (e) {
            $(this).removeClass('hover');
        });
    });


    $("body").on('click', '#stars li', function () {
        var onStar = parseInt($(this).data('value'), 10);
        var stars = $(this).parent().children('li.star');
        for (i = 0; i < stars.length; i++) {
            $(stars[i]).removeClass('selected');
        }

        for (i = 0; i < onStar; i++) {
            $(stars[i]).addClass('selected');
        }

        ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
    });

    function change_msg(ratingValue) {
        if (ratingValue > 2) {
            msg = '<i class="fa fa-smile-o" aria-hidden="true"></i> Thanks! You rated this ' + ratingValue + " stars.";
        } else {
            msg = "<i class='fa fa-frown-o' aria-hidden='true'></i> We will improve ourselves. You rated this " + ratingValue + " stars.";
        }
    };

    $(".unzoomed").click(function (e) {
        window.open($(this).attr("src"));
    });

    $(".container").css("padding-top", $(".navbar-fixed-top").css("height"));
    if (window.matchMedia('(min-width:768px)').matches) {
        $(".third .form-group").addClass("affix");
        $(".first .form-group").addClass("affix");
    } else {
        var reviews = $('#reviews');
        var write = $('#write');
        $('#reviews').remove();
        $('#write').remove();
        $(".second").append(reviews);
        $(".second").append(write);
        $("#price_form_group").insertAfter("#product_prc");
        $("#rating").insertAfter("#price_form_group");
    }

    $(window).resize(function () {
        $(".container").css("padding-top", $(".navbar-fixed-top").css("height"));
        if (window.matchMedia('(min-width:768px)').matches) {
            $(".third .form-group").addClass("affix");
        } else {
            $(".third .form-group").removeClass("affix");
        }
    });

    $(".photos").click(function (e) {
        $("#main").attr("src", $(this).attr("src"));
    });

    $("#main").click(function (e) {
        $(this).toggleClass("zoom");
        $(this).toggleClass("unzoomed");
    });

});

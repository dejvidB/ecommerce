// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    $("html, body").animate({
        scrollTop: 0
    }, 'slow');
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
if (getCookie("dparts") === "open" || getCookie("dparts") == "") {
    $("#dparts").show();
    $("#showdparts").html('Departments <span class="dropup"><span class="caret"></span></span>');
} else {
    $("#dparts").hide();
    $("#showdparts").html('Departments <span class="caret"></span>');
}
if (getCookie("search-mob") === "open" || getCookie("search-mob") == "") {
    $("#search-mob").html('<span class="glyphicon glyphicon-search"><span class="dropup"><span class="caret"></span></span>');
} else {
    $(".navbar-right").hide();
    $("#search-mob").html('<span class="glyphicon glyphicon-search"></span> <span class="caret"></span>');
    $(".navbar-right").addClass("closed");
}

$(document).ready(function () {
    var selected_slider;
    $('body').keydown(function (e) {
        if (e.keyCode == 37) {
            e.preventDefault();
            var leftPos = selected_slider.scrollLeft();
            selected_slider.animate({
                scrollLeft: leftPos - 200
            }, 300);
        }
        if (e.keyCode == 39) {
            e.preventDefault();
            var leftPos = selected_slider.scrollLeft();
            selected_slider.animate({
                scrollLeft: leftPos + 200
            }, 300);
        }
    });
    $(".btn-left").click(function () {
        var slider = $(this).parent().children().find(".items");
        selected_slider = slider;
        slider.stop();
        var leftPos = slider.scrollLeft();
        slider.animate({
            scrollLeft: leftPos - 200
        }, 300);
    });
    $(".btn-right").click(function () {
        var slider = $(this).parent().children().find(".items");
        selected_slider = slider;
        slider.stop();
        var leftPos = slider.scrollLeft();
        slider.animate({
            scrollLeft: leftPos + 200
        }, 300);
    });
    var lastScrollTop = $(window).scrollTop();
    $(window).scroll(function (e) {
        if (window.matchMedia("(max-width:768px)").matches) {
            var st = $(this).scrollTop();
            if (st > lastScrollTop) {
                $("nav").hide("slow");
                //$("nav").removeClass("navbar-fixed-top");
            } else {
                $("nav").show("slow");
                //$("nav").addClass("navbar-fixed-top");
            }
            lastScrollTop = st;
            //$(".container").css("padding-top", $("nav").css("height"));
        }
    });
    scrollFunction();
    $.post("footer", {})
        .done(function (data) {
            $("body").append(data);
        });
    $(".sign_out").click(function (e) {
        e.preventDefault();
        $.post("auth", {
            "action": "logout"
        })
            .done(function (data) {
                location.reload(true);
            });
    });

    $("#showdparts").click(function (e) {
        $(this).toggleClass("closed");
        if (!$(this).hasClass("closed")) {
            $("#dparts").show();
            $(this).html('Departments <span class="dropup"><span class="caret"></span></span>');
            document.cookie = "dparts=open;path=/";
        } else {
            $("#dparts").hide();
            $(this).html('Departments <span class="caret"></span>');
            document.cookie = "dparts=closed;path=/";
        }
        $(".container").css("padding-top", $("nav").css("height"));
    });
    $("#search-mob").click(function (e) {
        e.preventDefault();
        $(".navbar-right").toggleClass("closed");
        if (!$(".navbar-right").hasClass("closed")) {
            $(".navbar-right").show();
            $("#results").nextAll('div').hide();
            $(this).html('<span class="glyphicon glyphicon-search"><span class="dropup"><span class="caret"></span></span>');
            document.cookie = "search-mob=open;path=/";
        } else {
            $(".navbar-right").hide();
            $(this).html('<span class="glyphicon glyphicon-search"></span> <span class="caret"></span>');
            document.cookie = "search-mob=closed;path=/";
        }
        $(".container").css("padding-top", $("nav").css("height"));
    });
    $(".list-group-item").click(function () {
        $(this).addClass("active");
        $(this).children("a")[0].click();
    });
    // When the user scrolls down 20px from the top of the document, show the button
    //window.onscroll = function() {scrollFunction()};
    //$(window).scroll(scrollFunction());
    window.addEventListener('scroll', scrollFunction);

    function scrollFunction() {
        if (document.body.scrollTop > 30 || document.documentElement.scrollTop > 30) {
            document.getElementById("myBtn").style.display = "block";
        } else {
            document.getElementById("myBtn").style.display = "none";
        }
    }

    if (window.matchMedia('(min-width:768px)').matches) {
        //$("nav").addClass("navbar-fixed-top");
        $(".container").css("padding-top", $("nav").css("height"));
    }
    $("html").children().not("#results").click(function (e) {
        $("html").children().not('nav').css("background", "");
        $('#results').hide();

    });
    $("body").on('click', '.person', function (e) {
        e.stopPropagation();
        window.location.href = $(this).find("a").attr("href");
    });

    var index;
    var max;
    $('#searchbar').keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            $("#results .person").each(function () {
                if ($(this).hasClass("selected")) {
                    window.location.href = $(this).find("a").attr("href");
                }
            });
        }
        //down
        if (e.keyCode == 40 && index < max) {
            index += 1;
            //$("#searchbar").val($("#results .title:eq(" + index + ")").text().replace(/\s/g, ''));
            $("#results .person").each(function () {
                $(this).removeClass("selected");
            });
            $("#results .person:eq(" + index + ")").addClass("selected");
            //$("#results").scrollTop($("#results .selected").parent().offset().top);
            var elem = $("#results .selected");
            //$("#results").scrollTop(elem.offset().top - $("#results .table").height() + elem.outerHeight());
            var $parentDiv = $("#results");
            var $innerListItem = $("#results .selected");
            $parentDiv.scrollTop($parentDiv.scrollTop() + $innerListItem.position().top - $parentDiv.height() / 2 + $innerListItem.height() / 2);

        }
        //up
        if (e.keyCode == 38 && index >= 0 && index <= max) {
            if (index > 0) {
                index -= 1;
                //$("#searchbar").val($("#results .title:eq(" + index + ")").text().replace(/\s/g, ''));
                $("#results .person").each(function () {
                    $(this).removeClass("selected");
                });
                $("#results .person:eq(" + index + ")").addClass("selected");
                //$("#results").scrollTop($("#results .selected").offset().top);
                var elem = $("#results .selected");
                //$("#results").scrollTop(elem.offset().top - $("#results .table").height() + elem.outerHeight());
                var $parentDiv = $("#results");
                var $innerListItem = $("#results .selected");
                $parentDiv.scrollTop($parentDiv.scrollTop() + $innerListItem.position().top - $parentDiv.height() / 2 + $innerListItem.height() / 2);

            }
        }
    });


    $("body").on('DOMSubtreeModified', ".cart", function () {
        var $el = $(".cart"),
            x = 500,
            org = "white";
        $el.css("color", "yellow");
        setTimeout(function () {
            $("#snackbar").addClass("show");
            setTimeout(function () {
                $("#snackbar").removeClass("show");
            }, 3500);
            $el.css("color", org);
        }, x);
    });

    $('#searchbar').on('input', function (e) {
        var q = $("#searchbar").val();
        if (q.length > 0) {
            //$('.dropdown-menu').hide();
            $("#results").show();
            $("html").children().not('nav').css("background", "rgba(0,0,0, 0.5)");
            $.post("results", {
                "q": q
            })
                .done(function (data) {
                    $('#results').html(data);
                    index = -1;
                    max = $("div.person").length - 1;
                });
        } else {
            $('#results').hide();
            $("html").children().not('nav').css("background", "");
        }
    });

    $("body").on('click', '.product', function (e) {
        window.location.href = "product/" + $(this).attr("id");
    });
    $("body").on('click', '.add_to_cart', function (e) {
        e.stopPropagation();
        //var id = $(this).parent().parent().parent().parent().attr("id");
        var str = $(this).closest(".product").attr("id");
        var id = str.substr(str.indexOf("/") + 1, str.length);
        $.post("add", {
            "id": id,
            "quantity": 1
        })
            .done(function (data) {
                $(".cart").html('<span class="glyphicon glyphicon-shopping-cart"></span> ' + data);
            });
    });

});

var curl = window.location.href;

function getParamValue(param) {
    var ParamValue = "";
    var i = curl.indexOf(param + "=") + param.length + 1;
    while (i < curl.length && curl.charAt(i) != "&") {
        ParamValue += curl.charAt(i);
        i += 1;
    }
    return ParamValue;
}

function changeParam(param, value) {
    if (value.length > 0) {
        if (!curl.includes("?")) {
            curl += "?";
        }
        if (!curl.includes(param)) {
            if (curl.charAt(curl.length - 1) == "?") {
                var add = param + "=" + value;
            } else {
                var add = "&" + param + "=" + value;
            }
            curl += add;
        } else {
            var old_param = getParamValue(param);
            curl = curl.replace(param + "=" + old_param, param + "=" + value);
        }
    } else {
        var old_param = getParamValue(param);
        if (curl.charAt(curl.indexOf(old_param) + old_param.length) == "&") {
            curl = curl.slice(0, curl.indexOf(old_param) + old_param.length) + curl.slice(curl.indexOf(old_param) + old_param.length + 1);
        }
        curl = curl.replace(param + "=" + old_param, "");
        if (curl.charAt(curl.length - 1) == "?" || curl.charAt(curl.length - 1) == "&") {
            curl = curl.slice(0, curl.length - 1);
        }
    }
    return curl;
}
$(document).ready(function() {
    $("#price_chip .closebtn").click(function() {
        $(this).parent().hide();
        changeParam("min", "");
        changeParam("max", "");
        window.location.href = curl;
    });
    $("#change_prc").click(function() {
        changeParam("min", parseFloat($("#price_min").val().replace(/[^\d.-]/g, '')).toString());
        changeParam("max", parseFloat($("#price_max").val().replace(/[^\d.-]/g, '')).toString());
        window.location.href = curl;
    });
    $("#closebtn").click(function(e) {
        e.preventDefault();
        $("#filters_opener").click();
    });
    var container_margin_left = $(".container").css("margin-left");
    $("#filters_opener").click(function() {
        $("#mySidenav").toggleClass("open");
        if ($("#mySidenav").hasClass("open")) {
            if (window.matchMedia('(min-width:768px)').matches) {
                $("#mySidenav").css("width", "25%");
                $(".container").css("margin-left", "25%");
                $(".container").css("max-width", "75%");
            } else {
                $("#mySidenav").css("width", "100%");
                $(".container").css("position", "fixed");
            }
        } else {
            if (window.matchMedia('(min-width:768px)').matches) {
                $("#mySidenav").css("width", "0");
                $(".container").css("margin-left", container_margin_left);
                $(".container").css("max-width", "100%");
            } else {
                $("#mySidenav").css("width", "0");
                $(".container").css("margin-left", container_margin_left);
                $(".container").css("position", "static");
            }
        }
    });
    $(".container").css("padding-top", $(".navbar-fixed-top").css("height"));
    $("#mySidenav").css("padding-top", $(".navbar-fixed-top").css("height"));
    $("#order_by_dropdown .btn").click(function(e) {
        e.preventDefault();
        changeParam("order_by", $(this).attr("id"));
        window.location.href = curl;
    });
    $("body").on('click', '.product', function(e) {
        window.location.href = "product/" + $(this).attr("id");
    });
    $(window).resize(function() {
        $(".container").css("padding-top", $(".navbar-fixed-top").css("height"));
    });
});

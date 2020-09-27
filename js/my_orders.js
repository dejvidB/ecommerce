var parse = "";
$(document).ready(function() {
    $(".container").first().css("padding-top", $("nav").css("height"));
    $.post("load_orders", {})
        .done(function(data) {
            parse = JSON.parse(data);
            for (var i = 0; i < parse.length; i++) {
                var order = parse[i];
                $("#all_orders tbody").append("<tr id=" + order['order_id'] + "><td class='text-center'>" + order['order_id'] + "</td><td class='text-center'>" + order['date'] + "</td><td class='text-center'>$" + order['total'] + "</td><td class='text-center'><a href='#' class='view_info'>View order info <i class='fa fa-angle-down'></i></a></td><td class='text-center'>" + order['state'] + "</td></tr>");
            }
        });
    $("body").on('click', '.view_info', function(e) {
        e.preventDefault();
        $(this).toggleClass("open");
        var parent = $(this).closest("tr");
        var order_id = parent.attr("id");
        if ($(this).hasClass("open")) {
            $(".view_info").not(this).removeClass("open");
            $(".view_info").not(this).html('View order info <i class="fa fa-angle-down"></i>');
            if ($("#" + order_id + "_info").length == 0) {
                $.post("load_order_info", {
                        'order_id': order_id
                    })
                    .done(function(data) {
                        $(".order_tr").hide();
                        $("#" + order_id + "_info").show();
                        $(data).insertAfter(parent);
                        var total = document.getElementById(order_id + "_total");
                        var pos = -1;
                        for (var i = 0; i < parse.length; i++) {
                            var order = parse[i];
                            var oid = order.order_id;
                            if (oid == order_id) {
                                $(total).html("$" + JSON.stringify(order.total));
                            }
                        }
                    });
            } else {
                $(".order_tr").hide();
                $("#" + order_id + "_info").show();
            }
            $(this).html('Hide order info <i class="fa fa-angle-up"></i>');
        } else {
            $("#" + order_id + "_info").hide();
            $(this).html('View order info <i class="fa fa-angle-down"></i>');
        }
    });
});

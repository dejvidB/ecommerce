$(".container").css("padding-top", $(".navbar-fixed-top").css("height"));
$(document).ready(function() {
    $("#payment, #success").hide();

    $("#continue").click(function(e) {
        if (check_required_inputs() == 0) {
            $(".modal-title").html("Please make sure that the details bellow are correct and complete.");
            $(".modal-body").html('<p>E-mail : ' + $("#email").val() + '</p><p> First name : ' + $("#name").val() + '</p><p> Last name : ' + $("#lastname").val() + '</p><p> Country : ' + $("#country").val() + '</p><p> State/Province/Region : ' + $("#region").val() + '</p><p> City : ' + $("#city").val() + '</p><p>Street Address : ' + $("#street").val() + '</p><p> ZIP code : ' + $("#zip").val());
            $("#Modal").modal('show');
        }
    });
    var order_id = 0;
    $("#go").click(function(e) {
        $("#payment").show();
        $(window).scrollTop($("#payment").offset().top);
        $("#step1").find("input").prop("disabled", true);
        $("#country").prop("disabled", true);
        $("#continue").addClass("disabled");
        if (!$("#continue").hasClass("ordered")) {
            $.post("order", {
                    'action': 'order',
                    'mail': $("#email").val(),
                    'name': $("#name").val(),
                    'lastname': $("#lastname").val(),
                    'country': $("#country").val(),
                    'region': $("#region").val(),
                    'city': $("#city").val(),
                    'address': $("#street").val(),
                    'zip': $("#zip").val()
                })
                .done(function(data) {
                    alert(data);
                    $("#continue").addClass("ordered");
                    order_id = data;
                });
            $("#continue").addClass("ordered");
        } else {
            $.post("order", {
                    'action': 'update',
                    'order_id': order_id,
                    'mail': $("#email").val(),
                    'name': $("#name").val(),
                    'lastname': $("#lastname").val(),
                    'country': $("#country").val(),
                    'region': $("#region").val(),
                    'city': $("#city").val(),
                    'address': $("#street").val(),
                    'zip': $("#zip").val()
                })
                .done(function(data) {
                });
        }
    });

    function check_required_inputs() {
        var unfilled = 0;
        $('#step1 input').each(function() {
            if ($(this).val() == "") {
                unfilled += 1;
                $(window).scrollTop($(this).parent().offset().top);
                alert('Please ' + $(this).attr("placeholder").toLowerCase());
                return false;
            }
        });
        if ($("#email").val().replace(/\s/g, '') != "") {
            if ((!$("#email").val().includes("@") || !$("#email").val().includes(".")) && unfilled == 0) {
                $(window).scrollTop($("#email").parent().offset().top);
                alert("Hmm. That doesn't look like an email.");
                if (unfilled == 0) {
                    unfilled += 1;
                }
            }
        }
        if ($("#country").val() == "Choose your country") {
            if (unfilled == 0) {
                alert("Please choose your country.");
                if (unfilled == 0) {
                    unfilled += 1;
                }
            }
        }
        return unfilled;
    }

    $("#edit").click(function(e) {
        e.preventDefault();
        $("#continue").text("Update details and continue to payment.");
        $("#step1").find("input").prop("disabled", false);
        $("#country").prop("disabled", false);
        $("#continue").removeClass("disabled");
        $("#payment").fadeOut(500);
    });
});

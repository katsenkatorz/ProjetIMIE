$(document).ready(function () {

    $(".color").unbind('change').bind('change', function () {
        var id = $(this).attr('data-id');
        var label = $(this).attr("title");
        var value = $(this).val();
        var timeout = "";

        $.ajax({
            url: "/admin/parameter/putColor",
            method: "PUT",
            data: {
                id: id,
                label: label,
                value: value
            }
        }).done(function (result) {
            $("#responseMessageContent")
                .fadeIn(250)
                .removeClass('hidden');
            $("#responseMessage").html(result.message);
            timeout = setTimeout(function () {
                $("#responseMessageContent").fadeOut(250);
            }, 5000);
        }).fail(function (error) {
            $("#responseMessageContent")
                .fadeIn(250)
                .removeClass('hidden');
            $("#responseMessage").html(error.message);
            timeout = setTimeout(function () {
                $("#responseMessageContent").fadeOut(250);
            }, 5000);
        });
    });
});
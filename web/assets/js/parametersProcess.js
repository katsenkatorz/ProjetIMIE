$(document).ready(function () {
    $(".param").change(function () {

        var parameterId = $(this).attr("data-id");
        var value = $(this).val();
        var label = $(this).prev().attr("data-label");

        $.ajax({
            url: "/admin/parameter/put/"+parameterId,
            method: "POST",
            data: {
                label: label,
                value: value
            },
            success: function (result) {
                console.log(result.message)
            },
            error: function (error) {
                console.log(error.message)
            }
        });

    });
});
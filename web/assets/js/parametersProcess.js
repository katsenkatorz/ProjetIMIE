$(document).ready(function () {
    $(".param").change(function () {

        var parameterId = $(this).attr("data-id");
        var value = $(this).val();
        var label = $(this).prev().attr("data-label");

        console.log(parameterId);
        console.log(value);
        console.log(label);

        $.ajax({
            url: "/admin/putParameters",
            method: "POST",
            data: {
                parameterId: parameterId,
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
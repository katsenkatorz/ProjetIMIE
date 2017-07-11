function loadPartielView(idJob, resultContent)
{
    // On récupère la div qui contient la description
    var descriptionContent = resultContent;

    // On appelle le controleur qui renvois la vue partielle
    $.ajax({
        url: "/admin/job/"+idJob,
        type: "GET",
        dataType: "json",
        success: function (result)
        {
            // On stocke la vue partielle dans la description
            descriptionContent.html(result);

            loadRangeInput('.rangeInput');

            // Au changement de value de l'input
            $('.rangeInput').unbind('click').bind('click', function ()
            {
                var value = $(this).val();
                var jobId = $(this).next().val();
                var temperamentId = $(this).next().next().val();

                loadRangeInput('.rangeInput');

                // On appelle la route qui permet de sauvegarder les changements
                $.ajax({
                    url: "/admin/job/"+jobId+"/saveTemperament",
                    type: "POST",
                    data: {
                        temperament: temperamentId,
                        value: value
                    },
                    success: function (result)
                    {
                        $("#responseMessageContent")
                            .fadeIn(250)
                            .removeClass('hidden');
                        $("#responseMessage").html(result.message);
                        setTimeout(function ()
                        {
                            $("#responseMessageContent").fadeOut(250);
                        }, 5000);
                    }
                });
            });
        }
    });
}

function loadRangeInput(selector)
{
    $(selector).each(function ()
    {
        var value = $(this).val();
        var valueBlockRight = $(this).prev().prev();
        var valueBlockLeft = $(this).prev().prev().prev().html(value);

        valueBlockRight.html(value);
        valueBlockLeft.html(-value);

        $(this).unbind('click').bind('click', function ()
        {
            var value = $(this).val();
            var valueBlockRight = $(this).prev().prev();
            var valueBlockLeft = $(this).prev().prev().prev().html(value);

            valueBlockRight.html(value);
            valueBlockLeft.html(-value);
        });
    });
}

$(document).ready(function ()
{
    var JobPanel = $("a.getJobPersonnalityView");


    $('.formModifJob').unbind('submit').bind('submit', function (e)
    {
        e.preventDefault();

        var jobId = $(this).attr('data-id');
        var name = $('#modifName'+jobId).val();
        var minSalary = $('#modifMinSalary'+jobId).val();
        var maxSalary = $('#modifMaxSalary'+jobId).val();
        var description = $('#modifDescription'+jobId).val();
        var resultContent = $('#descriptionContent'+jobId);

        var formData = new FormData($(this)[0]);

        $.ajax({
            url: "/admin/job/"+jobId+"/put",
            type: "POST",
            dataType: "json",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (result)
            {
                loadPartielView(jobId, resultContent);
                $('#collapseTitle'+jobId).text(result.name);

                $('#modifJob' + jobId).modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();

                $("#responseMessageContent")
                    .fadeIn(250)
                    .removeClass('hidden');
                $("#responseMessage").html(result.message);
                setTimeout(function ()
                {
                    $("#responseMessageContent").fadeOut(250);
                }, 5000);
                setTimeout(function ()
                {
                    $("#responseMessageContent").addClass("hidden");
                }, 2000);
            }
        })

    });

    // $('.modifJob').unbind('click').bind('click', function (e)
    // {
    //     e.preventDefault();
    //
    //     var jobId = $(this).attr('data-id');
    //     var name = $('#modifName'+jobId).val();
    //     var minSalary = $('#modifMinSalary'+jobId).val();
    //     var maxSalary = $('#modifMaxSalary'+jobId).val();
    //     var description = $('#modifDescription'+jobId).val();
    //     var resultContent = $('#descriptionContent'+jobId);
    //
    //     $.ajax({
    //         url: "/admin/job/"+jobId+"/put",
    //         type: "PUT",
    //         dataType: "json",
    //         data: {
    //             name: name,
    //             minSalary: minSalary,
    //             maxSalary: maxSalary,
    //             description: description
    //         },
    //         success: function (result)
    //         {
    //             loadPartielView(jobId, resultContent);
    //             $('#collapseTitle'+jobId).text(result.name);
    //
    //             $('#modifJob' + jobId).modal('hide');
    //             $('body').removeClass('modal-open');
    //             $('.modal-backdrop').remove();
    //
    //             $("#responseMessageContent")
    //                 .fadeIn(250)
    //                 .removeClass('hidden');
    //             $("#responseMessage").html(result.message);
    //             setTimeout(function ()
    //             {
    //                 $("#responseMessageContent").fadeOut(250);
    //             }, 5000);
    //             setTimeout(function ()
    //             {
    //                 $("#responseMessageContent").addClass("hidden");
    //             }, 2000);
    //         }
    //     })
    //
    // });

    $('.modalDeleteJob').unbind('show.bs.modal').bind('show.bs.modal', function (event)
    {
        var modal = $(this);
        var button = $(event.relatedTarget);
        var name = button.data('name');

        modal.find('.modal-title').text('Confirmation de la suppression de ' + name);
        modal.find('.modal-body p').html('Etes-vous sûr de supprimer le métier :&nbsp;<strong>' + name + ' ?</strong>');
    });

    $('.deleteJob').unbind('click').bind('click', function ()
    {
        var idJob = $(this).attr("data-id");

        $.ajax({
            url: "/admin/job/"+idJob+"/delete",
            type: "DELETE",
            dataType: "json",
            success: function (result)
            {
                window.location = window.location.href;

                $('#modalDeleteJob' + idJob).modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            }
        })
    });

    JobPanel.unbind('click').bind('click', function ()
    {
        var idJob = $(this).data('id');
        var resultContent = $('#descriptionContent'+idJob);

        loadPartielView(idJob, resultContent);
    });
});
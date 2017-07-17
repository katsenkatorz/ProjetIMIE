function loadPartielView(idJob, resultContent)
{
    // On récupère la div qui contient la description
    var descriptionContent = resultContent;

    // On appelle le controleur qui renvois la vue partielle
    $.ajax({
        url: "/admin/job/" + idJob,
        type: "GET",
        dataType: "json",
        success: function (result)
        {
            // On stocke la vue partielle dans la description
            descriptionContent.html(result);

            loadRangeInput('.rangeInput');

            // Au changement de value de l'input
            $('.rangeInput').unbind('change').bind('change', function ()
            {
                var value = $(this).val();
                var jobId = $(this).next().val();
                var temperamentId = $(this).next().next().val();

                loadRangeInput('.rangeInput');

                // On appelle la route qui permet de sauvegarder les changements
                $.ajax({
                    url: "/admin/job/" + jobId + "/saveTemperament",
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


    $('#modalJob').unbind('show.bs.modal').bind('show.bs.modal', function (event)
    {
        var nameInput = $('#nameInput');
        var minSalaryInput = $('#minSalaryInput');
        var maxSalaryInput = $('#maxSalaryInput');
        var descriptionInput = CKEDITOR.instances.descriptionInput;

        nameInput.val("");
        minSalaryInput.val("");
        maxSalaryInput.val("");
        descriptionInput.setData("");
    });

    $('#modalUpdateJob').unbind('show.bs.modal').bind('show.bs.modal', function (event)
    {
        var modal = $(this);
        var button = $(event.relatedTarget);

        // Récupération des formulaires
        var nameInput = $('#nameUpdateInput');
        var minSalaryInput = $('#minSalaryUpdateInput');
        var maxSalaryInput = $('#maxSalaryUpdateInput');
        var descriptionInput = CKEDITOR.instances.descriptionUpdateInput;
        var submitInput = $('#submitUpdateInput');

        // Récupération des data-attributes pour la modification
        var action = button.data('action');
        var jobId = button.data('job');
        var minSalary = button.data('min-salary');
        var maxSalary = button.data('max-salary');
        var description = button.data('description');
        var name = button.data('name');

        // Assignation des valeurs aux inputs
        nameInput.val(name);
        minSalaryInput.val(minSalary);
        maxSalaryInput.val(maxSalary);
        descriptionInput.setData(description);
        submitInput.html("Modifier");

        // Requete ajax
        $('#formUpdateJob').unbind('submit').bind('submit', function (e)
        {
            e.preventDefault();

            var resultContent = $('#descriptionContent' + jobId);
            var formData = new FormData($(this)[0]);

            $.ajax({
                url: action,
                type: "POST",
                dataType: "json",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result)
                {
                    loadPartielView(jobId, resultContent);

                    $('#collapseTitle' + jobId).text(result.job.name);

                    button.attr('data-name', result.job.name);
                    button.attr('data-min-salary', result.job.minSalary);
                    button.attr('data-max-salary', result.job.maxSalary);
                    button.attr('data-description', result.job.description);

                    modal.modal('hide');
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
    });

    // Traitement pour l'update de metier


    $('.modalDeleteJob').unbind('show.bs.modal').bind('show.bs.modal', function (event)
    {
        var modal = $(this);
        var button = $(event.relatedTarget);
        var name = button.data('name');
        var action = button.data('action');
        var idJob = button.data('job');

        modal.find('.modal-title').text('Confirmation de la suppression de ' + name);
        modal.find('.modal-body p').html('Etes-vous sûr de supprimer le métier :&nbsp;<strong>' + name + ' ?</strong>');

        $('.deleteJob').unbind('click').bind('click', function ()
        {
            $.ajax({
                url: action,
                type: "DELETE",
                dataType: "json",
                success: function ()
                {
                    window.location = window.location.href;

                    $('#modalDeleteJob').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            })
        });
    });


    JobPanel.unbind('click').bind('click', function ()
    {
        var idJob = $(this).data('id');
        var resultContent = $('#descriptionContent' + idJob);

        loadPartielView(idJob, resultContent);
    });
});
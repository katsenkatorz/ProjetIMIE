function loadPartielView(idJob, resultContent)
{
    // On récupère la div qui contient la description
    var descriptionContent = resultContent;

    // On appelle le controleur qui renvois la vue partielle
    $.ajax({
        url: "/admin/job/" + idJob,
        type: "GET",
        dataType: "json"
    }).done(function (result)
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
                }
            }).done(function (result)
            {
                $("#responseMessageContent")
                    .fadeIn(250)
                    .removeClass('hidden');
                $("#responseMessage").html(result.message);
                setTimeout(function ()
                {
                    $("#responseMessageContent").fadeOut(250);
                }, 5000);
            }).fail(function (error)
            {
                $("#responseMessageContent")
                    .fadeIn(250)
                    .removeClass('hidden');
                $("#responseMessage").html(error.message);
                setTimeout(function ()
                {
                    $("#responseMessageContent").fadeOut(250);
                }, 5000);
            });
        });

    }).fail(function (error)
    {
        $("#responseMessageContent")
            .fadeIn(250)
            .removeClass('hidden');
        $("#responseMessage").html(error.message);
        setTimeout(function ()
        {
            $("#responseMessageContent").fadeOut(250);
        }, 5000);
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

    // Permet de nettoyer les champs du modal
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

    // Permet de réaliser l'update d'un métier
    $('#modalUpdateJob').unbind('show.bs.modal').bind('show.bs.modal', function (event)
    {
        // On récupère le modal et le button
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

            // Récupération de la zone ou afficher le résultat et des informations du formulaire
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
                beforeSend: function ()
                {
                    $('#loadingmessage').show();
                }
            }).done(function (result)
            {
                // Chargement de la vu partielle
                loadPartielView(jobId, resultContent);

                // Mise a jours du nom
                $('#collapseTitle' + jobId).text(result.job.name);

                // Mise a jours des data-attributes
                button.attr('data-name', result.job.name);
                button.attr('data-min-salary', result.job.minSalary);
                button.attr('data-max-salary', result.job.maxSalary);
                button.attr('data-description', result.job.description);

                // Fermeture du modal
                modal.modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();

                // Chargement du message de succes
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
            }).fail(function (error)
            {
                // Affichage du message d'erreur
                $("#responseMessageContent")
                    .fadeIn(250)
                    .removeClass('hidden');
                $("#responseMessage").html(error.message);
                setTimeout(function ()
                {
                    $("#responseMessageContent").fadeOut(250);
                }, 5000);
            }).always(function ()
            {
                $('#loadingmessage').hide();
            });
        });
    });

    // Traitement pour l'update de metier
    $('.modalDeleteJob').unbind('show.bs.modal').bind('show.bs.modal', function (event)
    {
        var modal = $(this);
        var button = $(event.relatedTarget);
        var name = button.data('name');
        var action = button.data('action');

        modal.find('.modal-title').text('Confirmation de la suppression de ' + name);
        modal.find('.modal-body p').html('Etes-vous sûr de supprimer le métier :&nbsp;<strong>' + name + ' ?</strong>');

        $('.deleteJob').unbind('click').bind('click', function ()
        {
            $.ajax({
                url: action,
                type: "DELETE",
                dataType: "json"
            }).done(function ()
            {
                window.location = window.location.href;

                $('#modalDeleteJob').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            }).fail(function (error)
            {
                // Affichage du message d'erreur
                $("#responseMessageContent")
                    .fadeIn(250)
                    .removeClass('hidden');
                $("#responseMessage").html(error.message);
                setTimeout(function ()
                {
                    $("#responseMessageContent").fadeOut(250);
                }, 5000);
            });
        });
    });


    JobPanel.unbind('click').bind('click', function ()
    {
        var idJob = $(this).data('id');
        var resultContent = $('#descriptionContent' + idJob);

        loadPartielView(idJob, resultContent);
    });
});
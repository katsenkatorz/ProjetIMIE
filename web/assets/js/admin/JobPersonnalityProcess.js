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

function clearInput(source, crop)
{
    var $form = $('<form>');
    var $targ = source.clone().appendTo($form);
    $form[0].reset();
    source.replaceWith($targ);

    crop.croppie('destroy');
}

function addEventToImageInput(selector, crop)
{
    selector.unbind('change').bind('change', function ()
    {
        var reader = new FileReader();
        reader.onload = function (e)
        {
            crop.croppie('bind', {
                url: e.target.result
            }).then(function () {});

        };
        reader.readAsDataURL(this.files[0]);
    });
}

function createUploadCrop(selector)
{
    return selector.croppie({
        enableExif: true,
        viewport: {
            width: $('#widthInput').val(),
            height: $('#heightInput').val(),
            type: 'square'
        },
        boundary: {
            width: 300,
            height: 300
        }
    });
}

$(document).ready(function ()
{

    var JobPanel = $("a.getJobPersonnalityView");
    var modalJob = $('#modalJob');

    modalJob.unbind('hidden.bs.modal').bind('hidden.bs.modal', function (event)
    {
        clearInput($("#imageJob"), $uploadCrop);
    });

    // Permet de nettoyer les champs du modal
    modalJob.unbind('show.bs.modal').bind('show.bs.modal', function (event)
    {
        var fileInput = $("#imageJob");

        $uploadCrop = createUploadCrop($('#imageHandler'));
        addEventToImageInput(fileInput, $uploadCrop);

        var button = $(event.relatedTarget);
        var action;

        var nameInput = $('#nameInput');
        var minSalaryInput = $('#minSalaryInput');
        var maxSalaryInput = $('#maxSalaryInput');
        var descriptionInput = CKEDITOR.instances.descriptionInput;
        var submitInput = $('#submitInput');

        if(button.data('method') === "post")
        {
            action = button.data('action');

            nameInput.val("");
            minSalaryInput.val("");
            maxSalaryInput.val("");
            descriptionInput.setData("");

            submitInput.html('Créer un métier');

            // Requete ajax
            $('#formJob').unbind('submit').bind('submit', function (e)
            {
                var that = this;

                $uploadCrop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function (resp)
                {
                    $('#dataCroppedImage').val(resp);
                    var formData = new FormData($(that)[0]);

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
                    }).done(function () {})
                        .fail(function (error)
                        {
                            clearInput(fileInput, $uploadCrop);
                            // Affichage du message d'erreur
                            $("#responseMessageContent")
                                .fadeIn(250)
                                .removeClass('hidden');
                            $("#responseMessage").html(error.message);
                            setTimeout(function ()
                            {
                                $("#responseMessageContent").fadeOut(250);
                            }, 5000);
                        })
                        .always(function ()
                        {
                            $('#loadingmessage').hide();
                        });
                });
            });
        }

        if(button.data('method') === "put")
        {
            // On récupère le modal et le button
            var modal = $(this);
            // Récupération des data-attributes pour la modification
            action = button.data('action');
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
            $('#formJob').unbind('submit').bind('submit', function (e)
            {
                e.preventDefault();

                var that = this;
                var resultContent = $('#descriptionContent' + jobId);

                $uploadCrop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function (resp)
                {
                    console.log(resp);
                    $('#dataCroppedImage').val(resp);
                    var formData = new FormData($(that)[0]);

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
        }
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
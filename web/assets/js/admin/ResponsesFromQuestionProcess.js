// Permet de charger les réponses
function loadResponse(idQuestion, env, PanelTitle)
{
    // Pour régler un bug des accordions
    $('.collapse').collapse("hide");
    $(env.parentElement.nextElementSibling).collapse("show");

    var responseContent = $(".responseContent" + idQuestion);

    // Récupération des réponses pour une question et affichage dans le content de l'accordion
    $.ajax({
        url: "/tdb-admin/question/" + idQuestion + "/getResponse",
        type: "GET",
        dataType: "json"
    }).done(function (result)
    {
        // On affiche le résultat
        responseContent.html(result.view);

        loadRangeInput(".rangeInput");

        $('.modalConfirmDeleteResponse').unbind('show.bs.modal').bind('show.bs.modal', function (event)
        {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var action = button.data('action');

            var name = button.data('name');

            modal.find('.modal-title').text('Confirmation de la suppression de ' + name);
            modal.find('.modal-body p').html('Etes-vous sûr de supprimer la réponse :&nbsp;<strong>' + name + '</strong>');

            // Supression d'une réponse
            $(".deleteResponseButton").unbind('click').bind('click', function (e)
            {
                e.preventDefault();

                $.ajax({
                    url: action,
                    type: "DELETE"
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

                }).always(function ()
                {
                    $('#modalConfirmDeleteResponse').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();

                    PanelTitle.trigger("click");
                    setTimeout(function ()
                    {
                        PanelTitle.trigger("click");
                    }, 500);
                });
            });
        });

        var questionId = result.questionId;
        var addResponseButton = $('#responseButton'+questionId);

        addResponseButton.show();

        if(result.responseNumber >= 6)
            addResponseButton.hide();

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

function loadRangeInput(inputRange)
{
    function changeValue(inputRange)
    {
        var value = $(inputRange).val();
        var valueBlockRight = $('#valueTempResponse');
        var valueBlockLeft = $("#valueOpposedTempResponse");

        valueBlockRight.html(-value);
        valueBlockLeft.html(value);
    }

    $(inputRange).each(function ()
    {
        changeValue($(this));

        $(this).unbind('change').bind('change', function ()
        {
            changeValue($(this));
        });
    });
}

function loadTemperament(idTemperament, temperamentDiv, opposedTempDiv, selectDiv)
{
    selectDiv = selectDiv || false;

    function getHomonyme(idTemperament, temperamentDiv, opposedTempDiv)
    {
        $.ajax({
            url: "/tdb-admin/job/getTemperament/" + idTemperament,
            type: "GET",
            dataType: "json"
        }).done(function (result)
        {
            var temperamentContainer = $(temperamentDiv);
            var opposedTemperamentContainer = $(opposedTempDiv);

            temperamentContainer.html(result.temperament);
            opposedTemperamentContainer.html(result.opposedTemperament);

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

    getHomonyme(idTemperament, temperamentDiv, opposedTempDiv);

    if (selectDiv)
    {
        $(selectDiv).unbind('change').bind("change", function ()
        {
            var id = $(this).val();

            getHomonyme(id, temperamentDiv, opposedTempDiv);
        });
    }
}

function refreshSelectInput(selectInput, hiddenInput)
{
    function changeValue(selectInput, hiddenInput)
    {
        var temperament = $(selectInput).val();
        var hiddenTemp = document.querySelector(hiddenInput);

        hiddenTemp.value = temperament;
    }

    changeValue(selectInput, hiddenInput);

    $(selectInput).unbind('change').bind('change', function ()
    {
        changeValue(selectInput, hiddenInput)
    });
}

function sendResponse(form, action, PanelTitle)
{
    $(form).unbind('submit').bind('submit', function (e)
    {
        e.preventDefault();

        var that = this;

        $uploadCrop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (resp)
        {
            var formData = new FormData($(that)[0]);

            formData.append('croppedImage', resp);

            $.ajax({
                url: action,
                type: "POST",
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
                // Fermeture du modal en cas de success
                $('#modalResponse').modal('hide');
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

                PanelTitle.trigger("click");
                setTimeout(function ()
                {
                    PanelTitle.trigger("click");
                }, 500);
            }).fail(function (error)
            {
                $("#responseMessageContent")
                    .fadeIn(250)
                    .removeClass('hidden');
                $("#responseMessage").html(error);
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
    loadRangeInput("#valueResponse");

    refreshSelectInput('#selectTemperamentQuestion', '#tempQuestion');

    // Pour chaque éléments qui contienne un liens a de classe getJobPersonnalityView
    $(".panel-title").unbind("click").bind("click", function ()
    {
        // Récupération de l'id de la question et de la zone ou stocké la réponse
        var idQuestion = $(this).data('id');
        var env = this;
        var PanelTitle = $(this);

        loadResponse(idQuestion, env, PanelTitle);

        var modalResponse = $('#modalResponse');

        modalResponse.unbind('hidden.bs.modal').bind('hidden.bs.modal' ,function ()
        {
            clearInput($('#imageResponse'), $uploadCrop);
        });

        // Ajout/Modification d'une réponse
        modalResponse.unbind('show.bs.modal').bind('show.bs.modal', function (event)
        {
            var fileInput = $('#imageResponse');
            $uploadCrop = createUploadCrop($('#imageHandler'));
            addEventToImageInput(fileInput, $uploadCrop);

            var button = $(event.relatedTarget);
            var idQuestion = button.data('question');
            var idTemperament = button.data('temperament');
            var hiddenQuestion = $('#idQuestionResponse');
            var action = button.data('action');
            var modalResponseTitle = $('#modalResponseTitle');
            var responseSaveButton = $('#response_save');

            hiddenQuestion.val(idQuestion);
            modalResponseTitle.html("Ajouter une réponse");
            responseSaveButton.html("Ajouter une réponse");

            loadTemperament(idTemperament, "#temperamentResponse", "#opposedTemperamentResponse");

            if (button.data('type') === 'create')
                sendResponse("#formResponse", action, PanelTitle);

            if (button.data('type') === 'update')
            {
                var value = button.data('value');
                var label = button.data('label');

                modalResponseTitle.html("Modifier une réponse");
                responseSaveButton.html("Modifier une réponse");

                $('#labelResponse').val(label);
                $('#valueResponse').val(value);

                sendResponse("#formResponse", action, PanelTitle);
            }
        });
    });

    $('.modalConfirmDeleteQuestion').unbind('show.bs.modal').bind('show.bs.modal', function (event)
    {
        var modal = $(this);
        var button = $(event.relatedTarget);
        var name = button.data('name');
        var questionId = button.data('id');

        modal.find('.modal-title').text('Confirmation de la suppression de ' + name);
        modal.find('.modal-body p').html('Etes-vous sûr de supprimer la question :&nbsp;<strong>' + name + '</strong>');

        // Suppresion d'une question
        $(".deleteQuestion").unbind('click').bind('click', function (e)
        {
            e.preventDefault();

            $.ajax({
                url: "/tdb-admin/question/delete/" + questionId,
                type: "DELETE"
            }).done(function ()
            {
                window.location = window.location.href;
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
    });


    $('#modalQuestion').unbind('show.bs.modal').bind('show.bs.modal', function (event)
    {
        var modal = $(this);
        var button = $(event.relatedTarget);

        // Récupération des inputs
        var hiddenTemp = $("#tempQuestion");
        var selectTemp = $('#selectTemperamentQuestion');
        var labelTemp = $('#question_label');
        var submitButton = $('#question_save');
        var modalTitle = $('#modalTitleQuestion');
        var formQuestion = $('#formQuestion');
        var idTempForCreate = selectTemp.val();

        loadTemperament(idTempForCreate, "#temperamentQuestion", "#opposedTemperamentQuestion", "#selectTemperamentQuestion");

        formQuestion.unbind('submit').bind('submit');
        labelTemp.val("");
        submitButton.html('Ajouter une question');
        modalTitle.html("Ajouter une question");

        if (button.data('type') === 'update')
        {
            // Récupération des data
            var id = button.data('id');
            var label = button.data('label');
            var temperament = button.data('temperament');
            var action = button.data('action');

            // Mise à jours des inputs avec les data
            submitButton.html("Modifier la question");
            labelTemp.val(label);
            modalTitle.html("Modifier une question");
            selectTemp.val(temperament);
            hiddenTemp.val(id);

            refreshSelectInput('#selectTemperamentQuestion', '#tempQuestion');

            loadTemperament(temperament, "#temperamentQuestion", "#opposedTemperamentQuestion", "#selectTemperamentQuestion");

            // Requete ajax
            formQuestion.unbind('submit').bind('submit', function (e)
            {
                e.preventDefault();

                var formData = new FormData($(this)[0]);

                $.ajax({
                    url: action,
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
                }).done(function (result)
                {
                    modal.modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();

                    $("#questionLabelDiv" + id).html(result.name);

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
        }
    });

});
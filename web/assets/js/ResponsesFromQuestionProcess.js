// Permet de charger les réponses
function loadResponse(idQuestion, env, PanelTitle)
{
    // Pour régler un bug des accordions
    $('.collapse').collapse("hide");
    $(env.parentElement.nextElementSibling).collapse("show");

    var responseContent = $(".responseContent" + idQuestion);

    // Récupération des réponses pour une question et affichage dans le content de l'accordion
    $.ajax({
        url: "/admin/question/" + idQuestion + "/getResponse",
        type: "GET",
        dataType: "json",
        success: function (result)
        {
            // On affiche le résultat
            responseContent.html(result);

            loadTemperament("#temperamentResponse"+idQuestion);

            if(result.length > 0)
                loadTemperament(".temperamentModifResponse");

            loadRangeInput(".rangeInput");

            $('.modalConfirmDeleteResponse').unbind('show.bs.modal').bind('show.bs.modal', function (event)
            {
                var modal = $(this);
                var button = $(event.relatedTarget);
                var name = button.data('name');

                modal.find('.modal-title').text('Confirmation de la suppression de ' + name);
                modal.find('.modal-body p').html('Etes-vous sûr de supprimer la réponse :&nbsp;<strong>' + name + '</strong>');
            });

            // Supression d'une réponse
            $(".deleteResponseButton").unbind('click').bind('click', function (e)
            {
                e.preventDefault();

                var responseId = $(this).attr('data-id');
                var idModal = $(this).attr('data-modalId');

                $.ajax({
                    url: "/admin/question/"+idQuestion+"/deleteResponse/"+responseId,
                    type: "DELETE",
                    success: function (result)
                    {
                        $(idModal).modal('hide');
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
                    }
                });
            });

            // Au click sur valider du modal de modification d'une réponse
            $(".modalModifResponse").unbind('click submit').bind('click submit', function (e)
            {
                e.preventDefault();

                var responseId = $(this).attr("id");
                var value = $("#valueModifResponse" + responseId).val();
                var image = $("#imageModifResponse" + responseId).val();
                var label = $("#labelModifResponse" + responseId).val();
                var temperament = $('#temperamentModifResponse' + responseId).val();

                // On appelle la route qui permet de sauvegarder les changements
                $.ajax({
                    url: "/admin/question/"+idQuestion+"/putResponse/"+responseId,
                    type: "PUT",
                    data: {
                        value: value,
                        image: image,
                        label: label,
                        temperament: temperament

                    },
                    success: function (result)
                    {
                        // Fermeture du modal en cas de success
                        $('#modalModifResponse' + idQuestion).modal('hide');
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
                        setTimeout(function()
                        {
                            PanelTitle.trigger("click");
                        }, 500);
                    }
                });
            });

        },
        error: function (error)
        {
            console.log(error.statusText);
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

        valueBlockRight.html(100-value);
        valueBlockLeft.html(value);

        $(this).unbind('click').bind('click', function ()
        {
            var value = $(this).val();
            var valueBlockRight = $(this).prev().prev();
            var valueBlockLeft = $(this).prev().prev().prev().html(value);

            valueBlockRight.html(100-value);
            valueBlockLeft.html(value);
        });
    });
}

function loadTemperament(selector)
{
    function getHomonyme(idTemperament, context)
    {
        $.ajax({
            url: "/admin/job/getTemperament/"+idTemperament,
            type: "GET",
            dataType: "json",
            success: function (result)
            {
                var temperamentContainer = context.next();
                var opposedTemperamentContainer = context.next().next().next().next();

                temperamentContainer.html(result.temperament);
                opposedTemperamentContainer.html(result.opposedTemperament);
            },
            error: function (error)
            {
                console.log(error);
            }
        });
    }

    getHomonyme($(selector).val(), $(selector));

    $(selector).change(function ()
    {
        var id = $(this).val();

        getHomonyme(id, $(this));
    });
}

$(document).ready(function ()
{
    loadRangeInput(".rangeInput");

    // Pour chaque éléments qui contienne un liens a de classe getJobPersonnalityView
    $(".panel-title").unbind("click").bind("click", function ()
    {
        // Récupération de l'id de la question et de la zone ou stocké la réponse
        var idQuestion = $(this).data('id');
        var env = this;
        var PanelTitle = $(this);

        loadResponse(idQuestion, env, PanelTitle);

        // Ajout d'une réponse
        $(".submitResponse").unbind('click').bind('click', function (e)
        {
            e.preventDefault();

            var rangeInput = $("#valueResponse" + idQuestion);
            var value = rangeInput.val();
            var image = $("#imageResponse" + idQuestion).val();
            var label = $("#labelResponse" + idQuestion).val();
            var temperament = $("#temperamentResponse" + idQuestion).val();

            $.ajax({
                url: "/admin/question/"+idQuestion+"/postResponse",
                type: "POST",
                data: {
                    value: value,
                    image: image,
                    label: label,
                    temperament: temperament
                },
                success: function (result)
                {
                    // Fermeture du modal en cas de success
                    $('#modalResponse' + idQuestion).modal('hide');
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
                },
                error: function (error)
                {
                    console.log(error);
                }
            });
        });


    });

    $('.modalConfirmDeleteQuestion').unbind('show.bs.modal').bind('show.bs.modal', function (event)
    {
        var modal = $(this);
        var button = $(event.relatedTarget);
        var name = button.data('name');

        modal.find('.modal-title').text('Confirmation de la suppression de ' + name);
        modal.find('.modal-body p').html('Etes-vous sûr de supprimer la question :&nbsp;<strong>' + name + '</strong>');
    });


    // Modification d'une question
    $(".submitModifQuestion").unbind('click').bind('click', function (e)
    {
        e.preventDefault();

        var questionId = $(this).attr('id');
        var label = $("#labelModifQuestion" + questionId).val();
        var responseContent = $(".responseContent" + questionId);

        $.ajax({
            url: "/admin/question/put/"+questionId,
            type: "PUT",
            data: {
                label: label
            },
            success: function (result)
            {
                $('#modalModifQuestion' + questionId).modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();

                $("#questionLabelDiv"+questionId).html(result.name);
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

    // Suppresion d'une question
    $(".deleteQuestion").unbind('click').bind('click', function (e)
    {
        e.preventDefault();

        var questionId = $(this).attr("data-id");

        $.ajax({
            url: "/admin/question/delete/"+questionId,
            type: "DELETE",
            success: function (result)
            {
                window.location = window.location.href;
            }
        });
    });
});
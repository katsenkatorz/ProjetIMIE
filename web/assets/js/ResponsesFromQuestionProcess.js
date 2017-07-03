// Permet de charger les réponses
function loadResponse(idQuestion, env, PanelTitle)
{
    // Pour régler un bug des accordions
    $('.collapse').collapse("hide");
    $(env.parentElement.nextElementSibling).collapse("show");

    var responseContent = $(".responseContent" + idQuestion);

    // Récupération des réponses pour une question et affichage dans le content de l'accordion
    $.ajax({
        url: "/admin/getResponsesFromQuestion/" + idQuestion,
        type: "GET",
        dataType: "json",
        success: function (result)
        {
            // On affiche le résultat
            responseContent.html(result);

            loadTemperament("#temperamentResponse"+idQuestion);
            loadTemperament(".temperamentModifResponse");

            // Supression d'une réponse
            $(".deleteResponseButton").unbind('click').bind('click', function (e)
            {
                e.preventDefault();

                var responseId = $(this).prev().val();

                $.ajax({
                    url: "/admin/deleteResponse",
                    type: "POST",
                    data: {
                        responseId: responseId
                    },
                    success: function (result)
                    {
                        // loadResponse(result.idQuestion, env, PanelTitle);

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
                    url: "/admin/putResponse",
                    type: "POST",
                    data: {
                        responseId: responseId,
                        value: value,
                        image: image,
                        label: label,
                        temperament: temperament

                    },
                    success: function (result)
                    {
                        console.log(result);
                        // Fermeture du modal en cas de success
                        $('#modalModifResponse' + idQuestion).modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();

                        PanelTitle.trigger("click");
                        setTimeout(function()
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

        },
        error: function (error)
        {
            console.log(error.statusText);
        }
    });
}

function loadTemperament(selector)
{
    function getHomonyme(idTemperament, context)
    {
        $.ajax({
            url: "/admin/getTemperament/"+idTemperament,
            type: "GET",
            dataType: "json",
            success: function (result)
            {
                var temperamentContainer = context.next();
                var opposedTemperamentContainer = context.next().next();

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

            var value = $("#valueResponse" + idQuestion).val();
            var image = $("#imageResponse" + idQuestion).val();
            var label = $("#labelResponse" + idQuestion).val();
            var temperament = $("#temperamentResponse" + idQuestion).val();

            $.ajax({
                url: "/admin/postResponse",
                type: "POST",
                data: {
                    question: idQuestion,
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


    // Modification d'une question
    $(".submitModifQuestion").unbind('click').bind('click', function (e)
    {
        e.preventDefault();

        var questionId = $(this).attr('id');
        var label = $("#labelModifQuestion" + questionId).val();
        var responseContent = $(".responseContent" + questionId);

        console.log(questionId);
        console.log(label);
        console.log(responseContent);

        $.ajax({
            url: "/admin/putQuestion",
            type: "POST",
            data: {
                question: questionId,
                label: label
            },
            success: function (result)
            {
                location.reload();
                console.log(result);
            },
            error: function (error)
            {
                console.log(error);
            }
        });
    });

    // Suppresion d'une question
    $(".deleteQuestion").unbind('click').bind('click', function (e)
    {
        e.preventDefault();

        var questionId = $(this).attr("data-id");

        $.ajax({
            url: "/admin/deleteQuestion",
            type: "POST",
            data: {
                question: questionId
            },
            success: function (result)
            {
                location.reload();
                console.log(result);
            },
            error: function (error)
            {
                console.log(error);
            }
        });
    });
});
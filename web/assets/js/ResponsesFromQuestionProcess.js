$(document).ready(function ()
{
    $('.collapse').collapse("hide");

    // Pour chaque éléments qui contienne un liens a de classe getJobPersonnalityView
    $(".panel-title").on("click" ,function ()
    {
        // Pour régler un bug des accordions
        $('.collapse').collapse("hide");
        $(this.parentElement.nextElementSibling).collapse("show");

        // Récupération de l'id de la question et de la zone ou stocké la réponse
        var idQuestion = $(this).data('id');
        var responseContent = $(".responseContent"+idQuestion);

        // Récupération des réponses pour une question et affichage dans le content de l'accordion
        $.ajax({
            url: "/admin/getResponsesFromQuestion/" + idQuestion,
            type: "GET",
            dataType: "json",
            success: function (result)
            {
                // On affiche le résultat
                responseContent.html(result);

                // Supression d'une réponse
                $(".deleteResponseButton").on('click', function (e)
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
                            // Rechargement de la vue partiel pour la mise à jours des données
                            $.ajax({
                                url: "/admin/getResponsesFromQuestion/" + idQuestion,
                                type: "GET",
                                dataType: "json",
                                success: function (result)
                                {
                                    responseContent.html(result);
                                },
                                error: function (error)
                                {
                                    console.log(error.statusText);
                                }
                            });
                        },
                        error: function (error)
                        {
                            console.log(error);
                        }
                    });
                });

                // Ajout d'une réponse
                $(".submitResponse").on('click submit',function (e)
                {
                    e.preventDefault();

                    var value = $("#valueResponse"+idQuestion).val();
                    var image = $("#imageResponse"+idQuestion).val();
                    var label = $("#labelResponse"+idQuestion).val();
                    var personnalityType = $("#personnalityTypeResponse"+idQuestion).val();

                    $.ajax({
                        url: "/admin/postResponse",
                        type: "POST",
                        data: {
                            question: idQuestion,
                            value: value,
                            image: image,
                            label: label,
                            personnalityType: personnalityType

                        },
                        success: function (result)
                        {
                            // Fermeture du modal en cas de success
                            $('#modalResponse'+idQuestion).modal('hide');
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();

                            // Rechargement de la vue partiel pour la mise à jours des données
                            $.ajax({
                                url: "/admin/getResponsesFromQuestion/" + idQuestion,
                                type: "GET",
                                dataType: "json",
                                success: function (result)
                                {
                                    responseContent.html(result);
                                },
                                error: function (error)
                                {
                                    console.log(error.statusText);
                                }
                            });
                        },
                        error: function (error)
                        {
                            console.log(error);
                        }
                    });
                });

                // Au click sur valider du modal de modification d'une réponse
                $(".modalSubmit").on('click submit', function (e)
                {
                    e.preventDefault();

                    var responseId = $(this).attr("id");

                    var value = $("#valueModifResponse"+responseId).val();
                    var image = $("#imageModifResponse"+responseId).val();
                    var label = $("#labelModifResponse"+responseId).val();
                    var personnalityType = $('#personnalityType'+responseId).val();

                    // On appelle la route qui permet de sauvegarder les changements
                    $.ajax({
                        url: "/admin/putResponse",
                        type: "POST",
                        data: {
                            responseId: responseId,
                            value: value,
                            image: image,
                            label: label,
                            personnalityType: personnalityType

                        },
                        success: function (result)
                        {
                            // Fermeture du modal en cas de success
                            $('#modalModifResponse'+idQuestion).modal('hide');
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();

                            // Rechargement de la vue partiel pour la mise à jours des données
                            $.ajax({
                                url: "/admin/getResponsesFromQuestion/" + idQuestion,
                                type: "GET",
                                dataType: "json",
                                success: function (result)
                                {
                                    responseContent.html(result);
                                },
                                error: function (error)
                                {
                                    console.log(error.statusText);
                                }
                            });
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
    });

    $(".deleteQuestion").on('click', function (e)
    {
        e.preventDefault();
        var questionId = $(this).attr("id");

        $.ajax({
            url: "/admin/deleteQuestion",
            type: "POST",
            data: {
                question: questionId
            },
            success: function (result)
            {
                location.reload();
            },
            error: function (error)
            {
                console.log(error);
            }
        });
    });

    $(".submitModifQuestion").on('click', function (e)
    {
        e.preventDefault();

        var questionId = $(this).attr('id');
        var label = $("#labelModifQuestion"+questionId).val();
        var responseContent = $(".responseContent"+questionId);

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
            },
            error: function (error)
            {
                console.log(error);
            }
        });
    });
});
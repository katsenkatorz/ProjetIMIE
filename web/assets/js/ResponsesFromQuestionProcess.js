$(document).ready(function ()
{
    // Pour chaque éléments qui contienne un liens a de classe getJobPersonnalityView
    [].forEach.call($("a.getResponsesFromQuestion"), function (elem)
    {
        // On lui ajoute un event au onClick
        elem.onclick = function ()
        {
            var idQuestion = this.parentElement.parentElement.nextElementSibling.id;
            var responseContent = this.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild;

            $.ajax({
                url: "/admin/getResponsesFromQuestion/"+idQuestion,
                type: "GET",
                dataType: "json",
                success: function (result)
                {
                    // On stocke la vue partielle dans la description
                    responseContent.innerHTML = result;

                    // Au changement de value de l'input
                    // $('input#job_personnality_value').on('input', function ()
                    // {
                    //     var jobId = this.nextElementSibling.nextElementSibling.value;
                    //     var personnalityTypeId = this.nextElementSibling.nextElementSibling.nextElementSibling.value;
                    //     var value = this.value;
                    //     // On appelle la route qui permet de sauvegarder les changements
                    //     $.ajax({
                    //         url: "/admin/saveJobPersonnality",
                    //         type: "POST",
                    //         data: {
                    //             job: jobId,
                    //             personnalityType: personnalityTypeId,
                    //             value: value
                    //         },
                    //         success: function (result)
                    //         {
                    //             // A la réussite on affiche le message de succes
                    //             $("p.SaveModificationMessage").html(result.message);
                    //         },
                    //         error: function (error)
                    //         {
                    //             $("p.SaveModificationMessage").html(error)
                    //         }
                    //     })
                    //
                    //
                    // });
                },
                error: function (error)
                {
                    console.log(error.statusText);
                }
            });
        }
    });
});
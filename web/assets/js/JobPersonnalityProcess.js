$(document).ready(function ()
{
    // Pour chaque éléments qui contienne un liens a de classe getJobPersonnalityView
    [].forEach.call($("a.getJobPersonnalityView"), function (elem)
    {
        // On lui ajoute un event au onClick
        elem.onclick = function ()
        {
            // On récupère l'id du Job
            var idJob = this.parentElement.parentElement.nextElementSibling.id;

            // On récupère la devi qui contient la description
            var descriptionContent = this.parentElement.parentElement.nextElementSibling.firstElementChild;
            // On appelle le controleur qui renvois la vue partielle
            $.ajax({
                url: "/admin/job/"+idJob,
                type: "GET",
                dataType: "json",
                success: function (result)
                {
                    // On stocke la vue partielle dans la description
                    descriptionContent.innerHTML = result;

                    // Au changement de value de l'input
                    $('input#job_personnality_value').on('input', function ()
                    {
                        var jobId = this.nextElementSibling.value;
                        var personnalityTypeId = this.nextElementSibling.nextElementSibling.value;
                        var value = this.value;


                        // On appelle la route qui permet de sauvegarder les changements
                        $.ajax({
                            url: "/admin/saveJobPersonnality",
                            type: "POST",
                            data: {
                                job: jobId,
                                personnalityType: personnalityTypeId,
                                value: value
                            },
                            success: function (result)
                            {
                                // A la réussite on affiche le message de succes
                                console.log(result.message)
                            },
                            error: function (error)
                            {
                                console.log(error.message);
                            }
                        })


                    });
                },
                error: function (error)
                {
                    descriptionContent.innerHTML = "Erreur lors du chargement";
                    console.log(error.statusText)
                }
            });
        }
    });
});
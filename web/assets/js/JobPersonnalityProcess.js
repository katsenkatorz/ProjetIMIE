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

            // Au changement de value de l'input
            $('.rangeInput').unbind('click').bind('click', function ()
            {
                var value = this.value;
                var jobId = this.nextElementSibling.value;
                var temperamentId = this.nextElementSibling.nextElementSibling.value;

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
                        // A la réussite on affiche le message de succes
                        console.log(result.message)
                    },
                    error: function (error)
                    {
                        console.log(error.message);
                    }
                });
            });
        },
        error: function (error)
        {
            descriptionContent.html("Erreur lors du chargement");
            console.log(error.statusText)
        }
    });
}

$(document).ready(function ()
{
    var JobPanel = $("a.getJobPersonnalityView");

    $('.modifJob').unbind('click').bind('click', function (e)
    {
        e.preventDefault();

        var jobId = $(this).attr('data-id');
        var name = $('#modifName'+jobId).val();
        var minSalary = $('#modifMinSalary'+jobId).val();
        var maxSalary = $('#modifMaxSalary'+jobId).val();
        var description = $('#modifDescription'+jobId).val();
        var resultContent = $('#descriptionContent'+jobId);

        $.ajax({
            url: "/admin/job/"+jobId+"/put",
            type: "PUT",
            dataType: "json",
            data: {
                name: name,
                minSalary: minSalary,
                maxSalary: maxSalary,
                description: description
            },
            success: function (result)
            {
                loadPartielView(jobId, resultContent);
                $('#collapseTitle'+jobId).text(result.name);
            },
            error: function (error)
            {
                console.log(error.message);
            }
        })

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
                console.log(result.message);

                window.location = window.location;
            },
            error: function (error)
            {
                console.log(error.message);
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
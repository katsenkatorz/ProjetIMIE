$(document).ready(function ()
{

    // Pour chaque éléments qui contienne un liens a de classe getJobPersonnalityView
    $(".panel-title").on("click" ,function ()
    {

        var idQuestion = $(this).data('id');
        console.log();
        var responseContent = $(".responseContent"+idQuestion);

        $.ajax({
            url: "/admin/getResponsesFromQuestion/" + idQuestion,
            type: "GET",
            dataType: "json",
            success: function (result)
            {
                // On stocke la vue partielle dans la content de l'accordeon
                responseContent.html(result);


                // Au click sur valider
                // $("p.modalSubmit").on('click', function ()
                // {
                //     console.log(this.previousElementSibling.previousElementSibling);
                //
                //     var value = 1;
                //     var image = 1;
                //     var label = 1;
                //     var personnalityType = 1;
                //     var responseId = this.previousElementSibling.previousElementSibling.value;
                //
                //     // var jobId = this.nextElementSibling.nextElementSibling.value;
                //     // var personnalityTypeId = this.nextElementSibling.nextElementSibling.nextElementSibling.value;
                //     // var value = this.value;
                //     // // On appelle la route qui permet de sauvegarder les changements
                //     // $.ajax({
                //     //     url: "/admin/saveJobPersonnality",
                //     //     type: "POST",
                //     //     data: {
                //     //         job: jobId,
                //     //         personnalityType: personnalityTypeId,
                //     //         value: value
                //     //     },
                //     //     success: function (result)
                //     //     {
                //     //         // A la réussite on affiche le message de succes
                //     //         $("p.SaveModificationMessage").html(result.message);
                //     //     },
                //     //     error: function (error)
                //     //     {
                //     //         $("p.SaveModificationMessage").html(error)
                //     //     }
                //     // })
                // });
            },
            error: function (error)
            {
                console.log(error.statusText);
            }
        });
    });


    // [].forEach.call(array, function (elem)
    // {
    //     // On lui ajoute un event au onClick
    //     elem.onclick = function ()
    //     {
    //         console.log(this.parentElement.parentElement.parentElement.nextElementSibling.id);
    //
    //         var idQuestion = this.parentElement.parentElement.parentElement.nextElementSibling.id;
    //
    //         var responseContent = this.parentElement.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild;
    //
    //         $.ajax({
    //             url: "/admin/getResponsesFromQuestion/"+idQuestion,
    //             type: "GET",
    //             dataType: "json",
    //             success: function (result)
    //             {
    //                 // On stocke la vue partielle dans la content de l'accordeon
    //                 responseContent.innerHTML = result;
    //
    //
    //                 // Au click sur valider
    //                 // $("p.modalSubmit").on('click', function ()
    //                 // {
    //                 //     console.log(this.previousElementSibling.previousElementSibling);
    //                 //
    //                 //     var value = 1;
    //                 //     var image = 1;
    //                 //     var label = 1;
    //                 //     var personnalityType = 1;
    //                 //     var responseId = this.previousElementSibling.previousElementSibling.value;
    //                 //
    //                 //     // var jobId = this.nextElementSibling.nextElementSibling.value;
    //                 //     // var personnalityTypeId = this.nextElementSibling.nextElementSibling.nextElementSibling.value;
    //                 //     // var value = this.value;
    //                 //     // // On appelle la route qui permet de sauvegarder les changements
    //                 //     // $.ajax({
    //                 //     //     url: "/admin/saveJobPersonnality",
    //                 //     //     type: "POST",
    //                 //     //     data: {
    //                 //     //         job: jobId,
    //                 //     //         personnalityType: personnalityTypeId,
    //                 //     //         value: value
    //                 //     //     },
    //                 //     //     success: function (result)
    //                 //     //     {
    //                 //     //         // A la réussite on affiche le message de succes
    //                 //     //         $("p.SaveModificationMessage").html(result.message);
    //                 //     //     },
    //                 //     //     error: function (error)
    //                 //     //     {
    //                 //     //         $("p.SaveModificationMessage").html(error)
    //                 //     //     }
    //                 //     // })
    //                 // });
    //             },
    //             error: function (error)
    //             {
    //                 console.log(error.statusText);
    //             }
    //         });
    //     };
    // });
});
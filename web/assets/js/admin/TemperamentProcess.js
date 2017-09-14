$(document).ready(function () {

    //Ouverture de la modale de confirmation de suppression
    $('#modalConfirm').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id'); // Extract info from data-* attributes
        var name = button.data('name'); // Extract info from data-* attributes
        var modal = $(this);
        var href = button.data('href');

        $('#confirm').attr('href', href);
        modal.find('.modal-title').text('Confirmation de la suppression de ' + name);
        modal.find('.modal-body p').html('Etes-vous sûr de supprimer le tempérament :&nbsp;<strong>' + name + '</strong>');

    });

    // Mécanisme d'update
    $('.updateButton').each(function()
    {
        $(this).unbind('click').bind('click', function()
        {
            // Récupération des formulaires
            var idTemperament = $(this).attr('data-id');
            var name = $('#nameUpdate'+idTemperament).val();
            var temperament = $('#temperamentUpdate'+idTemperament).val();
            var opposedTemperament = $('#opposedTemperamentUpdate'+idTemperament).val();

            $.ajax({
                url: "/tdb-admin/temperament/put/"+idTemperament,
                type: "PUT",
                datatype: "json",
                data: {
                    name: name,
                    temperament: temperament,
                    opposedTemperament: opposedTemperament
                },
                success: function (result)
                {
                    // Actualisation de l'affichage
                    $("#temperamentTitle"+idTemperament).html(result.name);
                    $("#temperament"+idTemperament).html(result.temperament);
                    $("#opposedTemperament"+idTemperament).html(result.opposedTemperament);

                    // Fermeture du modal
                    $('#modalUpdateTemperament' + idTemperament).modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();

                    // Affichage du message d'erreur
                    $("#responseMessageContent")
                        .fadeIn(250)
                        .removeClass('hidden');
                    $("#responseMessage").html(result.message);

                    // Suppression du message d'erreur
                    setTimeout(function ()
                    {
                        $("#responseMessageContent").fadeOut(250);
                    }, 5000);
                }
            })
        });
    });
});
$(document).ready(function () {

    //Ouverture de la modale de confirmation de suppréssion
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
});
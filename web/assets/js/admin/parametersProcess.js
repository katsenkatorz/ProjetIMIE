$(document).ready(function () {
    $(".param").unbind('change').bind('change', function ()
    {
        var parameterId = $(this).attr("data-id");
        var value = $(this).val();
        var label = $(this).attr("data-label");

        $.ajax({
            url: "/admin/parameter/put/"+parameterId,
            method: "PUT",
            data: {
                label: label,
                value: value
            },
            success: function (result)
            {
                $("#responseMessageContent")
                    .fadeIn(250)
                    .removeClass('hidden');
                $("#responseMessage").html(result.message);
                setTimeout(function ()
                {
                    $("#responseMessageContent").fadeOut(250);
                }, 5000);
            },
            error: function (error) {}
        });
    });

    var imageInput = $('#parameterValue5');

    // Si le paramètre de gestion des length existe:
    if(imageInput.length !== 0)
    {
        // On applique le process de transformation de l'input
        loadImageGenProcess(imageInput, function()
        {
            // Récupération des inputs
            var widthInput = $('#widthInput');
            var heightInput = $('#heightInput');
            var imageResizer = $('#imageResizer');
            var numberInput = $('.numberInput');

            // Pour chaque changement sur un input
            numberInput.unbind('change').bind('change', function(e)
            {
                e.preventDefault();

                // On récupère la largeur et la hauteur
                var width = widthInput.val();
                var height = heightInput.val();

                // Fonction qui récupère la value d'une image vide
                getImageValue(imageResizer, width, height, function (resp)
                {
                    // On prépare la string a enregistrer en base de données
                    var array = '{ "width": '+width+', "height": '+height+', "emptyImageString": "'+resp+'" }';

                    // On envoie la requête ajax
                    $.ajax({
                        url: "/admin/parameter/putValue/"+imageInput.data('id'),
                        method: "PUT",
                        data: {
                            "value": array
                        }
                    }).done(function (response)
                    {
                        // Affichage du message de success
                        $("#responseMessageContent")
                            .fadeIn(250)
                            .removeClass('hidden');
                        $("#responseMessage").html(response.message);
                        setTimeout(function ()
                        {
                            $("#responseMessageContent").fadeOut(250);
                        }, 5000);
                    });
                });
            });

        });
    }

    function getImageValue(imageResizer, width, height, callback)
    {
        imageResizer.show();

        $uploadCrop = imageResizer.croppie({
            enableExif: true,
            viewport: {
                width: width,
                height: height,
                type: 'square'
            },
            boundary: {
                width: 300,
                height: 300
            }
        });

        $uploadCrop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (resp)
        {
            callback(resp)
        });

        imageResizer.hide();
    }

    // Génère le html pour la gestion de la largeur/hauteur d'image
    function loadImageGenProcess(imageInput, callback)
    {
        imageInput.hide();

        var imageData = JSON.parse(imageInput.val());

        var widthValue = imageData.width;
        var heightValue = imageData.height;

        var contentDiv = imageInput.parent();

        // Créer une nouvelle row
        var divRow = document.createElement('div');
        divRow.className = "row";

        // Créer une nouvelle colonne
        var inputWidthHeightHolder = document.createElement('div');
        inputWidthHeightHolder.className = "col-md-12";

        // Créer une nouvelle colonne
        var imageResizerHolder = document.createElement('div');
        imageResizerHolder.className = "col-md-12";

        // Créer la zone de message d'alerte
        var alertMessage = document.createElement("p");
        alertMessage.innerHTML = "Attention, tout changement de largeur et/ou de hauteur n'affecte pas les images déjà ajouté !";
        alertMessage.style.color = 'red';

        // Créer l'input qui va contenir la largueur
        var widthInput = document.createElement("input");
        widthInput.className = "param form-control numberInput";
        widthInput.type = "number";
        widthInput.value = widthValue;
        widthInput.id = "widthInput";

        // Créer l'input qui va contenir la hauteur
        var heightInput = document.createElement("input");
        heightInput.className = "param form-control numberInput";
        heightInput.type = "number";
        heightInput.value = heightValue;
        heightInput.id = "heightInput";

        // Créer le label pour l'input qui va contenir la largeur
        var widthLabel = document.createElement('label');
        widthLabel.innerHTML = "Largeur: ";
        widthLabel.className = "param-title";

        // Créer le label pour l'input qui va contenir la hauteur
        var heightLabel = document.createElement('label');
        heightLabel.innerHTML = "Hauteur: ";
        heightLabel.className = "param-title";

        // Créer la div qui va contenir le resizer d'image
        var imageResizer = document.createElement('div');
        imageResizer.id = "imageResizer";

        // Imbrication des divs
        imageResizerHolder.append(imageResizer);

        inputWidthHeightHolder.append(alertMessage);
        inputWidthHeightHolder.append(widthLabel);
        inputWidthHeightHolder.append(widthInput);
        inputWidthHeightHolder.append(document.createElement("br"));
        inputWidthHeightHolder.append(heightLabel);
        inputWidthHeightHolder.append(heightInput);

        divRow.append(inputWidthHeightHolder);
        divRow.append(imageResizerHolder);

        contentDiv.append(divRow);

        // Appel de fonction en callback
        callback();
    }
});


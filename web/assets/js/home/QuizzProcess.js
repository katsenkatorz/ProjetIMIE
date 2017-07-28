$(document).ready(function ()
{
    getQuestionSetIntoLocalStorage(function ()
    {
        genQuizz(getCookie("lastQuestionToStart"), function ()
        {
            getImageValue();
            showPrevButton();
            handleProgressBar();
            genImg();
        });
    });

    nextQuestionProcess();
    prevQuestionProcess();


    // Génère le quizz sur la page
    function genQuizz(number, callback)
    {
        // On récupère la questionSet
        var questionSet = JSON.parse(localStorage.getItem("questionSet" + number));

        // On récupère le responseContainer
        var responseContainer = document.querySelector('#responseContainer');

        // On écrit le numeros de la question
        document.querySelector("#questionLabel").innerHTML = questionSet.questionLabel;

        // Permet de créer une id par réponse
        var i = 0;

        questionSet.responses.forEach(function (response)
        {
            // On crée la div qui gère le placement du quizz
            var colDiv = document.createElement("div");
            colDiv.className = "col-md-4 col-sm-6";

            // On crée la div qui contient la class thumbnail
            var thumbnail = document.createElement("a");
            thumbnail.className = "thumbnail imgContent";
            thumbnail.setAttribute('data-value', response.value);
            thumbnail.setAttribute('data-temperament', questionSet.idTemperament);
            thumbnail.setAttribute('data-number', questionSet.questionNumber);
            thumbnail.id = "imgContent" + i;

            // On crée la div qui contient l'image de la réponse
            var imgContent = document.createElement("img");
            imgContent.src = "assets/img/imageResponse/" + response.imageName;
            imgContent.alt = "Image indisponible";

            // On crée la div qui contient le nom de l'image
            var responseLabel = document.createElement('h3');
            responseLabel.className = "text-center subtitleQuizz";
            responseLabel.innerHTML = response.label;

            // On ajoute l'image et sont nom au thumbnail
            thumbnail.appendChild(imgContent);
            thumbnail.appendChild(responseLabel);

            // Puis le thumbnail a la div de placement
            colDiv.appendChild(thumbnail);

            // Et enfin la div de placement au responseContainer
            responseContainer.appendChild(colDiv);

            i++;
        });

        // On appelle une callback pour les traitements synchrones
        callback();
    }

    // Permet de gérer la progression du questionnaire
    function handleProgressBar()
    {
        var progressBar = $('.progress-bar');
        var questionNumber = parseInt(getCookie("lastQuestionToStart")) + 1;
        var newWidth = (questionNumber * 100) / localStorage.length;

        progressBar.attr('aria-valuemax', localStorage.length);
        progressBar.attr('aria-valuenow', questionNumber);
        progressBar.css("width", newWidth + "%");
    }

    // Récupère la value de l'image
    function getImageValue()
    {
        // Au click sur l'image
        $('.imgContent').unbind('click').bind('click', function ()
        {
            // On récupère les data-attributes sur l'image
            var value = $(this).data("value");
            var questionNumber = $(this).data("number");
            var temperamentId = $(this).data("temperament");
            var idImage = $(this).attr('id');

            // On les met dans un tableau
            var responseContent = {
                value: value,
                temperamentId: temperamentId,
                idImage: idImage
            };

            // On génère un cookie
            setCookie("responseContent" + questionNumber, JSON.stringify(responseContent), 1);

            // On affiche le bouton next
            $('#next').removeClass('disabled');

            // Sur chaque image, on enlève la classe imageBorder (qui permet d'afficher la sélection)
            $('.imgContent').each(function ()
            {
                $(this).removeClass("imageBorder");
            });

            // Sur l'image sur laquelle on a cliquer on ajoute imageBorder
            $(this).addClass("imageBorder");
        });
    }

    // Fonction qui permet d'afficher le bouton précédent
    function showPrevButton()
    {
        // On récupère le cookie qui contient le numéro de la dernière réponse valider
        var questionNumber = getCookie("lastQuestionToStart");

        // Si la dernière réponse valider n'est pas la première, on affiche le bouton précedent
        if (questionNumber > 0)
            $('#prev').removeClass('disabled');
    }

    // Fonction qui permet de passé à la question suivante
    function nextQuestionProcess()
    {
        // Au click sur le bouton suivant
        $('#next').unbind('click').bind('click', function ()
        {
            var href = $(this).data("href");

            // On récupère le numéro de la question en cours
            var questionNumber = getCookie("lastQuestionToStart");

            // Pour eviter de dépasser le nombre de question
            if (questionNumber < localStorage.length - 1)
            {
                // On met à jours le numéro de la question pour avoir celle de la suivante
                setCookie("lastQuestionToStart", parseInt(questionNumber) + 1, 1);

                // On reset le contenu de responseContainer
                $('#responseContainer').html("");

                // On regénère le quizz
                genQuizz(getCookie("lastQuestionToStart"), function ()
                {
                    getImageValue();
                    showPrevButton();
                    handleProgressBar();
                    selectRespondedResult();
                    genImg();
                });
            }
            else
            {
                var responses = [];

                for (var i = 0; i < localStorage.length; i++)
                {
                    responses.push(JSON.parse(getCookie("responseContent" + i)));
                }

                $.ajax({
                    url: href,
                    type: "POST",
                    data: {"responses": responses}
                }).done(function (result)
                {
                    localStorage.clear();
                    deleteAllCookies();

                    location.href = result.href;
                })
            }
        });
    }

    // Fonction qui permet de revenir à la question précedente
    function prevQuestionProcess()
    {
        // Au clic sur le bouton précédent
        $('#prev').unbind('click').bind('click', function ()
        {
            // On récupère le numeros de la question en cours
            var questionNumber = getCookie("lastQuestionToStart");

            // Tant que le numeros de la question en cour est supérieur à 0
            if (questionNumber > 0)
            {
                // On fait -1 au cookie qui contient la question en cours
                setCookie("lastQuestionToStart", parseInt(questionNumber) - 1, 1);

                // On reset le responseContainer
                $('#responseContainer').html("");
                // On le remplis avec la question précédente
                genQuizz(getCookie("lastQuestionToStart"), function ()
                {
                    getImageValue();
                    showPrevButton();
                    handleProgressBar();
                    selectRespondedResult();
                    genImg();
                });

                // Si la question qui est en cours est la première, on cache le bouton précedent
                if (parseInt(getCookie("lastQuestionToStart")) === 0)
                    $(this).addClass('disabled');
            }
        });
    }

    function selectRespondedResult()
    {
        var unParsedResponseContent = getCookie("responseContent" + getCookie("lastQuestionToStart"));
        if (unParsedResponseContent.length > 0)
        {
            var responseContent = JSON.parse(unParsedResponseContent);

            if (typeof responseContent !== undefined)
            {
                $("#" + responseContent.idImage).trigger('click');
                $('#next').removeClass('disabled')
            }
        }
        else
        {
            $('#next').addClass('disabled');
        }
    }

    function genImg()
    {
        var imgs = [
            'perso_pose2',
            'perso_pose3',
            'perso_pose4',
            'perso_pose5',
            'perso_pose6',
            'perso_pose7',
            'perso_pose8'
        ];

        var perso = document.querySelector('#perso');
        var img = imgs[Math.floor(Math.random() * imgs.length)];

        perso.src = "assets/img/perso/" + img + '.png';
    }
});

/**
 * On récupère le tableau de questionSet et on le fragmente, puis enregistre chaque fragment dans le localStorage
 */
function getQuestionSetIntoLocalStorage(callback)
{
    if (localStorage.length < 1)
    {
        setCookie("lastQuestionToStart", 0, 1);
        $.ajax({
            url: "/questionSet",
            type: "GET",
            async: false
        }).done(function (result)
        {
            var j = 0;

            result.forEach(function (questionSet)
            {
                var arraySet = {};

                // On récupère l'id du tempérament
                arraySet.idTemperament = questionSet.temperament.id;

                questionSet.questions.forEach(function (arrayQuestion)
                {
                    // On récupère le numeros de la question
                    arraySet.questionNumber = arrayQuestion.questionNumber;

                    // On récupère le label de la question
                    arraySet.questionLabel = arrayQuestion.question[0].label;

                    // On récupère les réponses de la question
                    arraySet.responses = arrayQuestion.question[1];

                    // On stocke le tableau dans le localStorage
                    localStorage.setItem("questionSet" + j, JSON.stringify(arraySet));
                    j++;
                });
            });
        });
    }
    callback();
}

// Cookies handler functions
function setCookie(cname, cvalue, exdays)
{
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname)
{
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++)
    {
        var c = ca[i];
        while (c.charAt(0) === ' ')
        {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0)
        {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function deleteAllCookies()
{
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++)
    {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
}



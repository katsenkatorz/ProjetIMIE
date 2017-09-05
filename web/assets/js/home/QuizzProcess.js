$(document).ready(function ()
{

    // On initialise une variable qui va contenir l'objet qui gère l'animation
    var pT;

    // recaptcha = onloadCallback();

    setTimeout(function ()
    {
        var titi = grecaptcha.execute(recaptcha);

        console.log(titi);
    }, 5000);

    try
    {
        // On initialise le test
        getQuestionSetIntoLocalStorage(function ()
        {
            genQuizz(getCookie("lastQuestionToStart"), function ()
            {
                getImageValue();
                handleProgressBar();
                genImg();
                pT = new PageTransionner();
            });
        });
    }
    catch (e)
    {
        resetQuizz();
        var responseDiv = document.querySelector('#questionLabel');
        responseDiv.innerHTML = "<h3>Il semble y avoir eu un problème, veuillez actualiser la page ou contacter un administrateur</h3>";
        genImg();
    }

    /**
     *  Génère le quizz sur la page au premier chargement
     *
     *  @param number Numeros de la question en cours
     *  @param callback Fonction des callbacks
     */
    function genQuizz(number, callback)
    {
        // On récupère la questionSet qui va remplir la pt-page numero 1
        var questionSetOne = JSON.parse(localStorage.getItem("questionSet" + number));

        // On affiche le label de la question
        document.querySelector("#questionLabel").innerHTML = questionSetOne.questionLabel;

        // On récupère le nombre de réponses
        var responseLength = questionSetOne.responses.length;

        // On crée la div principal permettant l'animation de slide
        var ptMain = document.createElement("div");
        ptMain.id = "pt-main";
        ptMain.className = "pt-perspective";

        // Traitement spécial en fonction du nombre de réponses pour l'affichage
        switch (responseLength)
        {
            case 2:
                handleResponse(questionSetOne, false, ptMain, "col-md-6");
                break;
            case 3:
                handleResponse(questionSetOne, false, ptMain, "col-md-4");
                break;
            case 4:
                handleResponse(questionSetOne, false, ptMain, "col-md-6");
                break;
            case 5:
                handleResponse(questionSetOne, false, ptMain, "col-md-4", true);
                break;
            case 6:
                handleResponse(questionSetOne, false, ptMain, "col-md-4");
                break;
        }

        // Si on est arriver a la dernière question au chargement du test, la condition permet de ne pas essayer de charger la question suivante
        if (!!JSON.parse(localStorage.getItem("questionSet" + (parseInt(number) + 1))))
        {
            // On charge la question suivante dans le deuxième ptPage
            var questionSetTwo = JSON.parse(localStorage.getItem("questionSet" + (parseInt(number) + 1)));

            // On récupère la taille des réponses pour ce questionSet
            responseLength = questionSetTwo.responses.length;

            // Traitement spécial en fonction du nombre de réponses pour l'affichage
            switch (responseLength)
            {
                case 2:
                    handleResponse(questionSetTwo, true, ptMain, "col-md-6");
                    break;
                case 3:
                    handleResponse(questionSetTwo, true, ptMain, "col-md-4");
                    break;
                case 4:
                    handleResponse(questionSetTwo, true, ptMain, "col-md-6");
                    break;
                case 5:
                    handleResponse(questionSetTwo, true, ptMain, "col-md-4", true);
                    break;
                case 6:
                    handleResponse(questionSetTwo, true, ptMain, "col-md-4");
                    break;
            }
        }

        // On appelle une callback pour les traitements synchrones
        callback();
    }

    /**
     * Permet le chargement des réponses dans le ptMain
     *
     * @param questionSet La questionSet a traiter
     * @param isSecond Permet de savoir si il faut générer les réponses dans la ptPage 1 ou 2
     * @param ptMain Permet de récupérer le ptMain
     * @param colDivClassName Permet de setter la structure bootstrap pour l'affichage
     * @param bool Pour le cas particulier ou il y a 5 réponses
     */

    function handleResponse(questionSet, isSecond, ptMain, colDivClassName, bool)
    {
        // Booléen étant a true lorsque on a une question à 5 réponse
        bool = bool || false;

        // On récupère le responseContainer
        var responseContainer = document.querySelector('#responseContainer');

        // On crée la div secondaire permettant l'animation de slide
        var ptPage = document.createElement("div");
        ptPage.className = "pt-page hidden";
        ptPage.id = "first-pt-page";

        if (isSecond)
            ptPage.id = "second-pt-page";


        // Permet de créer une id par réponse
        var i = 0;

        questionSet.responses.forEach(function (response)
        {
            // On crée la div qui gère le placement du quizz
            var colDiv = document.createElement("div");

            colDiv.className = colDivClassName;

            // Si on a une question a 5 réponse
            if (bool && i === 3)
                colDiv.className = colDivClassName + " col-md-offset-2";

            // On crée la div qui contient la class thumbnail
            var thumbnail = document.createElement("a");
            thumbnail.className = "thumbnail imgContent";
            thumbnail.setAttribute('data-value', response.value);
            thumbnail.setAttribute('data-temperament', questionSet.idTemperament);
            thumbnail.setAttribute('data-number', questionSet.questionNumber);
            thumbnail.setAttribute('data-href', document.querySelector("#hrefInput").value);
            thumbnail.id = "imgContent" + i;

            // On crée la div qui contient l'image de la réponse
            var imgContent = document.createElement("img");
            imgContent.src = "assets/img/imageResponse/" + response.imageName;
            imgContent.alt = "Image indisponible";
            imgContent.width = document.querySelector('#widthInput').value;
            imgContent.height = document.querySelector('#heightInput').value;
            imgContent.style.width = document.querySelector('#widthInput').value;
            imgContent.style.height = document.querySelector('#heightInput').value;

            // On crée la div qui contient le nom de l'image
            var responseLabel = document.createElement('h3');
            responseLabel.className = "text-center subtitleQuizz";
            responseLabel.innerHTML = response.label;

            // On ajoute l'image et son nom au thumbnail
            thumbnail.appendChild(imgContent);
            thumbnail.appendChild(responseLabel);

            // Puis le thumbnail a la div de placement
            colDiv.appendChild(thumbnail);

            // On ajoute le tout a la ptPage
            ptPage.appendChild(colDiv);

            // Incrémentation du compteur
            i++;
        });

        // On envois le tout dans le ptMain
        ptMain.appendChild(ptPage);

        // Puis dans la response container
        responseContainer.appendChild(ptMain);
    }

    // Permet de gérer la progression du questionnaire
    function handleProgressBar()
    {
        // On récupère la progressBar
        var progressBar = $('.progress-bar');

        // On récupère le numeros de la question en cours
        var questionNumber = parseInt(getCookie("lastQuestionToStart")) + 1;
        
        // On set la width
        var newWidth = ((questionNumber -1) * 100) / getCookie("NumberOfQuestion");

        // On ajoute tout au html
        progressBar.attr('aria-valuemax', getCookie("NumberOfQuestion"));
        progressBar.attr('aria-valuenow', questionNumber);
        progressBar.css("width", newWidth + "%");
    }

    // Récupère la value de l'image, créer le cookie de la réponse, et permet de passer à la question suivante
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
            var href = $(this).data("href");

            // On les met dans un tableau
            var responseContent = {
                value: value,
                temperamentId: temperamentId,
                idImage: idImage
            };

            // On génère un cookie
            setCookie("responseContent" + questionNumber, JSON.stringify(responseContent), 1);

            // On appelle le process de la question suivante
            nextQuestion(questionNumber, href);
        });
    }

    /**
     * Gère le passage au question suivante
     *
     * @param questionNumber Le numeros de la question en cours
     * @param href Le lien de résolution du quizz
     */
    function nextQuestion(questionNumber, href)
    {
        // Pour eviter de dépasser le nombre de question
        if (questionNumber < getCookie("NumberOfQuestion") - 1)
        {
            // On met à jours le numéro de la question pour avoir celle de la suivante
            setCookie("lastQuestionToStart", parseInt(questionNumber) + 1, 1);

            // On appelle l'event de la question suivante
            pT.nextPage(function ()
            {
                nextLoadingProcess(function ()
                {
                    getImageValue();
                    handleProgressBar();
                    genImg();
                });
            });

            /**
             * Permet de charger la question suivante
             *
             * @param callback Permet d'ajouter les évents du quizz
             */
            function nextLoadingProcess(callback)
            {
                // On récupère les ptPage
                var firstPtPage = $('#first-pt-page');
                var secondPtPage = $('#second-pt-page');

                // On récupère la question en cours
                var number = getCookie('lastQuestionToStart');

                // On prépare la variable questionSet
                var questionSet;

                // On récupère la questionSet
                questionSet = JSON.parse(localStorage.getItem("questionSet" + number));

                // On écris le titre de la question
                document.querySelector("#questionLabel").innerHTML = questionSet.questionLabel;

                // On récupère la taille des réponses de la question
                var responseLength = questionSet.responses.length;

                // Si la question en cours est paire
                if (number % 2 === 0)
                {
                    // Traitement spécial en fonction du nombre de réponses pour l'affichage
                    switch (responseLength)
                    {
                        case 2:
                            loading(firstPtPage, "col-md-6");
                            break;
                        case 3:
                            loading(firstPtPage, "col-md-4");
                            break;
                        case 4:
                            loading(firstPtPage, "col-md-6");
                            break;
                        case 5:
                            loading(firstPtPage, "col-md-4", true);
                            break;
                        case 6:
                            loading(firstPtPage, "col-md-4");
                            break;
                    }
                }

                // Si la question en cours est impaire
                if (number % 2 !== 0)
                {
                    // Traitement spécial en fonction du nombre de réponses pour l'affichage
                    switch (responseLength)
                    {
                        case 2:
                            loading(secondPtPage, "col-md-6");
                            break;
                        case 3:
                            loading(secondPtPage, "col-md-4");
                            break;
                        case 4:
                            loading(secondPtPage, "col-md-6");
                            break;
                        case 5:
                            loading(secondPtPage, "col-md-4", true);
                            break;
                        case 6:
                            loading(secondPtPage, "col-md-4");
                            break;
                    }
                }

                /**
                 * Chargement des réponses
                 *
                 * @param ptPage Div dans laquelle charger les réponses
                 * @param colDivMd Permet de setter la structure bootstrap pour l'affichage
                 * @param bool Pour le cas particulier ou il y a 5 réponses
                 */
                function loading(ptPage, colDivMd, bool)
                {
                    bool = bool || false;

                    // Vidage de la ptPage
                    ptPage.html("");

                    // Permet de créer une id par réponse
                    var i = 0;

                    // Pour chaque réponses
                    questionSet.responses.forEach(function (response)
                    {
                        // On crée la div qui gère le placement du quizz
                        var colDiv = document.createElement("div");

                        colDiv.className = colDivMd;

                        // Si on a une question a 5 réponse
                        if (bool && i === 3)
                            colDiv.className = colDivMd + " col-md-offset-2";

                        // On crée la div qui contient la class thumbnail
                        var thumbnail = document.createElement("a");
                        thumbnail.className = "thumbnail imgContent";
                        thumbnail.setAttribute('data-value', response.value);
                        thumbnail.setAttribute('data-temperament', questionSet.idTemperament);
                        thumbnail.setAttribute('data-number', questionSet.questionNumber);
                        thumbnail.setAttribute('data-href', document.querySelector("#hrefInput").value);
                        thumbnail.id = "imgContent" + i;

                        // On crée la div qui contient l'image de la réponse
                        var imgContent = document.createElement("img");
                        imgContent.src = "assets/img/imageResponse/" + response.imageName;
                        imgContent.alt = "Image indisponible";
                        imgContent.width = document.querySelector('#widthInput').value;
                        imgContent.height = document.querySelector('#heightInput').value;
                        imgContent.style.width = document.querySelector('#widthInput').value;
                        imgContent.style.height = document.querySelector('#heightInput').value;

                        // On crée la div qui contient le nom de l'image
                        var responseLabel = document.createElement('h3');
                        responseLabel.className = "text-center subtitleQuizz";
                        responseLabel.innerHTML = response.label;

                        // On ajoute l'image et son nom au thumbnail
                        thumbnail.appendChild(imgContent);
                        thumbnail.appendChild(responseLabel);

                        // Puis le thumbnail a la div de placement
                        colDiv.appendChild(thumbnail);

                        // Ajout des réponses au ptPage
                        ptPage.append(colDiv);

                        // Incrémentation du compteur
                        i++;
                    });
                }

                callback();
            }
        }
        else
        {
            // On initialise un tableau de réponses
            var responses = [];
            // On récupère le body
            var container = $('.container');
            var body = $('#my-body');

            // On remplis le tableau
            for (var i = 0; i < getCookie("NumberOfQuestion"); i++)
            {
                responses.push(JSON.parse(getCookie("responseContent" + i)));
            }

            // On envoie la requête ajax qui résous le test
            $.ajax({
                url: href,
                type: "POST",
                data: {"responses": responses},
                beforeSend: function ()
                {
                    $('#modalLoading').modal('show');
                }
            }).done(function (result)
            {
                setTimeout(function ()
                {
                    // On reset le quizz
                    resetQuizz();

                    // On charge la page de résultat
                    body.hide().html('').fadeOut('slow');
                    body.html(result.page).fadeIn('slow');

                    // Changement de l'url
                    window.history.pushState("", "", result.href);

                    // Permet de compenser la disparition de la barre de scroll
                    $('html').css("overflow", "auto");

                    // Fermeture du modal
                    setTimeout(function ()
                    {
                        $('#modalLoading').hide();
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    }, 600)

                }, 1000)
            }).fail(function () {})
        }
    }

    // Génération de l'image du personnage aléatoire
    function genImg()
    {
        // On crée un tableau
        var imgs = [];

        // On cible la div contenant le personnage
        var perso = document.querySelector('#perso');

        // On remplis le tableau avec les images du personnage du site
        for (var i = 2; i < 9; i++)
        {
            imgs.push('perso_pose' + i);
        }

        // On récupère une image aléatoire
        var img = imgs[Math.floor(Math.random() * imgs.length)];

        // On l'affiche
        perso.src = "assets/img/perso/" + img + '.png';
    }
});

/**
 * On récupère le tableau de questionSet et on le fragmente, puis enregistre chaque fragment dans le localStorage
 *
 * @param callback
 */
function getQuestionSetIntoLocalStorage(callback)
{
    if (getCookie("NumberOfQuestion") <= 0 || getCookie("lastQuestionToStart").length <= 0)
    {
        // On crée le cookie qui va être utilisé pour gérer l'ordre des questions
        setCookie("lastQuestionToStart", 0, 1);

        // On envoie la requête ajax qui permet de récupérer les questions
        $.ajax({
            url: "/questionSet",
            type: "GET",
            async: false
        }).done(function (result)
        {
            // On initialise un compteur
            var j = 0;

            // Pour chaque résultat
            result.forEach(function (questionSet)
            {
                // On crée un objet vide
                var objectSet = {};

                // On récupère l'id du tempérament
                objectSet.idTemperament = questionSet.temperament.id;

                questionSet.questions.forEach(function (objectQuestion)
                {
                    // On récupère le numeros de la question
                    objectSet.questionNumber = objectQuestion.questionNumber;

                    // On récupère le label de la question
                    objectSet.questionLabel = objectQuestion.question[0].label;

                    // On récupère les réponses de la question
                    objectSet.responses = objectQuestion.question[1];

                    // On stocke le tableau dans le localStorage
                    localStorage.setItem("questionSet" + j, JSON.stringify(objectSet));

                    // On incrémente le compteur
                    j++;
                });
            });

            // On crée le cookie qui contient le nombre total de fonction
            setCookie("NumberOfQuestion", j);
        });
    }

    // On fait les traitements à la fin de la requête ajax
    callback();
}



/************** Fonction servant à gérer les cookies **************/

// Modifie un cookie
function setCookie(cname, cvalue, exdays)
{
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// Récupère un cookie
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

// Supprime tout les cookies
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

// Reset du quizz
function resetQuizz()
{
    // On clear le localstorage
    localStorage.clear();

    // On clear les cookies
    deleteAllCookies();
}

/************** Fonction servant à la redirection avec envois de données **************/
function redirect(redirectUrl, arg, value)
{
    var form = $('<form action="' + redirectUrl + '" method="post">' +
        '<input type="hidden" name="' + arg + '" value="' + value + '"></input>' + '</form>');
    $('body').append(form);
    $(form).submit();
}


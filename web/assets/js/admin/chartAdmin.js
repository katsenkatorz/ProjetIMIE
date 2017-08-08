$(document).ready(function () {

    genQuestionByType();

    genVisitorByCountry();

    var selectYearInput = $('#registedYear');

    genVisitorByYear(selectYearInput.val());

    selectYearInput.unbind('change').bind('change', function ()
    {
        genVisitorByYear($(this).val());
    });
});

function genVisitorByYear(year)
{
    $.ajax({
        url: "/admin/visitors/"+year,
        method: "GET"
    }).done(function (data)
    {

        // On crée un tableau de référence pour les mois
        var monthsName = [
            "",
            "Janvier",
            "Fevrier",
            "Mars",
            "Avril",
            "Mai",
            "Juin",
            "Juillet",
            "Aout",
            "Septembre",
            "Octobre",
            "Novembre",
            "Decembre"
        ];

        // On crée un tableau de référence mois => valeur
        var yearlyMonthValue = {
            "Janvier": 0,
            "Fevrier": 0,
            "Mars": 0,
            "Avril": 0,
            "Mai": 0,
            "Juin": 0,
            "Juillet": 0,
            "Aout": 0,
            "Septembre": 0,
            "Octobre": 0,
            "Novembre": 0,
            "Decembre": 0
        };

        // Pour chaque valeur réçus
        data.forEach(function (elem)
        {
            // On récupère la valeur du mois pour avoir la string correspondante
            elem.month = monthsName[elem.month];

            // On remplis le tableau de référence avec les nouvelles values
            yearlyMonthValue[elem.month] = parseInt(elem[1]);
        });

        // On prépare le tableau qui va avoir les values
        var values = [];

        // On retire le premier index du tableau
        monthsName.splice(0, 1);

        // A partir du tableau de référence on stocke les valeurs ordonnées pour chaque mois
        monthsName.forEach(function (elem)
        {
            values.push(yearlyMonthValue[elem]);
        });

        // On génère le graphique
        var ctxUser = document.getElementById("userChart").getContext('2d');
        var userChart = new Chart(ctxUser, {
            type: 'line',
            data: {
                labels: monthsName,
                datasets: [{
                    backgroundColor: "#3e95cd",
                    label: "Questions par Tempéraments",
                    data: values
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Nombre de visiteur par année'
                },
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        stacked: true
                    }],
                    yAxes: [{
                        stacked: true,
                        ticks: {
                            beginAtZero: true,
                            stepSize: 10
                        }
                    }]
                }
            }
        });

    }).fail(function (error)
    {
        console.log("error");
    })
}


function genVisitorByCountry()
{
    var inputs = document.querySelectorAll(".visitorCountryDataHolder");

    var labels = [];
    var values = [];
    var backgroundColor = ["#8e5ea2", "#3e95cd", "#3cba9f", "#e8c3b9", "#c45850"];

    inputs.forEach(function (elem)
    {
        labels.push(elem.dataset.country);
        values.push(parseInt(elem.dataset.value))
    });

    if(values.length > backgroundColor.length)
    {
        var colorLength = backgroundColor.length;
        var interval = values.length - colorLength;

        for(var i = 0; i < interval; i++)
        {
            backgroundColor.push(backgroundColor[i]);
        }
    }

    var ctxTemp = document.getElementById("visitorByCountry").getContext('2d');

    new Chart(ctxTemp, {
        type: 'polarArea',
        data: {
            labels: labels,
            datasets:
            [{
                backgroundColor: backgroundColor,
                data: values
            }]
        },
        options: {
            responsive: true,
            legend: {
                position: 'right'
            },
            title: {
                display: true
            },
            scale: {
                ticks: {
                    beginAtZero: true
                },
                reverse: false
            },
            animation: {
                animateRotate: false,
                animateScale: true
            }
        }
    });
}


function genQuestionByType()
{
    var inputs = document.querySelectorAll(".questionDataHolder");

    var labels = [];
    var values = [];
    var valid = [];
    var unValid = [];
    var backgroundColor = ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850"];

    inputs.forEach(function (elem)
    {
        labels.push(elem.dataset.type);
        valid.push(parseInt(elem.dataset.valid));
        unValid.push(parseInt(elem.dataset.nonvalid))
    });

    values.push(valid);
    values.push(unValid);

    if(values.length > backgroundColor.length)
    {
        var colorLength = backgroundColor.length;
        var interval = values.length - colorLength;

        for(var i = 0; i < interval; i++)
        {
            backgroundColor.push(backgroundColor[i]);
        }
    }

    document.querySelector("#valid").innerHTML = "Nombre de question valide: "+values[0].sum();
    document.querySelector("#nonValid").innerHTML = "Nombre de question non valide: "+values[1].sum();

    var ctxTemp = document.getElementById("questionByType").getContext('2d');
    new Chart(ctxTemp, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Question non valide',
                    backgoundColor: backgroundColor,
                    data: values[1]
                },
                {
                    label: 'Question valide',
                    backgroundColor: backgroundColor,
                    data: values[0]
                }
            ]
        },
        options: {
            legend: {
                display: false
            },
            responsive: true,
            scales: {
                xAxes: [{
                    stacked: true
                }],
                yAxes: [{
                    stacked: true,
                    ticks: {
                        beginAtZero: true,
                        stepSize: 10
                    }
                }]
            }
        }
    });
}


Array.prototype.sum = function(selector) {
    if (typeof selector !== 'function') {
        selector = function(item) {
            return item;
        }
    }
    var sum = 0;
    for (var i = 0; i < this.length; i++) {
        sum += parseFloat(selector(this[i]));
    }
    return sum;
};


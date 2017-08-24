var userChart;
var testChart;
var nonStartedChart;

$(document).ready(function () {

    var targets = ["#userChart", "#getNbTestChart", "#unstartedTestChart"];
    var selectYearInput = $('#registedYear');

    selectYearInput.val(new Date().getFullYear());

    userChart = genVisitorByYear(selectYearInput.val());
    testChart = genAchieveAndUnAchieveTest(selectYearInput.val());
    nonStartedChart = genUnstartedTest(selectYearInput.val());


    selectYearInput.unbind('change').bind('change', function ()
    {
        userChart.destroy();
        testChart.destroy();
        nonStartedChart.destroy();

        genVisitorByYear($(this).val());
        genAchieveAndUnAchieveTest($(this).val());
        genUnstartedTest($(this).val());
    });


    modifySize(targets, function () {
        genQuestionByType();

        genVisitorByCountry();

        genVisitorByBrowser();

        genSharedTest();
    });

});

function modifySize(targets, callback)
{
    targets.forEach(function (idTarget)
    {
        var target = document.querySelector(idTarget);

        if ($(window).width() <= 768) {
            target.height = 100;
        }
        else {
            target.height = 20;
        }

        $(window).unbind('resize').bind('resize', function () {
            if ($(window).width() <= 768) {
                target.height = 100;
            }
            else {
                target.height = 20;
            }
        });
    });
    callback();
}

function genVisitorByYear(year) {
    $.ajax({
        url: "/admin/visitors/" + year,
        method: "GET"
    }).done(function (data) {

        var result = orderValueToMonth(data);

        var max = Math.max(result.values);

        // On génère le graphique
        var ctxUser = document.getElementById("userChart").getContext('2d');
        userChart = new Chart(ctxUser, {
            type: 'line',
            data: {
                labels: result.month,
                datasets: [{
                    backgroundColor: "#3e95cd",
                    label: "utilisateurs mensuels",
                    data: result.values
                }]
            },
            options: {
                responsive: true,
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
                            stepSize: max
                        }
                    }]
                }
            }
        });

    }).fail(function (error) {
        console.log("error");
    });

    return userChart;
}

function genSharedTest()
{
    var hiddenSharedInput = $('#visitorWhoShared');
    var numberShared = hiddenSharedInput.data('number');

    var hiddenDontSharedInput = $('#visitorWhoDontShared');
    var numberDontShared = hiddenDontSharedInput.data('number');

    var backgroundColor = ["#3e95cd", "#c45850"];

    // On génère le graphique
    var ctxNav = document.getElementById("sharedChart").getContext('2d');
    sharedChart = new Chart(ctxNav, {
        type: 'pie',
        data: {
            labels: ['Partagés', 'Non partagés'],
            datasets: [{
                backgroundColor: backgroundColor,
                data: [numberShared, numberDontShared]
            }]
        },
        options: {
            responsive: true,
            title: {
                display: false,
                text: 'Nombre de tests partagés par rapport au nombre de tests réalisés'
            }
        }
    });

    return sharedChart;
}

function genAchieveAndUnAchieveTest(year)
{
    var backgroundColor;
    var max;

    $.ajax({
        url: '/admin/visitors/quizz/'+year+'/0',
        method: 'GET'
    }).done(function (data)
    {
        var achieveData = orderValueToMonth(data.value);

        $.ajax({
            url: '/admin/visitors/quizz/'+year+'/1',
            method: 'GET'
        }).done(function (data)
        {
            var unAchieveData = orderValueToMonth(data.value);

            if(achieveData.values.length > unAchieveData.values.length)
            {
                backgroundColor = createBackgroundColor(achieveData.values);
                max = Math.max(achieveData.values);
            }
            else
            {
                backgroundColor = createBackgroundColor(unAchieveData.values);
                max = Math.max(unAchieveData.values);
            }

            var ctxTemp = document.getElementById("getNbTestChart").getContext('2d');
            testChart = new Chart(ctxTemp, {
                type: 'line',
                data: {
                    labels: achieveData.month,
                    datasets: [
                        {
                            label: 'Quizz non finis',
                            borderColor: "#cd1e10",
                            data: unAchieveData.values,
                            fill: false,
                            pointRadius: 10
                        },
                        {
                            label: 'Quizz finis',
                            borderColor: "#3e95cd",
                            data: achieveData.values,
                            fill: false,
                            pointRadius: 10
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
                            stacked: false
                        }],
                        yAxes: [{
                            stacked: false,
                            ticks: {
                                beginAtZero: true,
                                stepSize: max
                            }
                        }]
                    },
                    elements: {
                        point: {
                            pointStyle: 'crossRot'
                        }
                    }
                }
            });
        }).fail(function () {});
    }).fail(function () {});

    return testChart;
}

function genUnstartedTest(year)
{

    $.ajax({
        url: '/admin/visitors/quizz/'+year+'/2',
        method: 'GET'
    }).done(function (data)
    {
        var unStartedData = orderValueToMonth(data.value);

        var max = Math.max(unStartedData.values);

        var ctxTemp = document.getElementById("unstartedTestChart").getContext('2d');
        nonStartedChart = new Chart(ctxTemp, {
            type: 'line',
            data: {
                labels: unStartedData.month,
                datasets: [{
                    label: 'Quizz non finis',
                    borderColor: "#3e95cd",
                    data: unStartedData.values,
                    fill: false,
                    pointRadius: 10
                }]
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
                            stepSize: max
                        }
                    }]
                },
                elements: {
                    point: {
                        pointStyle: 'rectRot',
                        backgroundColor: "#3e95cd"
                    }
                }
            }
        });
    }).fail(function () {});

    return nonStartedChart;
}

function genVisitorByBrowser(browser) {
    $.ajax({
        url: "/admin/browser",
        method: "GET"
    }).done(function (data)
    {
        var values = [];
        var labels = [];

        data.forEach(function (elem) {
            values.push(elem['1']);
            labels.push(elem['browser']);
        });

        var backgroundColor = createBackgroundColor(values);

        // On génère le graphique
        var ctxNav = document.getElementById("navChart").getContext('2d');
        var navChart = new Chart(ctxNav, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    backgroundColor: backgroundColor,
                    label: "Navigateurs utilisés",
                    data: values
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: false,
                    text: 'Navigateurs utilisés'
                }
            }
        });

    }).fail(function (error) {
        console.log("error");
    })
}


function genVisitorByCountry() {
    var inputs = document.querySelectorAll(".visitorCountryDataHolder");

    var labels = [];
    var values = [];
    var backgroundColor = ["#8e5ea2", "#3e95cd", "#3cba9f", "#e8c3b9", "#c45850"];

    inputs.forEach(function (elem) {
        labels.push(elem.dataset.country);
        values.push(parseInt(elem.dataset.value))
    });

    if (values.length > backgroundColor.length) {
        var colorLength = backgroundColor.length;
        var interval = values.length - colorLength;

        for (var i = 0; i < interval; i++) {
            backgroundColor.push(backgroundColor[i]);
        }
    }

    var ctxTemp = document.getElementById("visitorByCountry").getContext('2d');

    new Chart(ctxTemp, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                backgroundColor: backgroundColor,
                data: values
            }]
        },
        options: {
            responsive: true,
            title: {
                display: false
            }
        }
    });
}



function genQuestionByType() {
    var inputs = document.querySelectorAll(".questionDataHolder");

    var labels = [];
    var values = [];
    var valid = [];
    var unValid = [];
    var backgroundColor = ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850"];

    inputs.forEach(function (elem) {
        labels.push(elem.dataset.type);
        valid.push(parseInt(elem.dataset.valid));
        unValid.push(parseInt(elem.dataset.nonvalid))
    });

    values.push(valid);
    values.push(unValid);

    if (values.length > backgroundColor.length) {
        var colorLength = backgroundColor.length;
        var interval = values.length - colorLength;

        for (var i = 0; i < interval; i++) {
            backgroundColor.push(backgroundColor[i]);
        }
    }

    var max = Math.max(values);

    document.querySelector("#valid").innerHTML = values[0].sum();
    document.querySelector("#nonValid").innerHTML = values[1].sum();

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
                        stepSize: max
                    }
                }]
            }
        }
    });
}

function orderValueToMonth(array)
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
    array.forEach(function (elem) {
        // On récupère la valeur du mois pour avoir la string correspondante
        elem.month = monthsName[elem.month];

        // On remplis le tableau de référence avec les nouvelles values
        yearlyMonthValue[elem.month] = parseInt(elem.number);
    });

    // On prépare le tableau qui va avoir les values
    var values = [];

    // On retire le premier index du tableau
    monthsName.splice(0, 1);

    // A partir du tableau de référence on stocke les valeurs ordonnées pour chaque mois
    monthsName.forEach(function (elem) {
        values.push(yearlyMonthValue[elem]);
    });

    return {month: monthsName, values: values};
}


function createBackgroundColor(values)
{
    var backgroundColor = ["#8e5ea2", "#3e95cd", "#3cba9f", "#e8c3b9", "#c45850"];

    if (values.length > backgroundColor.length)
    {
        var colorLength = backgroundColor.length;
        var interval = values.length - colorLength;

        for (var i = 0; i < interval; i++) {
            backgroundColor.push(backgroundColor[i]);
        }
    }

    return backgroundColor;
}

Array.prototype.sum = function (selector) {
    if (typeof selector !== 'function') {
        selector = function (item) {
            return item;
        }
    }
    var sum = 0;
    for (var i = 0; i < this.length; i++) {
        sum += parseFloat(selector(this[i]));
    }
    return sum;
};


$(document).ready(function () {
    var inputs = document.querySelectorAll(".dataHolder");

    var labels = [];
    var values = [];
    var backgroundColor = ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850"];

    var valid = [];
    var unValid = [];

    inputs.forEach(function (elem) {
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

    var tempChartData = {
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
    };

    var ctxTemp = document.getElementById("tempChart").getContext('2d');
    var tempChart = new Chart(ctxTemp, {
        type: 'bar',
        data: tempChartData,
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

    var ctxUser = document.getElementById("userChart").getContext('2d');
    var userChart = new Chart(ctxUser, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: "Questions par Tempéraments",
                data: values
            }]
        },
        options: {
            title: {
                display: true,
                text: 'Nombre de question : '+ labels.length
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
                        max: values.length,
                        stepSize: 1
                    }
                }]
            }
        }
    });
});


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
$(document).ready(function () {
    var inputs = document.querySelectorAll(".dataHolder");

    var labels = [];
    var values = [];
    var backgroundColor = ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850"];

    inputs.forEach(function (elem) {
        labels.push(elem.dataset.type);

        values.push(parseInt(elem.value));
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

    var ctxTemp = document.getElementById("tempChart").getContext('2d');
    var tempChart = new Chart(ctxTemp, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: "Questions par Tempéraments",
                backgroundColor: backgroundColor,
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

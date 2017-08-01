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

    var ctx = document.getElementById("barChart").getContext('2d');
    var barChart = new Chart(ctx, {
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
});

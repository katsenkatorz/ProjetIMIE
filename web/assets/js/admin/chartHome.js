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

    var ctx = document.getElementById("pieChart").getContext('2d');
    var pieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: "Population (millions)",
                backgroundColor: backgroundColor,
                data: values
            }]
        },
        options: {
            title: {
                display: true,
                text: 'Nombre de question ajoutées'
            }
        }
    });
});

$(document).ready(function () {

    genGraph();
    sendShared();

    function sendShared()
    {
        var button = $('#sharedBtn');
        var href = button.data('href');

        button.unbind('click').bind('click', function ()
        {
            $.ajax({
                url: href,
                method: 'POST'
            }).done(function (data)
            {

            }).fail(function (error)
            {

            })
        });
    }


    function genGraph()
    {
        var inputs = document.querySelectorAll(".dataHolder");

        var labels = [];
        var values = [];

        inputs.forEach(function (elem) {
            labels.push(elem.dataset.type);

            if (elem.value < 0)
                elem.value = -elem.value;

            values.push(parseInt(elem.value));
        });

        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: [
                        'rgba(137, 133, 177, 0.2)'
                    ],
                    borderColor: [
                        'rgba(137, 133, 177, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                legend: {display: false},
                title: {
                    display: true,
                    text: 'Résultats du test'
                },
                scale: {
                    ticks: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }
});
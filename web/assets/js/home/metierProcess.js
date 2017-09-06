$(document).ready(function ()
{
    genGraph();
    sendShared();
    genUserGraph();


    function sendShared()
    {
        var button = $('#sharedBtn');
        var href = button.data('href');

        button.unbind('click').bind('click', function ()
        {
            $.ajax({
                url: href,
                method: 'POST'
            }).done(function (data) {}).fail(function (error) {})
        });
    }

    function genUserGraph()
    {
        var inputQuizzResult = $('#sessionUserQuizzResult');
        var quizzResult = inputQuizzResult.val();

        var labels = [];
        var values = [];

        if(typeof quizzResult !== "undefined")
        {
            var results = JSON.parse(quizzResult);
            $.each(results, function (key, element)
            {
                labels.push(element.temperament);

                var value = element.moyenne;

                if (value < 0)
                    value = - value;

                values.push(value);
            });


            var ctx = document.getElementById("userChart").getContext('2d');
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
        else
        {
            return false;
        }
    }

    function genGraph()
    {
        var inputs = $(".dataHolder");

        var labels = [];
        var values = [];

        inputs.each(function () {
            var elem = $(this);

            labels.push(elem.data('type'));

            var value = elem.val();

            if (value < 0)
                value = - value;

            values.push(parseInt(value));
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
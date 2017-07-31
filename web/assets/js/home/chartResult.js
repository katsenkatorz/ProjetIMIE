$(document).ready(function () {
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

// var ctx = document.getElementById("myChart").getContext('2d');
// var myChart = new Chart(ctx, {
//     type: 'horizontalBar',
//     data: {
//         labels: ["Introverti", "Pensée", "Observateur", "Prospection", "Prudent"],
//         datasets: [{
//             data: [49, 89, 12, 56, 26]
//         }]
//     },
//     options: {
//         legend: { display: false },
//         title: {
//             display: true,
//             text: 'Résultats du test'
//         }
//     }
// });
});
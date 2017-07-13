var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'horizontalBar',
    data: {
        labels: ["Introverti | Extraverti", "Sentiment | Pensée", "Observateur | Intuitif", "Jugement | Prospection", "Assuré | Prudent"],
        datasets: [{
            data: [-49, 89, -12, 56, -26],
            backgroundColor: [
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255,99,132,1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            xAxes: [{
                ticks: {
                    min: -100,
                    max: 100
                }
            }],
            yAxes: [{
                stacked: true
            }]
        },
        legend: { display: false },
        title: {
            display: true,
            text: 'Résultats du test'
        }
    }
});
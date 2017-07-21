var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'radar',
    data: {
        labels: ["Introverti", "Pensée", "Observateur", "Prospection", "Prudent"],
        datasets: [{
            data: [49, 89, 12, 56, 26],
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
        legend: { display: false },
        title: {
            display: true,
            text: 'Résultats du test'
        }
    }
});
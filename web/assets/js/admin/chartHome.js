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

    var ctx = document.getElementById("pieChart").getContext('2d');
    var pieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ["Esprit", "Energie", "Nature", "Tactique", "Identité"],
            datasets: [{
                label: "Population (millions)",
                backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850"],
                data: [2478, 5267, 734, 784, 433]
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

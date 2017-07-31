var ctx = document.getElementById("pieChart").getContext('2d');
var pieChart = new Chart(ctx,{
    type: 'pie',
    data: data,
    options: options
});

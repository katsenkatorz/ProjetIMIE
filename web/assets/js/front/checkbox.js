$('img').click(function(){
    $('.selected').removeClass('selected'); // removes the previous selected class
    $(this).addClass('selected'); // adds the class to the clicked image
});

$('#ex1').slider({
    formatter: function(value) {
        return 'Current value: ' + value;
    }
});

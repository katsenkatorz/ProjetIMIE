$(document).ready(function () {

    var nav = $('.question');
    $(window).scroll(function () {
        if ($(this).scrollTop() < $(window).height()) {
            nav.addClass("f-question");
        }

        if($(this).scrollTop() === 0){
            nav.removeClass("f-question");
        }
    })
});

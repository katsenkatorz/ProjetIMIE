window.fbAsyncInit = function() {
    FB.init({
        appId      : '312787115798930',
        xfbml      : true,
        version    : 'v2.10'
    });
    FB.AppEvents.logPageView();
};

document.getElementById('sharedBtn').onclick = function() {
    FB.ui({
        method: 'share',
        display: 'popup',
        mobile_iframe: true,
        href: 'https://developers.facebook.com/docs/'
    }, function (response) {
    });
};

(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
window.fbAsyncInit = function() {
    FB.init({
        appId      : '1731974550440072',
        xfbml      : true,
        version    : 'v2.10'
    });
    FB.AppEvents.logPageView();
};

document.getElementById('sharedBtn').onclick = function() {
    FB.ui({
        method: 'share_open_graph',
        display: 'popup',
        mobile_iframe: true,
        href: 'https://developers.facebook.com/docs/'
    }, function (response) {
    });
};

(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.10&appId=1731974550440072";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


/*twitter login/signup*/
(function ($) {
    //  inspired by DISQUS
    $.oauthpopup = function (options) {
        if (!options || !options.path) {
            throw new Error("options.path must not be empty");
        }
        options = $.extend({
            windowName: 'ConnectWithOAuth' // should not include space for IE
            , windowOptions: 'location=0,status=0,width=800,height=400', callback: function () {
                window.location.reload();
            }
        }, options);

        var oauthWindow = window.open(options.path, options.windowName, options.windowOptions);
        var oauthInterval = window.setInterval(function () {
            if (oauthWindow.closed) {
                window.clearInterval(oauthInterval);
                options.callback();
            }
        }, 1000);
    };

    //bind to element and pop oauth when clicked
    $.fn.oauthpopup = function (options) {
        $this = $(this);
        $this.click($.oauthpopup.bind(this, options));
    };

})(jQuery);
function twitterLogin(){
    $.oauthpopup({
        path: ajaxurl+'?action=twitterLogin',
        callback: function(){
            // window.location.reload();
        }
    });
}

/*google plus login*/
(function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/client.js?onload=onLoadCallback';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
function GooglePluslogin()
{
    var myParams = {
        //'clientid' : '45053474216-oet4c90b6qk7ldej707uot3r1qsvmcvv.apps.googleusercontent.com',
        'clientid' : '1054411368753-51n7ds9kbephrjqeqdnvvpdq07b9ggv1.apps.googleusercontent.com',
        'cookiepolicy' : 'single_host_origin',
        'callback' : 'loginCallback',
        'approvalprompt':'force',
        'scope' : 'https://www.googleapis.com/auth/plus.profile.emails.read'
    };
    gapi.auth.signIn(myParams);
}

function loginCallback(result)
{
    if(result['status']['signed_in'])
    {
        var request = gapi.client.plus.people.get(
            {
                'userId': 'me'
            });
        request.execute(function (resp)
        {
            console.log(resp);
            var email = '';
            var fname = '';
            var lname = '';
            var gender = '';
            var image = '';
            var id = '';
            id = resp['id'];
            if(resp['emails'])
            {
                for(i = 0; i < resp['emails'].length; i++)
                {
                    if(resp['emails'][i]['type'] == 'account')
                    {
                        email = resp['emails'][i]['value'];
                    }
                }
            }


            if(resp['name']['givenName'])
            {
                fname = resp['name']['givenName'];
            }

            if(resp['name']['familyName'])
            {
                lname = resp['name']['familyName'];
            }
            if(resp['gender'])
            {
                gender = resp['gender'];
            }
            if(resp['image']['url'])
            {
                image = resp['image']['url'].replace('?sz=500','');
            }

            $.ajax({
                type:"POST",
                url: ajaxurl,
                dataType:'json',
                cache:"false",
                data:{'action' : 'social_login',social_type:"facebook",'socialId':id,'first_name':fname,'last_name':lname,'email':email,'gender':gender,'socialImage':image},
                success:function(data){
                    var response = data;
                    if(response.success){
                        ajaxLoaderStop();
                        window.location.href = response.redirect_to;
                    }
                },
            });
        });

    }

}
function onLoadCallback()
{
    //gapi.client.setApiKey('AIzaSyDFwBtlPtYj5uhmvXB5dgAdKLP56tyA8TU');
    gapi.client.setApiKey('AIzaSyCP_AF2IQqXcaADE9MJIDaS4egmuhmZEcw');
    gapi.client.load('plus', 'v1',function(){
    });
}


window.fbAsyncInit = function() {
    FB.init({
        appId      : '1535827290053874',
        status     : true, // check login status
        cookie     : true, // enable cookies to allow the server to access the session
        xfbml      : true,
        version    : 'v2.5'
    });
};

(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function Login()
    {
        FB.login(function(){
            FB.api('me?fields=id,first_name,last_name,email,gender',
                function(response){
                    //console.log(response); return false;
                    if(response.error){ }
                    else{
                        ajaxLoaderStart();
                        var socialImage_path = "https://graph.facebook.com/"+response.id+"/picture?type=large";
                        jQuery.ajax({
                            type:"POST",
                            url:ajaxurl,
                            dataType:'json',
                            data:{action:"social_login",social_type:"facebook",socialId :response.id, first_name:response.first_name, last_name:response.last_name, socialImage:socialImage_path, email:response.email, gender:response.gender},
                            success:function(data){
                                var response = data;
                                if(response.success){
                                    ajaxLoaderStop();
                                    window.location.href = response.redirect_to;
                                }
                            }
                        });
                    }
});
},{'scope':'email'});


}




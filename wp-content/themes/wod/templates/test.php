<?php
/* Template Name: Test */

/*?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Facebook Login JavaScript Example</title>
        <meta charset="UTF-8">
    </head>
    <body>
    <script>
        // This is called with the results from from FB.getLoginStatus().
        function statusChangeCallback(response) {
            console.log('statusChangeCallback');
            console.log(response);
            // The response object is returned with a status field that lets the
            // app know the current login status of the person.
            // Full docs on the response object can be found in the documentation
            // for FB.getLoginStatus().
            if (response.status === 'connected') {
                // Logged into your app and Facebook.
                testAPI();
            } else if (response.status === 'not_authorized') {
                // The person is logged into Facebook, but not your app.
                document.getElementById('status').innerHTML = 'Please log ' +
                    'into this app.';
            } else {
                // The person is not logged into Facebook, so we're not sure if
                // they are logged into this app or not.
                document.getElementById('status').innerHTML = 'Please log ' +
                    'into Facebook.';
            }
        }

        // This function is called when someone finishes with the Login
        // Button.  See the onlogin handler attached to it in the sample
        // code below.
        function checkLoginState() {
            FB.getLoginStatus(function(response) {
                statusChangeCallback(response);
            });
        }

        window.fbAsyncInit = function() {
            FB.init({
                appId      : '1536117516716635',
                cookie     : true,  // enable cookies to allow the server to access
                // the session
                xfbml      : true,  // parse social plugins on this page
                version    : 'v2.2' // use version 2.2
            });

            // Now that we've initialized the JavaScript SDK, we call
            // FB.getLoginStatus().  This function gets the state of the
            // person visiting this page and can return one of three states to
            // the callback you provide.  They can be:
            //
            // 1. Logged into your app ('connected')
            // 2. Logged into Facebook, but not your app ('not_authorized')
            // 3. Not logged into Facebook and can't tell if they are logged into
            //    your app or not.
            //
            // These three cases are handled in the callback function.

            FB.getLoginStatus(function(response) {
                console.log(response);
                statusChangeCallback(response);
            });

        };

        // Load the SDK asynchronously
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        // Here we run a very simple test of the Graph API after login is
        // successful.  See statusChangeCallback() for when this call is made.
        function testAPI() {
            console.log('Welcome!  Fetching your information.... ');
            FB.api('/me', function(response) {

                console.log(response);
                console.log('Successful login for: ' + response.name);
                console.log('Successful email  for: ' + response.emailAddress);
                document.getElementById('status').innerHTML =
                    'Thanks for logging in, ' + response.name + '!';
            });
        }
    </script>

    <!--
      Below we include the Login Button social plugin. This button uses
      the JavaScript SDK to present a graphical Login button that triggers
      the FB.login() function when clicked.
    -->

    <fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
    </fb:login-button>

    <div id="status">
    </div>

    </body>
    </html>





<?php
*/
?>





<html>
<body>
<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '1536117516716635', // App ID
            channelUrl : '//connect.facebook.net/en_US/all.js', // Channel File
            status     : true, // check login status
            cookie     : true, // enable cookies to allow the server to access the session
            xfbml      : true  // parse XFBML
        });


        FB.Event.subscribe('auth.authResponseChange', function(response)
        {
            console.log(response);
            if (response.status === 'connected')
            {
                document.getElementById("message").innerHTML +=  "<br>Connected to Facebook:)-";
                //SUCCESS

            }
            else if (response.status === 'not_authorized')
            {
                document.getElementById("message").innerHTML +=  "<br>Failed to Connect";

                //FAILED
            } else
            {
                document.getElementById("message").innerHTML +=  "<br>Logged Out here.";

                //UNKNOWN ERROR
            }
        });

    };

    function Login()
    {

        FB.login(function(){
            FB.api('me?fields=id,first_name,last_name,email,gender',
                function(response){
                    console.log(response);
                    if (response.email)
                    {
                        getUserInfo(response);
                    } else
                    {
                        console.log('User cancelled login or did not fully authorize.');
                    }

                });
        },{'scope':'email'});


        /*FB.login(function(response) {

            console.log(response);
            if (response.authResponse)
            {
                getUserInfo(response);
            } else
            {
                console.log('User cancelled login or did not fully authorize.');
            }
        },{scope: 'user_photos,id,first_name,last_name,email,gender'});*/


    }

    function getUserInfo(response) {


            var str="<b>First Name</b> : "+response.first_name+"<br>";
            str +="<b>Last Name: </b>"+response.last_name+"<br>";
            str +="<b>Gender: </b>"+response.gender+"<br>";
            str +="<b>id: </b>"+response.id+"<br>";
            str +="<b>Email:</b> "+response.email+"<br>";
            str +="<input type='button' value='Get Photo' onclick='getPhoto();'/>";
            str +="<input type='button' value='Logout' onclick='Logout();'/>";
            document.getElementById("status").innerHTML=str;


    }
    function getPhoto()
    {
        FB.api('/me/picture?type=normal', function(response) {

            var str="<br/><b>Pic</b> : <img src='"+response.data.url+"'/>";
            document.getElementById("status").innerHTML+=str;

        });

    }
    function Logout()
    {
        FB.logout(function(){document.location.reload();});
    }

    // Load the SDK asynchronously
    (function(d){
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement('script'); js.id = id; js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        ref.parentNode.insertBefore(js, ref);
    }(document));

</script>
<div align="center">
    <h2>Facebook OAuth Javascript Demo</h2>

    <div id="status">
        Click on Below Image to start the demo: <br/>
        <img src="<?php bloginfo('stylesheet_directory'); ?>/js/facebook-javascript-sdk/LoginWithFacebook.png" style="cursor:pointer;" onclick="Login()"/>
    </div>
    <br/><br/><br/><br/><br/>
    <div id="message">
        Logs:<br/>
    </div>

</div>
</body>
</html>
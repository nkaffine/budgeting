<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/6/17
 * Time: 1:29 PM
 */
    require_once('db.php');
    // Checks to see if there is a message to display
	if(isset($_GET['message'])){
        $message = validInputSizeAlpha($_GET['message'], 140);
    }
	?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Kaffine Budgeting</title>
        <meta charset="utf-8">
        <!--Stuff required for bootstrap-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!--importing javascript file and style sheets from the server-->
        <script src="scripts/login.js"></script>
        <link rel="stylesheet" href="style_sheets/main.css">
        <style>
            img {
                width: 2em;
            }

        </style>
        <script>
            // This is called with the results from from FB.getLoginStatus().
            function statusChangeCallback(response) {
                if (response.status === 'connected') {
                    // Logged into your app and Facebook.
                    login(response, response.authResponse.accessToken);
                } else {
                    // The person is not logged into this app or we are unable to tell.
                }
            }

            function checkLoginState() {
                FB.getLoginStatus(function(response) {
                    statusChangeCallback(response);
                });
            }

            window.fbAsyncInit = function() {
                FB.init({
                    appId      : '1896491627270600',
                    cookie     : true,
                    xfbml      : true,
                    version    : 'v2.8'
                });
                FB.getLoginStatus(function(response) {
                    statusChangeCallback(response);
                });
            };

            (function(d, s, id){
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));

            function login(response, accessToken) {
                FB.api('/me', { locale: 'en_US', fields: 'first_name, last_name, email, age_range, gender' },
                    function(response) {
                        createCookie("sessionId", accessToken, 30);
                        postFbInfo(response.id, accessToken, response.first_name, response.last_name, response.gender, response.email, response.age_range.min, response.age_range.max);
                    });

            }

            $(document).ready(function(){
                $('#fbbutton').click(function(){
                    FB.login(function(response) {
                        checkLoginState();
                    }, { scope: 'email,public_profile' });
                });
            });
        </script>

    </head>
    <body>
        <div id="fb-root"></div>
            <?php
            if(isset($message)){
                echo"<div class='col-lg-4 col-lg-offset-4' style='margin-bottom: -2%;' class='panel panel-danger'>".
                    "<div class='panel-heading'>Alert:</div>".
                    "<div class='panel-body'>{$message}</div></div>'";
            }
            ?>
            <div class="valign">
                <div style="background-color: black;" class="col-lg-4 col-lg-offset-4">
                    <h1 style="color:white; margin-top: 8%;" class="text-center">Kaffine Budgeting</h1>
                    <button id="fbbutton" class="btn btn-lg btn-primary btn-facebook col-lg-8 col-lg-offset-2">
                        <img src="https://www.facebook.com/rsrc.php/v3/y-/r/q5WSVI6B16O.png">Login with Facebook</button>
                </div>
            </div>
        <form id='fbinfo' style="display: none;">
        </form>
    </body>
</html>

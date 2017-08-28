<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/27/17
 * Time: 3:57 PM
 */
    require_once('db.php');
    require_once('header.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("20-1-7");
    }
    $user_id = logincheck("20-2", "20-3");
    $menu = getHeaderInfo("20-4");
    if(count($_POST)){
        if(isset($_POST['account_id'])){
            $account_id = validNumbers($_POST['account_id'], 10);
        }
        if(isset($_POST['type'])){
            $type = validNumbers($_POST['type'], 1);
        }
    }
    if(!isset($type) || !isset($account_id)){
        error("20-5-1");
    }
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Delete Account</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!--Stuff for selectors-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
        <script src="scripts/main.js"></script>
        <link rel="stylesheet" href="style_sheets/main.css">
        <script src="scripts/transactions.js"></script>
        <script src="scripts/timeFormatting.js"></script>
        <script>
            $(document).ready(function(){
                var type = document.getElementById('type').value;
                $("#no").click(function(){
                    if(type == 0){
                        window.location = "accounts.php";
                    } else if (type == 1 || type == 2){
                        window.location = "categories.php";
                    } else if(type == 3){
                        window.location = "accountsReceivable.php";
                    } else if(type == 4){
                        window.location = "accountsPayable.php";
                    } else {
                        window.location = "home.php";
                    }
                })
            })
        </script>
    </head>
    <body>
        <?php placeHeader($menu)?>
        <div class="box col-lg-4 col-lg-offset-4">
            <h1>Are you sure you want to delete the account?</h1>
            <form action="process/deleteAccount.php" method="post">
                <?php echo"<input type='hidden' name='account_id' value='{$account_id}'>
                            <input id='type' type='hidden' name='type' value='{$type}'>"; ?>
                <input type="submit" value="Yes" class="btn btn-default col-lg-6">
                <button id='no' type='button' class="btn btn-primary col-lg-6">No</button>
                &nbsp;
            </form>
        </div>
        <div class='col-lg-12' style="height:20vh;></div>
    </body>
</html>

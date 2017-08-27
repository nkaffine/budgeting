<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/27/17
 * Time: 11:33 AM
 */
    require_once('db.php');
    require_once('header.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("13-1-7");
    }
    $user_id = logincheck("13-2", "13-3");
    $menu = getHeaderInfo("13-4");
    if(count($_POST)){
        $id = validNumbers($_POST['id'], 10);
    }
    if(empty($id)){
        error("13-5-1");
    }
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Remove Transaction</title>
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
                $("#no").click(function(){
                    window.location = "transactions.php";
                })
            })
        </script>
    </head>
    <body>
        <?php placeHeader($menu)?>
        <div class="box col-lg-4 col-lg-offset-4">
            <h1>Are you sure you want to remove the transaction?</h1>
            <form action="process/removeTransaction.php" method="post">
                <?php echo"<input type='hidden' name='id' value='{$id}'>"; ?>
                <input type="submit" value="Yes" class="btn btn-default col-lg-6">
                <button id='no' type='button' class="btn btn-primary col-lg-6">No</button>
                &nbsp;
            </form>
        </div>
    </body>
</html>
